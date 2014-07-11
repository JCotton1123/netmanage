<?php

App::uses('Model', 'Model');

class AppModel extends Model {

    public $actsAs = array(
        'Containable'
    );

    /**
     * Return the last SQL statement executed
     */
    public function getSQLLog(){

        return $this->getDataSource()->getLog(false,false);
    }
}
