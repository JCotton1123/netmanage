<?php
class User extends AppModel {

   public $name = 'User';

   public $hasAndBelongsToMany = array(
	'Role' =>
		array(
			'className' => 'Role',
			'joinTable' => 'user_roles',
			'foreignKey' => 'user_id',
			'associationForiegnKey' => 'role_id'
		)
   );

   public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        )
   );

  public function beforeSave() {

    	if (isset($this->data[$this->alias]['password'])) {
        	$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    	}

    	return true;
  }

}
