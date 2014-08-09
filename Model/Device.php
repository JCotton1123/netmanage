<?php
class Device extends AppModel {

    public $hasMany = array(
        'DeviceNeighbor' => array(
            'foreignKey' => 'device_ip_addr',
            'dependent' => true
        ),
        'DeviceConfig' => array(
            'foreignKey' => 'device_ip_addr',
            'dependent' => true
        ),
        'DeviceLog' => array(
            'foreignKey' => 'device_ip_addr',
            'dependent' => true
        )
    ); 

    public $virtualFields = array(
   	    'friendly_ip_addr' => "INET_NTOA(Device.ip_addr)"
    );
}
