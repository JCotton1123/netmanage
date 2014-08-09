<?php

class UsersController extends AppController {

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow(array(
            'login',
            //'add'
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

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        }
    } 
}
