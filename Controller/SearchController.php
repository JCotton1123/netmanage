<?php

class SearchController extends AppController {
 
    public $uses = array(
        'Device',
        'ClientPort'
    );

    public function isAuthorized($user){

        return true;
    }
 
    public function index() {

        $searchTerm = false;
        $devices = array();
        $clients = array();

        if($this->request->is('post')){
       
            $searchTerm = $this->request->data('searchTerm');
            $searchTermWithWild = "%${searchTerm}%";

            $devices = $this->Device->find('all', array(
                'contain' => array(),
                'conditions' => array(
                    'OR' => array(
                        'Device.name LIKE' => $searchTermWithWild,
                        'INET_NTOA(Device.ip_addr) LIKE' => $searchTermWithWild
                    )
                )
            ));

            $clients = $this->ClientPort->find('all', array(
                'contain' => array(
                    'Device'
                ),
                'conditions' => array(
                    'ClientPort.friendly_mac_addr LIKE' => $searchTermWithWild
                )
            ));
        }

        $this->set(array(
            'searchTerm' => $searchTerm,
            'devices' => $devices,
            'clients' => $clients
        ));

        if($this->request->is('get')){

            if($this->request->is('ajax'))
                return $this->render('search.dialog');
            else
                return $this->render('search.results');
        }

        $this->render('search.results');
    }
}
