<?php

class NetmanageLogsController extends AppController {

    public function isAuthorized($user){

        return true;
    }

    public function llist() {

        $this->DataTables->setColumns(array(
            'Timestamp' => array(
                'model' => 'NetmanageLog',
                'column' => 'timestamp'
            ),
            'Type' => array(
                'model' => 'NetmanageLog',
                'column' => 'type'
            ),
            'Message' => array(
                'model' => 'NetmanageLog',
                'column' => 'message'
            )
        ));

        $this->DataTables->process(array(
            'contain' => array(),
        ));
    }

    public function stream(){

        $limit = $this->request->query('length');
        if(empty($limit))
            $limit = 10;

        $logs = $this->NetmanageLog->find('all', array(
            'contain' => array(),
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
