<?php
class ReportsController extends AppController {

    public $uses = array();

    public function isAuthorized($user){

        return true;
    }

    public function index(){}

    public function device_inventory(){

        $this->loadModel('Device');

        $this->layout = false;
        $this->RequestHandler->respondAs('text/csv');

        $devices = $this->Device->find('all', array(
            'contain' => array(),
            'fields' => array(
                'name',
                'friendly_ip_addr',
                'model',
                'firmware',
                'serial'
            ),
            'order' => array(
                'ip_addr' => 'asc'
            )
        ));

        $this->set('devices',$devices);
    }

    public function nagios_export(){

        $this->loadModel('Device');

        $conditions = array();
        $deviceFilter = false;
        $hostTemplate = 'generic-host';
        $hostParents = false;
        $hostgroupName = 'netequip';
        $hostgroupAlias = 'Network Equipment';

        if($this->request->is('post')){

            //Device filter
            $deviceFilter = $this->request->data('device_filter');
            if(!empty($deviceFilter)){
                $conditions = array(
                    'OR' => array(
                        'Device.friendly_ip_addr LIKE' => "%${deviceFilter}%",
                        'Device.name LIKE' => "%${deviceFilter}%",
                    )
                );
            }

            //Host template
            $newHostTemplate = $this->request->data('host_template');
            if(!empty($newHostTemplate))
                $hostTemplate = $newHostTemplate;

            //Host parents
            $newHostParents = $this->request->data('parents');
            if(!empty($newHostParents))
                $hostParents = $newHostParents;

            //Hostgroup name
            $newHostgroupName = $this->request->data('hostgroup_name');
            if(!empty($newHostgroupName))
                $hostgroupName = $newHostgroupName;

            //Hostgroup alias
            $newHostgroupAlias = $this->request->data('hostgroup_alias');
            if(!empty($newHostgroupAlias))
                $hostgroupAlias = $newHostgroupAlias;
        }
       
        //Get devices w/ filter applied 
        $devices = $this->Device->find('all', array(
            'contain' => array(),
            'fields' => array(
                'name',
                'friendly_ip_addr'
            ),
            'conditions' => $conditions,
            'order' => 'ip_addr'
        ));

        //Build the host declarations
        $hosts = array();
        foreach($devices as $device){

            $name = $device['Device']['name'];
            $ipAddr = $device['Device']['friendly_ip_addr'];
            $hosts[] = <<<EOD
define host {
  use ${hostTemplate}
  host_name ${name}
  alias ${name}
  address ${ipAddr}
  parents ${hostParents}
}
EOD;

        }

        //Build the hostgroup declarations
        $hostsList = implode(",", Hash::extract($devices, '{n}.Device.name'));
        $hostgroup = <<<EOD
define hostgroup {
  hostgroup_name ${hostgroupName}
  alias ${hostgroupAlias}
  members ${hostsList}
}
EOD;

        $this->set(array(
            'hosts' => implode("\n\n", $hosts),
            'hostgroup' => $hostgroup,
            'deviceFilter' => $deviceFilter,
            'hostTemplate' => $hostTemplate,
            'hostParents' => $hostParents,
            'hostgroupName' => $hostgroupName,
            'hostgroupAlias' => $hostgroupAlias
        ));
    }

    public function network_graph(){

        $this->loadModel('Device');

        $devices = $this->Device->find('all', array(
            'contain' => array(
                'DeviceNeighbor'
            ),
            'order' => array(
                'ip_addr' => 'ASC'
            ),
        ));

        $this->set('devices', $devices);
    }
}
