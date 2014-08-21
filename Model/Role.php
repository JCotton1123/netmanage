<?php

class Role extends AppModel {

    public function getFormFriendlyList(){
        
        $roles = $this->find('all', array(
            'contain' => array(),
        ));
        
        $rolesList = array();
        foreach($roles as $role){
            $id = $role['Role']['id'];
            $name = $role['Role']['name'];
            $descr = $role['Role']['description'];
            $rolesList[$id] = "${name} (${descr})";
        }

        return $rolesList;
    }
}
