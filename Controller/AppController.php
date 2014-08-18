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
        'DataTables.DataTables'
    );

    public function beforeFilter() {

        # Set auth parameters
        $this->Auth->authorize = array('Controller');
        $this->Auth->authenticate = array(
            'Form'
        );
    }

    public function beforeRender(){

        //Pass user variables to the view
        $user = $this->Auth->User();
        $this->set('isLoggedIn', $user != false);
        $this->set('username', $this->Auth->User('username'));

        //Pass isDebug to the view
        $this->set('isDebug', Configure::read('debug') > 0);

        //Determine which nav item should be active & pass to the view
        $controller = strtolower($this->params['controller']);
        $this->set('controller', $controller);
        $activeNav = 'home';
        if(in_array($controller, array('clients')))
            $activeNav = 'clients';
        if(in_array($controller, array('devices','device_configs','device_attr_oids','device_config_mgmt','device_software_mgmt')))
            $activeNav = 'devices';
        elseif(in_array($controller, array('reports')))
            $activeNav = 'reports';
        elseif(in_array($controller, array('settings')))
            $activeNav = 'settings';
        $this->set('activeNav', $activeNav);
    }

    public function isAuthorized($user) {

        return true;
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
