<?php
/**

This is a modified version of 
https://raw.githubusercontent.com/webtechnick/CakePHP-DatabaseLogger-Plugin/master/Lib/Log/Engine/DatabaseLog.php

# Setup

in app/config/bootstrap.php add the following

CakeLog::config('database', array(
    'engine' => 'DatabaseLog.DatabaseLog',
    'model' => 'CustomLogModel'
));

*/

App::uses('ClassRegistry', 'Utility');
App::uses('CakeLogInterface','Log');

class DatabaseLog implements CakeLogInterface{

    /**
    * Model name placeholder
    */
    public $model = null;

    /**
    * Model object placeholder
    */
    public $Log = null;

    /**
    * Contruct the model class
    */
    public function __construct($options = array()) {
    
        if(!isset($options['model']))
            throw new \InvalidArgumentException('You must define a model');
        $this->model = $options['model'];
        $this->Log = ClassRegistry::init($this->model);
    }

    /**
    * Write the log to database
    *
    * @return boolean Success
    */
    public function write($type, $message) {
        $this->Log->create();
        return (bool)$this->Log->save(array(
            'type' => $type,
            'message' => $message,
        ));
    }
}
