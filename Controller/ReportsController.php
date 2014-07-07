<?php
class ReportsController extends AppController {

    public $uses = array();

    public function index(){}

    public function device_inventory(){

        $this->layout = false;
        $this->RequestHandler->respondAs('text/csv');

        $this->loadModel('Device');

        $devices = $this->Device->find('all', array(
            'contain' => array(),
            'fields' => array(
                'name',
                'ip_addr',
                'model',
                'firmware',
                'serial'
            ),
            'order' => array(
                'name' => 'asc'
            )
        ));

        $this->set('devices',$devices);
    }

    public function nagios_export(){

    }

    private function _nagios_export_format_host($name, $ipAddr, $nagiosParams=array(), $parents=array()){

        $template = isset($nagiosParams['template']) ? $nagiosParmas['template'] : 'generic-host';

        $device_nagios_block = "define host {\n";
        $device_nagios_block .= "  use " . $nagios_params['template'] . "\n";
        $device_nagios_block .= "  host_name " . $name . "\n";
        $device_nagios_block .= "  alias " . $name . "\n";
        $device_nagios_block .= "  address " . $ipAddr . "\n";
        $device_nagios_block .= "  parents " . implode(' ', $parents) . "\n";
        $device_nagios_block .= "}\n";

        return $device_nagios_block;
    }

    public function network_graph(){

        $this->loadModel('Device');

        $devices = $this->Device->find('all', array(
            'contain' => array(
                'DeviceNeighbor'
            ),
            'order' => array(
                'mgmt_ip_addr' => 'ASC'
            ),
        ));

        $this->set('devices', $devices);
    }
}
