<?php
App::uses('Controller', 'Controller');

class AppController extends Controller {

    public $components = array(
        'Auth',
        'Session',
        'RequestHandler',
        'DataTables.DataTables',
        'DebugKit.Toolbar',
    );

    public $helpers = array(
        'Form',
        'Time',
        'DataTables.DataTables'
    );

    public function beforeFilter() {

        # Set auth parameters
        $this->Auth->authorize = array('Controller');
        $this->Auth->authenticate = array(
            'Form',
            'all' => array(
                //'scope' => array('User.is_active' => 1)
            ),
        );
    }

    public function isAuthorized($user) {

        return true;    
    }

    public function flash($message, $type="error"){

        if(!in_array($type, array('error', 'warning', 'success')))
            throw new \Exception('Invalid type supplied');

        $this->Session->setFlash(__($message), 'Alerts/default', array('type' => $type), $type);
    }
}
