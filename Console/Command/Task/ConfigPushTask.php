<?php

App::uses('SNMPDevice', 'SimpleSNMP');
App::uses('CiscoSNMPDevice', 'SimpleSNMP');

class ConfigPushTask extends Shell {

    public $uses = array(
        'Setting',
        'Device',
    );

    public function push($configPushId){

        $this->settings = $this->Setting->get(array(
            'global',
            'tftp'
        ));

        $deviceAddr = false;
        $snmpComm = false;

        //Put config to a file within the tftp root
        $tftpServerAddr = false;
        $tftpRoot = false; 
        $configFile = false;

        $device = new CiscoSNMPDevice($deviceAddr, $snmpComm, CiscoSNMPDevice::$defaultAttrDefs);
        $device->pullConfigFromTftp('running', $configFile, $tftpServerAddr);

    }

    public function saveRunToStartup($configPushId){

        $device = new CiscoSNMPDevice($deviceAddr, $snmpComm, CiscoSNMPDevice::$defaultAttrDefs);
        $device->copyRunningConfigToStartup();
    }
}
