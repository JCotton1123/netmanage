<?php

App::uses('SNMPDevice', 'SimpleSNMP');
App::uses('CiscoSNMPDevice', 'SimpleSNMP');

class ConfigBackupShell extends AppShell {

    public $uses = array(
        'Setting',
        'Device',
        'DeviceConfig'
    );

    public $tasks = array(
        'Settings'
    );

    public function main() {

        //Retrieve settings
        $this->settings = $this->Settings->get(array(
            'global',
            'tftp',
            'config_backup'
        ));

        $devices = $this->Device->find('all', array(
            'contain' => array()
        ));

        foreach($devices as $device){

            $deviceAddr = $device['Device']['ip_addr'];
            $deviceFriendlyAddr = $device['Device']['friendly_ip_addr'];

            //Check against blacklist
            $ipBlacklistFilter = $this->settings['config_backup.ip_blacklist'];
            if(!empty($ipBlacklistFilter)){
                if(preg_match($ipBlacklistFilter, $deviceFriendlyAddr)){
                    $this->log("Skipping device ${deviceFriendlyAddr} b/c its ip address is blacklisted", 'info', 'config_backup');
                    continue;
                }
            }

            //Get the running config from the device
            $newConf = $this->_getDeviceConfig($device);
            if($newConf === false){
                $this->log("Failed to get device configuration for $deviceFriendlyAddr", 'error', 'config_backup');
                continue;
            }

            //Get the last config stored in the DB
            $oldConf = $this->DeviceConfig->find('first', array(
                'contain' => array(),
                'conditions' => array(
                    'DeviceConfig.device_ip_addr' => $deviceAddr,
                ),
                'order' => array(
                    'DeviceConfig.created DESC'
                ),
                'limit' => 1
            ));
            if(empty($oldConf))
                $oldConf = "";
            else
                $oldConf = $oldConf['DeviceConfig']['config'];

            //Diff
            $diff = $this->_diffConfigs($oldConf, $newConf);

            if(!empty($diff)){

                $this->log("Configuration change detected for device $deviceFriendlyAddr", 'debug', 'config_backup');

                //Save
                $this->DeviceConfig->create();
                $result = $this->DeviceConfig->save(array(
                    'DeviceConfig' => array(
                        'device_ip_addr' => $deviceAddr,
                        'config' => $newConf,
                        'diff' => $diff
                    )
                ));
                if(!$result)
                    $this->log("Failed to save device configuration for $deviceFriendlyAddr", 'error', 'config_backup');
            }
            else
                $this->log("No configuration change detected for device $deviceFriendlyAddr", 'debug', 'config_backup');
        }
    }

    private function _getDeviceConfig($deviceRecord){

        $deviceId = $deviceRecord['Device']['id'];
        $deviceAddr = $deviceRecord['Device']['friendly_ip_addr'];

        $device = new CiscoSNMPDevice(
            $deviceAddr,
            $this->settings['global.snmp_community'],
            CiscoSNMPDevice::$defaultAttrDefs
        );

        $tftpRoot = $this->settings['tftp.root'];
        $tftpAddr = $this->settings['tftp.address'];

        $confFile = $this->_createTftpFile($tftpRoot, 'confbak.');
        if($confFile === false)
            throw new Exception('Failed to create TFTP file for config transaction.');

        $confStr = false;
        $result = $device->pushConfigToTftp('running', basename($confFile), $tftpAddr, '20000000');
        if($result){
            $confStr = file_get_contents($confFile);
        }

        unlink($confFile);

        return $confStr;
    }

    private function _createTftpFile($dir, $prefix){

        $filename = tempnam($dir, $prefix);
        if($filename === false)
            return false;

        chmod($filename, 0777);

        return $filename;
    }

    private function _diffConfigs($oldConfig, $newConfig){

        //Strip out any lines that should be ignored for the diff
        if(isset($this->settings['config_backup.diff_ignore_lines'])){
            $ignoreLines = $this->settings['config_backup.diff_ignore_lines'];
            if(!empty($ignoreLines)){
                $ignoreLines = json_decode($ignoreLines);
                $oldConfig = preg_replace($ignoreLines, "!\n", $oldConfig);
                $newConfig = preg_replace($ignoreLines, "!\n", $newConfig);
            }
        }

        return xdiff_string_diff($oldConfig, $newConfig);
    }
}
