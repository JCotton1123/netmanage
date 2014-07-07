<?php

class DeviceLogsController extends AppController {

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
                    'column' => 'event_descr'
                )
            )); 

            $this->DataTables->process(array(
                'contain' => array(),
                'field' => array(
                    'DeviceLog.*'
                ),
                'conditions' => array(
                    'DeviceLog.host' => $device['Device']['ip_addr']
                )
            ));
        }
    }

    public function stream($deviceId=null){

        $lastRequestTimestamp = $this->request->query('timestamp');
        if(empty($lastRequestTimestamp))
            $lastRequestTimestamp = time() - 3;

        $conditions = array(
            'timestamp >' => date('Y-m-d H:i:s', $lastRequestTimestamp)
        );

        if(!empty($deviceId)){

            if(!$this->Device->exist($deviceId))
                throw new NotFoundException('This device does not exist');

            $device = $this->Device->findById($deviceId);

            $conditions['host'] = $device['Device']['ip_addr'];
        }

        $logs = $this->DeviceLog->find('all', array(
            'contain' => array(),
            'conditions' => $conditions,
            'order' => array(
                'timestamp' => 'desc'
            ),
            'limit' => 10
        ));
        

        $this->set(array(
            'logs' => $logs
        ));
    }
}
