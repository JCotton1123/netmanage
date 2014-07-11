<?php

App::uses('SNMPDevice', 'SNMPDevice');

class DeviceDiscoveryShell extends AppShell {

    public $uses = array(
        'Setting',
        'DeviceAttrOid',
        'Device',
        'DeviceNeighbor'
    );

    const DEFAULT_SYSOBJECTID = '1.3.6.1.4.1.9';

    public function run() {

        //Retrieve settings
        $this->settings = $this->_getSettings();

        //Retrieve device SNMP attr oids
        $this->attrOids = $this->DeviceAttrOid->groupedBySysObjectId();

        $seeds = explode(',', $this->settings['discovery.seeds']);
        $seeds = array_map('trim', $seeds);

        $discoveredDevices = array();

        while(count($seeds) > 0) {

            $deviceIpAddr = array_pop($seeds);

            //Check against blacklist
            $ipBlacklistFilter = $this->settings['discovery.ip_blacklist'];
            if(!empty($ipBlacklistFilter)){
                if(preg_match($ipBlacklistFilter, $deviceIpAddr)){
                    $this->log("$deviceIpAddr|Skipping device b/c its ip address is blacklisted");
                    continue;
                }
            }

            //Check against whitelist
            $ipWhitelistFilter = $this->settings['discovery.ip_whitelist'];
            if(!empty($ipBlacklistFilter)){
                if(!preg_match($ipWhitelistFilter, $deviceIpAddr)){
                    $this->log("$deviceIpAddr|Skipping device b/c its ip address does not match the whitelist"); 
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

    private function _getSettings(){

        $settings = $this->Setting->find('all',array(
            'contain' => array(),
            'conditions' => array(
                'OR' => array(
                    array(
                        'var LIKE' => 'global%'
                    ),
                    array(
                        'var LIKE' => 'discovery%'
                    )
                )
            )
        ));

        if($settings === false)
            throw new \Exception('Unable to retrieve settings');

        return Hash::combine($settings, '{n}.Setting.var', '{n}.Setting.val');
    }

    private function _discoverDevice($ipAddr){

        $device = array(
            "mgmt_ip_addr" => ip2long($ipAddr)
        );

        $snmpComm = $this->settings['global.snmp_community_r'];
        $snmpTimeout = $this->settings['global.snmp_timeout'];
        $snmpRetry = $this->settings['global.snmp_retry'];

        $snmpDevice = new SNMPDevice(
            $ipAddr,
            $snmpComm,
            false, //Don't need a r/w community for discovery
            $snmpTimeout,
            $snmpRetry
        );

        //Ping device to verify its responding to SNMP queries
        //with the supplied SNMP community
        if(!$snmpDevice->ping()){
            $this->log("$ipAddr|SNMP ping failed", 'error');
            return false;
        }

        //Get sysObjectID
        $sysObjectId = $snmpDevice->getSysObjectId();
        if($sysObjectId === false){
            $this->log("$ipAddr|Failed to get device sysObjectID", 'error');
            return false;
        }
        $device['sys_object_id'] = $sysObjectId;

        //Get attributes
        $attrs = $this->_getDeviceAttributes($ipAddr, $snmpDevice, $sysObjectId);
        $device = array_merge($device, $attrs);

        //Add or update device record
        $deviceId = $this->_addOrUpdateDeviceRecord($ipAddr, $device);

        //Get neighbor info
        $neighbors = $this->_getDeviceNeighbors($ipAddr, $snmpDevice, $sysObjectId);

    }

    private function _getDeviceAttributes($ipAddr, $snmpDevice, $sysObjectId){

        $attributes = array();

        $attrSnmpOids = array_merge(
            $this->attrOids[self::DEFAULT_SYSOBJECTID],
            (isset($this->attrOids[$sysObjectId])) ? $this->attrOids[$sysObjectId] : array()
        );
        foreach($attrSnmpOids as $attrOid){
            $name = $attrOid['name'];
            $oid = $attrOid['oid'];
            $parseFilter = $attrOid['parse'];
            $value = null;

            //Get attribute
            if(!@$snmpDevice->getAttribute($oid, $value)){
                $this->log("$ipAddr|Failed to get device attribute $name", 'warning');
            }

            //Apply parser
            if(!empty($parseFilter)){
                //$this->log("$ipAddr|Applying parse filter $parseFilter to attribute $name with value $value", 'debug');
                if(preg_match($parseFilter, $value, $matches)){
                    $value = $matches[1];
                }
            }

            $attributes["$name"] = $value;
        }

        return $attributes;
    }

    private function _addOrUpdateDeviceRecord($ipAddr, $device){

         //Add or update device record
        $existingDevice = $this->Device->findByMgmtIpAddr($device['mgmt_ip_addr']);
        if(empty($existingDevice)){
            $this->log("$ipAddr|Creating a new device:" . json_encode($device), 'info');
            $this->Device->create();
            if(!$this->Device->save($device)) {
                $this->log("$ipAddr|Failed to save device record");
                return false;
            }

            return $this->Device->id;
        }
        else {
            //Update the record if there are any differences
            $diff1 = array_diff($device, $existingDevice['Device']);
            if(!empty($diff1)){
                $this->log("$ipAddr|Applying device updates:" . json_encode($diff1), 'info');
                $this->Device->id = $existingDevice['Device']['id'];
                if(!$this->Device->save($device))
                    $this->log("$ipAddr|Failed to update device record");
            }

            return $existingDevice['Device']['id'];
        }
    }

    private function _getDeviceNeighbors($ipAddr, $snmpDevice, $sysObjectId){

        return array();
    }
}
