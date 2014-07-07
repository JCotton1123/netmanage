<?php
class DeviceSoftwareUpgrade extends AppModel {

   public $name = 'DeviceSoftwareUpgrade';
   public $useTable = 'device_software_upgrades';

   public $belongsTo = array(
	'Device'
   );

   public $validate = array(
	'device_id' => array(
		'rule' => 'notEmpty',
		'required' => true,
	),
	'image_file' => array(
		'rule' => 'notEmpty',
		'required' => true
	),
	'start_time' => array(
		'rule' => 'notEmpty',
		'required' => true
	)
   );

}
