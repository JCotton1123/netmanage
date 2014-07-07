<?php
class DeviceNeighbor extends AppModel {

   public $name = 'DeviceNeighbor';
   public $useTable = "device_neighbors";

   public $belongsTo = array('Device');

   public $virtualFields = array(
   	'ip_addr' => "INET_NTOA(DeviceNeighbor.mgmt_ip_addr)"
   );

}
