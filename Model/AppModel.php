<?php

App::uses('Model', 'Model');

class AppModel extends Model {

    public $actsAs = array(
        'Containable'
    );

    /**
     * Validation errors as a "pretty" string
     */
    public function validationErrorsAsString($saveMany=false){

        if($saveMany){
            return print_r($this->validationErrors,true);
        }
        else {
            $message = "";
            foreach($this->validationErrors as $field => $fieldErrorMessages){
                $message .= implode(". ", $fieldErrorMessages) . ". ";
            }
            return $message;
        }
    }

    /**
     * Validate relationship (foreign_key) between this model and belongsTo model
     */
    public function isValidForeignKey($data) {
        foreach ($data as $key => $value) {
            foreach ($this->belongsTo as $alias => $assoc) {
                if ($assoc['foreignKey'] == $key) {
                    $this->{$alias}->id = $value;
                    return $this->{$alias}->exists();
                }
            }
        }
        return false;
    }

    /**
     * Return the last SQL statement executed
     */
    public function getSQLLog(){

        return $this->getDataSource()->getLog(false,false);
    }
}
