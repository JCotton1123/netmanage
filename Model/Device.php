<?php
class Device extends AppModel {

   public $virtualFields = array(
   	'ip_addr' => "INET_NTOA(Device.mgmt_ip_addr)"
   );

    public $hasMany = array(
        'DeviceConfig',
        'DeviceNeighbor',
    );
}
