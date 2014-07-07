<?php
class DevicePushConfig extends AppModel {

   public $name = 'DevicePushConfig';
   public $useTable = 'device_push_config';

   public $belongsTo = array(
	'Device'
   );

   public $validate = array(
	'device_id' => array(
		'rule' => 'notEmpty',
		'required' => true,
	),
	'config' => array(
		'rule' => 'notEmpty',
		'required' => true
	),
	'start_time' => array(
		'rule' => 'notEmpty',
		'required' => true
	)
   );

}
