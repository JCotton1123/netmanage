<?php

class DeviceLogsController extends AppController {

    public function isAuthorized($user){

        return true;
    }

    public function llist($deviceId=null) {

        $this->loadModel('Device');

        if(empty($deviceId)){
            
            $this->DataTables->setColumns(array(
                'Timestamp' => array(
                    'model' => 'DeviceLog',
                    'column' => 'timestamp'
                ),
                'Host' => array(
                    'model' => 'DeviceLog',
                    'column' => 'host'
                ),
                'Fac/Sev/Mnem' => array(
                    'model' => 'DeviceLog',
                    'column' => 'fac_sev_mnem'
                ),
                'Message' => array(
                    'model' => 'DeviceLog',
                    'column' => 'event_descr'
                )
            ));

            $this->DataTables->process(array(
                'contain' => array(),
                'field' => array(
                    'DeviceLog.*'
                )
            ));
        }
        else {

            if(!$this->Device->exists($deviceId))
                throw new NotFoundException('This device does not exist');

            $device = $this->Device->findById($deviceId);

            $this->DataTables->setColumns(array(
                'Timestamp' => array(
                    'model' => 'DeviceLog',
                    'column' => 'timestamp'
                ),
                'Fac/Sev/Mnem' => array(
                    'model' => 'DeviceLog',
                    'column' => 'fac_sev_mnem'
                ),
                'Message' => array(
                    'model' => 'DeviceLog',
                    'column' => 'message'
                )
            )); 

            $this->DataTables->process(array(
                'contain' => array(),
                'field' => array(
                    'DeviceLog.*'
                ),
                'conditions' => array(
                    'DeviceLog.device_ip_addr' => $device['Device']['ip_addr']
                )
            ));
        }
    }

    public function stream(){

        $limit = $this->request->query('length');
        if(empty($limit))
            $limit = 10;

        $logs = $this->DeviceLog->find('all', array(
            'contain' => array(
                'Device'
            ),
            'order' => array(
                'timestamp' => 'desc'
            ),
            'limit' => $limit
        ));

        $this->set(array(
            'logs' => $logs
        ));
    }
}
