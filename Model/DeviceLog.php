<?php
class DeviceLog extends AppModel {

    public $useTable = 'device_logs';

    public $belongsTo = array(
        'Device' => array(
            'foreignKey' => 'device_ip_addr',
        )
    );

    public $virtualFields = array(
        'friendly_ip_addr' => "INET_NTOA(DeviceLog.device_ip_addr)"
    );

    public $validate = array(
        'timestamp' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Timestamp is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Timestamp cannot be empty'
            ),
            'isDate' => array(
                'rule' => 'datetime',
                'message' => 'Timestamp must be a valid datetime'
            ),
        ),
        'device_ip_addr' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Device IP address is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Device IP address cannot be empty'
            ),
            'isNumeric' => array(
                'rule' => 'numeric',
                'message' => 'Device IP address should be an ip address in long format'
            )
        ),
        'message' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Host is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Host cannot be empty'
            ),
        )
    );
}
