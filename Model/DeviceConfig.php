<?php
class DeviceConfig extends AppModel {

    public $useTable = "device_configs";

    public $belongsTo = array(
        'Device' => array(
            'foreignKey' => 'device_ip_addr',
        )
    );
}
