<?php

class DeviceConfigsController extends AppController {

    public function view($configId=null){

        if(!$this->DeviceConfig->exists($configId))
            throw new NotFoundException('This configuration does not exist');

        $config = $this->DeviceConfig->find('first', array(
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
