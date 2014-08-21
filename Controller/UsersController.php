<?php

class UsersController extends AppController {

    public function isAuthorized($user){

        if($this->action == 'logout')
            return true;

        $userRole = $user['Role']['name'];
        if($userRole == 'administrator')
            return true;
        elseif($userRole == 'user'){
            if($this->action == 'reset_password'){
                $suppliedUserId = $this->passedArgs[0];
                return $this->Auth->User('id') === $suppliedUserId;
            }
            return in_array(
                $this->action,
                array(
                    'index',
                    'view'
                )
            );
        }

        return false;
    }

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow(array(
            'login',
        ));
    }

    public function login() {

        $this->layout = 'login';

        if($this->request->is('post')) {
            if($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            }
            else {
                $this->flash('Invalid username or password', 'error', false);
            }
        }
    }

    public function logout() {

        $this->Session->destroy();
    	$this->redirect($this->Auth->logout());
    }

    public function index() {

        $this->DataTables->setColumns(array(
            'Name' => array(
                'model' => 'User',
                'column' => 'name'
            ),
            'Username' => array(
                'model' => 'User',
                'column' => 'username'
            ),
            'Role' => array(
                'model' => 'Role',
                'column' => 'name'
            )
        ));

        if($this->request->isAjax()){

            $this->DataTables->process(array(
                'contain' => array(
                    'Role'
                )
            ));
        }
    }

    public function view($userId=null) {

        if(!$this->User->exists($userId))
            throw new NotFoundException('User does not exist');

        $user = $this->User->find('first', array(
            'contain' => array(
                'Role'
            ),
            'conditions' => array(
                'User.id' => $userId
            )
        ));

        $this->set(array(
            'user' => $user
        ));
    }

    public function reset_password($userId=null){

        if(!$this->User->exists($userId))
            throw new NotFoundException('User does not exist');

        $user = $this->User->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'User.id' => $userId
            )
        ));
        $this->set('user', $user);

        if($this->request->is('post')){

            $password = $this->request->data('password');
            $confirmPassword = $this->request->data('confirm_password');

            if($password != $confirmPassword) {
                $this->flash('The supplied passwords do not match. Please try again');
                return;
            }

            $this->User->id = $userId;
            $result = $this->User->save(array(
                'User' => array(
                    'password' => $password
                )
            ));

            if($result){
                $this->flash("Password updated", 'success');
                $this->redirect("/users/view/${userId}");
            }
            else {
                $valErrors = $this->User->validationErrorsAsString();
                $this->flash("${valErrors}");
            }
        }
    }

    public function add() {

        $roles = $this->User->Role->getFormFriendlyList();
        $this->set('roles', $roles);

        if($this->request->is('post')) {

            $password = $this->request->data('User.password');
            $confirmPassword = $this->request->data('User.confirm_password');

            if($password != $confirmPassword){
                $this->flash('The supplied passwords do not match.');
                return;
            }
            unset($this->request->data['User']['confirm_password']);

            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->flash('User created successfully', 'success');
                return $this->redirect("/users");
            }
            else {
                $valErrors = $this->User->validationErrorsAsString();
                $this->flash("${valErrors}"); 
            }
        }
    }

    public function edit($userId=null){

        if(!$this->User->exists($userId))
            throw new NotFoundException('User does not exist');

        $user = $this->User->findById($userId);
        $this->set('user', $user);
        $roles = $this->User->Role->getFormFriendlyList();
        $this->set('roles', $roles);

        if($this->request->is('post') || $this->request->is('put')){

            $this->User->id = $userId;
            $result = $this->User->save($this->request->data);
            if($result){
                $this->flash('User updated successfully', 'success');
                return $this->redirect("/users/view/${userId}");
            }
            else {
                $valErrors = $this->User->validationErrorsAsString();
                $this->flash("${valErrors}");
            }
        }
        else {
            $this->request->data = $user;
        }
    }

    public function delete($userId=null){

        if(!$this->User->exists($userId))
            throw new NotFoundException('User does not exist');

        if($this->Auth->User('id') == $userId){
            $this->flash("Sorry, you can't delete your own account.");
            return $this->redirect("/users/view/${userId}");
        }

        $result = $this->User->delete($userId);

        if($result){
            $this->flash('User deleted successfully', 'success');
            return $this->redirect('/users');
        }
        else {
            $this->flash('Error encountered while trying to delete this user');
            return $this->redirect("/users/view/${userId}");
        }
    }
}
