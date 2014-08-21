<?php

class Setting extends AppModel {

    /**
     * Retrieve a collection of settings based on the supplied filter
     */
    public function get($filter=false){

        $conditions = array();
        if(is_array($filter)){
            $conditions['OR'] = array();
            foreach($filter as $var){
                $conditions['OR'][] = array(
                    'var LIKE' => "${var}%"
                );
            }
        }
        elseif($filter !== false){
            $conditions = array(
                'var LIKE' => "${filter}%"
            );
        }

        $settings = $this->find('all',array(
            'contain' => array(),
            'conditions' => $conditions
        ));

        if($settings === false)
            throw new \Exception('Unable to retrieve settings');

        $keyValSettings = Hash::combine($settings, '{n}.Setting.var', '{n}.Setting.val');
        if(count($keyValSettings) == 1){
            return array_pop($keyValSettings);
        }

        return $keyValSettings;
    } 
}
