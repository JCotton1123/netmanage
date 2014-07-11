<?php
class DeviceLog extends AppModel {

    public $useTable = 'device_logs';

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
        'host' => array(
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
            'isNumeric' => array(
                'rule' => 'numeric',
                'message' => 'Host should be an ip address in long format'
            )
        ),
        'fac_sev_mnem' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Fac/Sev/Mnem is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Fac/Sev/Mnem cannot be empty'
            ),
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
