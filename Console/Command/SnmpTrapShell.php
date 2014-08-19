<?php

class SnmpTrapShell extends AppShell {

    public $uses = array(
        'ClientPort'
    );

    public function main() {

        $this->in('');
        $rawSrcDst = $this->in('');
        $rawSysUpTime = $this->in('');
        $rawOid = $this->in('');
        $rawValue = $this->in('');
        $this->in('');

        /*
        $this->log(
            print_r(compact(array(
                    'rawSrcDst',
                    'rawSysUpTime',
                    'rawOid',
                    'rawValue',
                )),
                true
            ),
            'debug',
            'snmp_traps'
        );
        */

        //Parse the src IP address
        $srcIpAddr = false;
        if(preg_match('/^UDP:\s\[(.*?)\]/', $rawSrcDst, $matches)){
            $srcIpAddr = $matches[1];
        }

        $this->log($srcIpAddr);

        //Parse OID
        $oid = substr($rawOid, strpos($rawOid, ' ') + 1);

        //Parse the value
        $value = false;
        if(preg_match('/"(.*)"/', $rawValue, $matches)){
            $value = trim($matches[1]);
        }

        if($oid == '.1.3.6.1.4.1.9.9.215.2.0.1'){
            $this->_processMacNotifTrap($srcIpAddr, $value);
        }
        else {
            $this->log("Unexpected snmp trap w/ OID $oid", 'warn', 'snmp_traps');
        }
    }

    public function _processMacNotifTrap($ip, $value){

        $tokens = explode(' ', $value);
        $upDown = !((int)$tokens[0] - 1);
        $vlan = hexdec($tokens[1] . $tokens[2]);
        $macAddr = implode('', array_slice($tokens, 3, 6));
        $port = hexdec($tokens[9] . $tokens[10]);

        $clientPort = array(
            'ClientPort' => array(
                'mac_addr' => hexdec($macAddr),
                'device_ip_addr' => ip2long($ip),
                'port' => $port,
                'vlan' => $vlan,
                'up_down' => $upDown
            )
        );

        $this->ClientPort->create();
        $result = $this->ClientPort->save($clientPort);

        if(!$result){
            $valErr = print_r($this->ClientPort->validationErrors, true);
            $this->log("Failed to save client port: $valErr", 'error', 'snmp_traps');
        }

        return $result;
    }
}
