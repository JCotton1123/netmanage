<?php
class Role extends AppModel {
   public $name = 'Role';

   public $hasAndBelongsToMany = array(
	'User' =>
		array(
			'className' => 'User',
			'joinTable' => 'user_roles',
			'foreignKey' => 'role_id',
			'associationForiegnKey' => 'user_id'
		)
    );

}
