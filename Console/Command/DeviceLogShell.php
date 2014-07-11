<?php

class DeviceLogShell extends AppShell {

    public $uses = array(
        'DeviceLog'
    );

    const LOG_DELIM = ':';

    public function main() { }

    public function process() {

        $log = $this->args[0];

        $formattedLog = $this->parseLogIntoStdFormat($log);

        $result = $this->DeviceLog->save(array(
            'DeviceLog' => $formattedLog
        ));

        if($result === false){
            throw new \Exception('Failed to save log message');
        }
    }

    private function parseLogIntoStdFormat($log){

        $formattedLog = array();

        //First field is the host (ip address)
        $firstDelim = strpos($log, self::LOG_DELIM);
        $host = ip2long(substr($log, 0, $firstDelim));
        $log = substr($log, $firstDelim+1);

        //Contains hostname and program id
        //hostname|pid|datehour|minute|sec|fac_sev_mnem|msg
        if(preg_match('/^[^:]+: [0-9]+:([^:]+:){3} %[A-Z0-9_-]+:.+$/', $log)){
            
            $logFields = explode(self::LOG_DELIM, $log, 7);
            return array(
                'host' => $host, 
                'timestamp' => date('Y-m-d H:i:s'),
                'fac_sev_mnem' => $logFields[5],
                'message' => $logFields[6]
            ); 
        }

        //Contains program id but no hostname
        //pid|datehour|minute|sec|fac_sev_mnem|msg
        if(preg_match('/^[0-9 ]+:([^:]+:){3} %[A-Z0-9_-]+:.+$/', $log)){
            
            $logFields = explode(self::LOG_DELIM, $log, 6);
            return array(
                'host' => $host,
                'timestamp' => date('Y-m-d H:i:s'),
                'fac_sev_mnem' => $logFields[4],
                'message' => $logFields[5]
            ); 
        }

        //Contains hostname but no program id
        //hostname|datehour|minute|sec|fac_sev_mnem|msg
        if(preg_match('/^([^:]+:){4} %[A-Z0-9_-]+:.+$/', $log)){

            $logFields = explode(self::LOG_DELIM, $log, 6);
            return array(
                'host' => $host,
                'timestamp' => date('Y-m-d H:i:s'),
                'fac_sev_mnem' => $logFields[4],
                'message' => $logFields[5]
            );
        }

        //Does not contain a hostname or a program id
        //datehour|minute|sec|fac_sev_mnem|msg
        if(preg_match('/^([^:]+:){3} %[A-Z0-9_-]+:.+$/', $log)){
            
            $logFields = explode(self::LOG_DELIM, $log, 5);
            return array(
                'host' => $host,
                'timestamp' => date('Y-m-d H:i:s'),
                'fac_sev_mnem' => $logFields[3],
                'message' => $logFields[4]
            ); 
        }

        throw new \Exception("Unexpected log format: $log");
    }
}
