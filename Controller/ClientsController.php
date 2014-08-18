<?php

class ClientsController extends AppController {

    public $uses = array(
        'ClientPort'
    );

    public function isAuthorized($user){

        return true;
    }

    public function index() {

        $this->DataTables->setColumns(array(
            'Client' => array(
                'model' => 'ClientPort',
                'column' => 'friendly_mac_addr'
            ),
            'Device' => array(
                'model' => 'Device',
                'column' => 'name'
            ),
            'Port' => array(
                'model' => 'ClientPort',
                'column' => 'port'
            ),
            'Vlan' => array(
                'model' => 'ClientPort',
                'column' => 'vlan'
            ),
            'Up/Down' => array(
                'model' => 'ClientPort',
                'column' => 'friendly_up_down'
            )
        ));

        if($this->request->isAjax()){

            $this->DataTables->process(
                array(
                    'contain' => array(
                        'Device'
                    ),
                ),
                $this->ClientPort
            );
        }
	}
}
