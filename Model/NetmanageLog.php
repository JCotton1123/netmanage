<?php

class NetmanageLog extends AppModel {

    public $useTable = 'netmanage_logs';

    public function beforeSave($options = array()){

        $this->data[$this->alias]['timestamp'] = date('Y-m-d H:i:s');
        return true;
    }
}
