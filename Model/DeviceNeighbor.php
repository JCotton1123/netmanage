<?php
class DeviceNeighbor extends AppModel {

    public $useTable = "device_neighbors";

    public $belongsTo = array(
        'Device' => array(
            'foreignKey' => 'device_ip_addr',
        )
    ); 

    public $virtualFields = array(
   	    'friendly_neighbor_ip_addr' => "INET_NTOA(DeviceNeighbor.neighbor_ip_addr)"
    );

}
