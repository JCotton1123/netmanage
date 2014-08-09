<?php

class DeviceConfigMgmtController extends AppController {

    public $uses = array();

    public function index(){
    
        $this->loadModel('Device');
        
        $this->DataTables->setColumns(array(
            'Name' => array(
                'model' => 'Device',
                'column' => 'name'
            ),
            'Address' => array(
                'model' => 'Device',
                'column' => 'friendly_ip_addr'
            ),
            'Model' => array(
                'model' => 'Device',
                'column' => 'model'
            ),
            'Serial' => array(
                'model' => 'Device',
                'column' => 'serial'
            )
        ));

        if($this->request->isAjax()){

            $this->DataTables->process(
                array(
                    'contain' => array(),
                    'field' => array(
                        'Device.*'
                    )
                ),
                $this->Device
            );
        }
    }
}
