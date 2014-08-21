<?php

App::uses('SNMPDevice', 'SimpleSNMP');
App::uses('CiscoSNMPDevice', 'SimpleSNMP');

class DiscoveryShell extends AppShell {

    public $uses = array(
        'Setting',
        'DeviceAttrOid',
        'Device',
        'DeviceNeighbor'
    );

    static $discoveryAttrs = array(
        'name',
        'model',
        'firmware',
        'serial'
    );

    public function main() {

        //Retrieve settings
        $this->settings = $this->Setting->get(array(
            'global',
            'discovery'
        ));

        //Retrieve device SNMP attr oids
        $this->attrOids = $this->DeviceAttrOid->groupedBySysObjectId();

        //Retrieve and format seeds
        $seeds = explode(',', $this->settings['discovery.seeds']);
        $seeds = array_map('trim', $seeds);

        $this->queue = $seeds;
        $discoveredDevices = array();

        while(count($this->queue) > 0) {

            $deviceIpAddr = array_pop($this->queue);

            //Check against blacklist
            $ipBlacklistFilter = $this->settings['discovery.ip_blacklist'];
            if(!empty($ipBlacklistFilter)){
                if(preg_match($ipBlacklistFilter, $deviceIpAddr)){
                    $this->log("Skipping device ${deviceIpAddr} b/c its ip address is blacklisted", 'info', 'discovery');
                    continue;
                }
            }

            //Check against whitelist
            $ipWhitelistFilter = $this->settings['discovery.ip_whitelist'];
            if(!empty($ipWhitelistFilter)){
                if(!preg_match($ipWhitelistFilter, $deviceIpAddr, $matches)){
                    $this->log("Skipping device ${deviceIpAddr} b/c its ip address does not match the whitelist", 'info', 'discovery');
                    continue;
                }
            }

            //Check if this device has already been discovered
            if(isset($discoveredDevices[$deviceIpAddr]))
                continue;

            //Mark device as discovered
            $discoveredDevices["$deviceIpAddr"] = true;

            $this->_discoverDevice($deviceIpAddr);
        }
    }

    private function _discoverDevice($ipAddr){

        $device = array(
            "id" => ip2long($ipAddr),
            "ip_addr" => ip2long($ipAddr),
            "last_seen" => date('Y-m-d H:i:s')
        );

        $snmpComm = $this->settings['global.snmp_community'];
        $snmpTimeout = $this->settings['global.snmp_timeout'];
        $snmpRetry = $this->settings['global.snmp_retry'];

        $snmpDevice = new CiscoSNMPDevice(
            $ipAddr,
            $snmpComm,
            CiscoSNMPDevice::$defaultAttrDefs, //Override once we have the sys obj id
            $snmpTimeout,
            $snmpRetry
        );

        //Ping device to verify its responding to SNMP queries
        //with the supplied SNMP community
        if(!$snmpDevice->ping()){
            $this->log("$ipAddr is not responding to the supplied SNMP community", 'error', 'discovery');
            return false;
        }

        //Get sysObjectID
        $sysObjectId = $snmpDevice->getSysObjectId();
        if($sysObjectId === false){
            $this->log("$ipAddr did not return a sysObjectID", 'error', 'discovery');
            return false;
        }
        $device['sys_object_id'] = $sysObjectId;

        //Override attribute defs
        $attrDefs = $this->_getSysObjectIdAttrDefs($sysObjectId);
        $snmpDevice->loadAttrDefs($attrDefs);

        //Get attributes
        $attrs = $this->_getDeviceAttributes($ipAddr, $snmpDevice, $sysObjectId);
        $device = array_merge($device, $attrs);

        //Add or update device record
        $this->_addOrUpdateDeviceRecord($ipAddr, $device);

        //Get neighbor info
        $neighbors = $this->_getDeviceNeighbors($ipAddr, $snmpDevice, $sysObjectId);
        $this->_addOrUpdateNeighborRecords($ipAddr, $neighbors);

        //Add neighbors to the queue for processing
        foreach($neighbors as $neighbor){
            $this->queue[] = $neighbor['ip_addr'];
        }
    }

    private function _getSysObjectIdAttrDefs($sysObjectId){

        $attrSnmpOids = (isset($this->attrOids[$sysObjectId])) ? $this->attrOids[$sysObjectId] : array();

        //Put them into a format the SimpleSNMP library will accept
        $attrDefs = array();
        foreach($attrSnmpOids as $attrOid){
            $name = $attrOid['name'];
            $oid = $attrOid['oid'];
            $filter = $attrOid['filter'];

            $attrDefs[$name] = array(
                'oid' => $oid,
                'filter' => $filter
            ); 
        }

        return $attrDefs;
    }

    private function _getDeviceAttributes($ipAddr, $snmpDevice){

        $attributes = array();

        foreach(self::$discoveryAttrs as $attr){
            
            $value = @$snmpDevice->getAttribute($attr);
            if($value === false) {
                $this->log("Failed to get attribute $attr for device ${ipAddr}", 'warning', 'discovery');
            }

            $attributes[$attr] = $value; 
        }

        return $attributes;
    }

    private function _addOrUpdateDeviceRecord($ipAddr, $device){

         //Add or update device record
        $existingDevice = $this->Device->findByIpAddr($device['ip_addr']);
        if(empty($existingDevice)){
            $this->Device->create();
        }
        else {
            $this->Device->id = $existingDevice['Device']['id'];
        }

        if(!$this->Device->save($device)) {
            $valError = print_r($this->Device->validationErrors, true);
            $this->log("Failed to save device record for $ipAddr: ${valError}", 'discovery');
        }

        return $this->Device->id;
    }

    private function _getDeviceNeighbors($ipAddr, $snmpDevice, $sysObjectId){

        $neighbors = $snmpDevice->getNeighbors();
        return $neighbors;
    }

    private function _addOrUpdateNeighborRecords($deviceIpAddr, $neighbors) {

        //Retrieve existing device neighbors and index by ip addr 
        $existingNeighbors = $this->DeviceNeighbor->find('all', array(
            'contain' => 'Device',
            'fields' => array(
                'DeviceNeighbor.*'
            ),
            'conditions' => array(
                'Device.ip_addr' => ip2long($deviceIpAddr)
            )
        ));

        //Index exsting neighbors by a hash of the
        //neighbor ip, neighbor port, local port
        $idxExistingNeighbors = array();
        foreach($existingNeighbors as $neighbor){
            $neighbor = $neighbor['DeviceNeighbor'];
            $neighborHash = $this->_neighborHash($neighbor);
            $idxExistingNeighbors[$neighborHash] = $neighbor['id'];
        }

        //Iterator over new neighbors and create or update as needed
        foreach($neighbors as $neighbor){

            //Convert SimpleSNMP neighbor to cake format
            $neighbor = $this->_simpleSnmpNeighborToCakeFormat(
                $deviceIpAddr,
                $neighbor
            );

            //Determine if we already have a record of this neighbor
            $neighborHash = $this->_neighborHash($neighbor);
            if(isset($idxExistingNeighbors[$neighborHash])){
                $this->DeviceNeighbor->id = $idxExistingNeighbors[$neighborHash];
            }
            else {
                $this->DeviceNeighbor->create();
            }
              
            $saveResult = $this->DeviceNeighbor->save(array(
                'DeviceNeighbor' => $neighbor
            ));

            if(!$saveResult){
                $valErr = print_r($this->DeviceNeighbor->validationErrors, true);
                $this->log("Failed to save neighbor record: ${valError}", 'discovery');
            }
        }
    }

    private function _simpleSnmpNeighborToCakeFormat($deviceIpAddr, $neighbor){

        return array(
            'device_ip_addr' => ip2long($deviceIpAddr),
            'neighbor_ip_addr' => ip2long($neighbor['ip_addr']),
            'neighbor_name' => $neighbor['name'],
            'neighbor_platform' => $neighbor['platform'],
            'neighbor_port' => $neighbor['neighbor_port'],
            'local_port' => $neighbor['local_port'],
            'last_seen' => $neighbor['discovery_timestamp']
        );
    }

    private function _neighborHash($neighbor){

        return md5(implode('|', array(
            $neighbor['neighbor_ip_addr'],
            $neighbor['neighbor_port'],
            $neighbor['local_port']
        )));
    }
}
