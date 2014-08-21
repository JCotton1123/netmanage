<?php

App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

    public $belongsTo = array(
        'Role'
    );

    public $virtualFields = array(
        'name' => 'CONCAT(first_name, " ", last_name)'
    );

    public $validate = array(
        'role_id' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Role is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Role cannot be empty'
            ),
            'isNumeric' => array(
                'rule' => 'numeric',
                'message' => 'Role must be an integer'
            ),
            'validForeignKey' => array(
                'rule' => array('isValidForeignKey'),
                'message' => 'Role does not exist'
            )
        ),
        'username' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Username is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Username cannot be empty'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This username is already in use'
            )
        ),
        'password' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Password is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Password cannot be empty'
            ),
            'minLength' => array(
                'rule' => array('minLength', 6),
                'message' => 'Password must be at least 6 characters long'
            )
        ),
        'first_name' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'First name is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty', 
                'message' => 'First name cannot be empty'
            )
        ),
        'last_name' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Last name is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty', 
                'message' => 'Last name cannot be empty'
            )
        ),
        'email' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Email is required'
            ),  
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Email cannot be empty'
            ),
            'validEmail' => array(
                'rule' => 'email',
                'message' => 'The supplied email address is not valid'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'The supplied email is already registered to another user'
            ),
        ),
    );

    public function beforeSave($options = array()){

        //Hash the password if its set
        if(isset($this->data[$this->alias]['password'])){
            $passwdHasher = new BlowfishPasswordHasher();
            $hashedPasswd = $passwdHasher->hash($this->data[$this->alias]['password']);
            $this->data[$this->alias]['password'] = $hashedPasswd;
        }

        return true;
    }
}
