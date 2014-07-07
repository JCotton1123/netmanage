<?php

class DashboardController extends AppController {
 
    public $uses = array();
 
    public function index() {

    }

    public function search() {

        $this->loadModel('Device');
        
        if($this->request->is('get'))
            return $this->render('search.dialog');

        $searchTerm = '%' . $this->request->data('search') . '%';

        $devices = $this->Device->find('all', array(
            'contain' => array(),
            'conditions' => array(
                'OR' => array(
                    'Device.name LIKE' => $searchTerm,
                    'Device.ip_addr LIKE' => $searchTerm,
                )
            )
        ));

        $this->set(array(
            'devices' => $devices
        ));

        $this->render('search.results');
    }
}
