<?php

class ClientPort extends AppModel {

    public $belongsTo = array(
        'Device' => array(
            'foreignKey' => 'device_ip_addr'
        )
    );

    public $virtualFields = array(
        'friendly_mac_addr' => "LPAD(HEX(mac_addr), 12, 0)",
        'friendly_up_down' => "IF(up_down = 0, 'down', 'up')"
    );
}
