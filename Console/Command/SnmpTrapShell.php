<?php

/**
# Sample input
snmptrap -v 1 -c public 127.0.0.1 .1.3.6.1.4.1.9.9.215.2.0.1 127.0.0.1 6 0 "" .1.3.6.1.4.1.9.9.215.2.0.1 s "00 00 07 00 0a 10 ab 4e 23 00 01"

UDP: [127.0.0.1]:54943->[127.0.0.1]
UDP: [127.0.0.1]:54943->[127.0.0.1]
.1.3.6.1.2.1.1.3.0 0:1:51:18.05
.1.3.6.1.6.3.1.1.4.1.0 .1.3.6.1.4.1.9.9.215.2.0.1.0.0
.1.3.6.1.4.1.9.9.215.2.0.1 "00 00 07 00 0a 10 ab 4e 23 00 01"
.1.3.6.1.6.3.18.1.3.0 127.0.0.1
.1.3.6.1.6.3.18.1.4.0 "public"
.1.3.6.1.6.3.1.1.4.3.0 .1.3.6.1.4.1.9.9.215.2.0.1
*/

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
        $rawTrapAddr = $this->in('');
        $rawComm = $this->in('');
        $rawOid2 = $this->in('');

        //Parse the src IP address
        $srcIpAddr = false;
        if(preg_match('/^UDP:\s\[(.*?)\]/', $rawSrcDst, $matches)){
            $srcIpAddr = $matches[1];
        }

        //Parse OID
        $oid = substr($rawOid2, strpos($rawOid2, ' ') + 1);

        //Parse the value
        $value = false;
        if(preg_match('/"(.*)"/', $rawValue, $matches)){
            $value = $matches[1];
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
        $upDown = $tokens[0];
        $vlan = $tokens[1] . $tokens[2];
        $macAddr = implode('', array_slice($tokens, 3, 6));
        $port = $tokens[9] . $tokens[10];

        $clientPort = array(
            'ClientPort' => array(
                'mac_addr' => hexdec($macAddr),
                'switch_ip_addr' => ip2long($ip),
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
