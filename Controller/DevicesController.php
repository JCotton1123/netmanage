<?php

class DevicesController extends AppController {

    public function isAuthorized($user){

        $userRole = $user['Role']['name'];
        if($userRole == 'administrator'){
            return true;
        }
        elseif($userRole == 'user'){
            return in_array(
                $this->action,
                array(
                    'index',
                    'view',
                    'neighbors',
                    'configs',
                    'config'
                )
            );
        }

        return false;
    }

    public function index() {

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
                'column' => 'neighbor_name',
            ),
            'Local Port' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'local_port'
            ),
            'Neighbor Port' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'neighbor_port'
            ),
            'Last Seen' => array(
                'model' => 'DeviceNeighbor',
                'column' => 'last_seen'
            )
        ));
            
        if($this->request->isAjax()){

            $this->DataTables->process(
                array(
                    'contain' => array(
			            'Device'
		            ),
                    'conditions' => array(
                        'Device.id' => $deviceId,
                    ),
                    'fields' => array(
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
                    'contain' => array(
                        'Device'
                    ),
                    'conditions' => array(
                        'Device.id' => $deviceId
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
