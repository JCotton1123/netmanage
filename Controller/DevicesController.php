<?php
class DevicesController extends AppController {

    public function index() {

        $this->DataTables->setColumns(array(
            'Name' => array(
                'model' => 'Device',
                'column' => 'name'
            ),
            'Address' => array(
                'model' => 'Device',
                'column' => 'ip_addr'
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

            $this->DataTables->process(array(
                'contain' => array(),
                'field' => array(
                    'Device.*'
                )
            ));
        }
	}

    public function view($deviceId=null){

        if(!$this->Device->exists($deviceId))
            throw new NotFoundException('This device does not exist');

        $device = $this->Device->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Device.id' => $deviceId
            )
        ));

        $this->set(array(
            'device' => $device
        ));
    }

    public function delete($deviceId=null){

        if(!$this->Device->exists($deviceId))
            throw new NotFoundException('This device does not exist');

        $this->Device->delete($deviceId);

        $this->redirect('/devices');
    }

    public function neighbors($deviceId=null){

        $this->loadModel('DeviceNeighbor');

        $this->DataTables->setColumns(array(
            'Neighbor Name' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'name',
            ),
            'Neighbor Platform' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'platform',
            ),
            'Local Port' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'local_port'
            ),
            'Neighbor Port' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'neighbor_port'
            ),
            'First Seen' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'created'
            ),
            'Last Seen' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'last_seen'
            )
        ));
            
        if($this->request->isAjax()){

            $this->DataTables->process(
                array(
                    'contain' => array(),
                    'conditions' => array(
                        'DeviceNeighbor.device_id' => $deviceId
                    ),
                    'field' => array(
                        'DeviceNeighbor.*'
                    )
                ),
                $this->DeviceNeighbor
            );
        }
    }

    public function configs($deviceId=null){

        $this->loadModel('DeviceConfig');
        
        $this->DataTables->setColumns(array(
            'Timestamp' => array(
                'model' => 'DeviceConfig',
                'column' => 'created'
            ),
            'Diff' => array(
                'model' => 'DeviceConfig',
                'column' => 'diff'
            ),
        ));

        if($this->request->isAjax()){

            $this->DataTables->process(
                array(
                    'contain' => array(),
                    'conditions' => array(
                        'DeviceConfig.device_id' => $deviceId
                    ),
                    'field' => array(
                        'DeviceConfig.created',
                        'DeviceConfig.diff'
                    )
                ),
                $this->DeviceConfig
            );
        } 
    }

    public function config($configId=null){

        if(!$this->Device->DeviceConfig->exists($configId))
            throw new NotFoundException('This configuration does not exist');

        $config = $this->Device->DeviceConfig->find('first', array(
            'contain' => array(
                'Device'
            ),
            'conditions' => array(
                'DeviceConfig.id' => $configId
            )
        ));

        $this->set(array(
            'config' => $config
        ));
    }
}
