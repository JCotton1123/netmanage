<?php

class DashboardController extends AppController {
 
    public $uses = array();

    public function isAuthorized($user){

        return true;
    }

    public function index() {

        $this->loadModel('Device');

        //Get device category counts
        $totalDeviceCount = $this->Device->find('count');

        //Get model breakdown
        $modelCounts = $this->_getModelCounts();

        $this->set(array(
            'totalDeviceCount' => $totalDeviceCount,
            'modelCounts' => $modelCounts
        ));
    }

    private function _getDeviceCounts(){

        $totalDeviceCount = $this->Device->find('count');

        $switchDeviceCount = $this->Device->find('count', array(
            'contain' => array(),
            'conditions' => array(
                'Device.model LIKE' => 'WS%'
            )
        ));

        $routerDeviceCount = $this->Device->find('count', array(
            'contain' => array(),
            'conditions' => array(
                'Device.model LIKE' => 'ASR'
            )
        ));

        $accessPointCount = $this->Device->find('count', array(
            'contain' => array(),
            'conditions' => array(
                'Device.model LIKE' => 'WS-%'
            )
        ));

        $smallBusinessCount = $this->Device->find('count', array(
            'contain' => array(),
            'conditions' => array(
                'Device.model LIKE' => 'SR%',
            )
        ));

    }

    private function _getModelCounts(){

        $modelCounts = $this->Device->find('all', array(
            'contain' => array(),
            'fields' => array(
                'model',
                'count(model) as model_count'
            ),
            'group' => 'model'
        ));

        //Hash::combine($modelCounts, '{n}.Device.model', '{n}.{n}.model_count'); 
    }
}
