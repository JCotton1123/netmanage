<?php
App::uses('Controller', 'Controller');

class AppController extends Controller {

    public $components = array(
        'Auth' => array(
            'unauthorizedRedirect' => false
        ),
        'Session',
        'RequestHandler',
        'DataTables.DataTables',
        'DebugKit.Toolbar',
    );

    public $helpers = array(
        'Form',
        'Time',
        'DataTables.DataTables',
        'ActionMenu'
    );

    public function beforeFilter() {

        # Set auth parameters
        $this->Auth->authorize = array('Controller');
        $this->Auth->authenticate = array(
            'Form' => array(
                'passwordHasher' => 'Blowfish'
            )
        );
    }

    public function beforeRender(){

        //Pass some variables to the view
        $this->set('__isLoggedIn', $this->Auth->loggedIn());
        $this->set('__userRole', $this->Auth->User('Role.name'));
        $this->set('__isDebug', Configure::read('debug') > 0);

        $controller = strtolower($this->params['controller']);
        $this->set('__controller', $controller);
    }

    public function isAuthorized($user) {

        if($user['Role']['name'] == 'administrator')
            return true;

        return false;
    }

    public function flash($message, $type="error", $dismissable=true){

        if(!in_array($type, array('error', 'warning', 'success')))
            throw new \Exception('Invalid type supplied');

        $this->Session->setFlash(
            __($message),
            'Alerts/default',
            array(
                'type' => $type,
                'dismissable' => $dismissable
            ),
            $type
        );
    }
}
