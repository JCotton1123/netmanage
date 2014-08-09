<?php

App::uses('CakeEmail', 'Network/Email');

class LogShell extends AppShell {

    public $uses = array(
        'Setting',
        'DeviceLog'
    );

    public $tasks = array(
        'Settings'
    );

    const LOG_DELIM = ':';

    public function main() {

        $countProcessedLogs = 0;

        $this->settings = $this->Settings->get(array(
            'global',
            'device_logging'
        ));

        $storageFilter = $this->settings['device_logging.storage_filter'];
        $maxLogsBeforeTerm = $this->settings['device_logging.max_logs_before_term'];
        $notificationsEnabled = $this->settings['device_logging.enable_notifications'];
        $notificationRecipients = explode(
            ",",
            str_replace(
                ' ',
                '',
                $this->settings['device_logging.notification_recipients']
            )
        );
        $notificationFilter = $this->settings['device_logging.notification_filter'];

        while(true){
            try {

                $log = $this->in('');

                $countProcessedLogs++;

                //Send notification if enabled and not filtered
                if($notificationsEnabled && !empty($notificationRecipients)){
                    if(empty($notificationFilter) || !preg_match($notificationFilter, $log)){
                        $email = new CakeEmail('default');
                        $email->to($notificationRecipients);
                        $email->subject("[NetManage] Log Notification");
                        $email->send($log);
                    }
                }

                //Test against storage filter
                if(!empty($storageFilter) && preg_match($storageFilter, $log)){
                    continue;
                }

                //Parse and store the log
                $parsedLog = $this->_parseLog($log);
                if($parsedLog === false){
                    $this->log("Unable to parse device log: ${log}", 'error', 'device_logging');
                    continue;
                }
                $this->DeviceLog->create();
                $result = $this->DeviceLog->save(array(
                    'DeviceLog' => $parsedLog
                ));
                if($result === false){
                    $valErr = print_r($this->DeviceLog->validationErrors, true);
                    $this->log("Failed to save log message: ${valErr}", 'error', 'device_logging');
                }

                //Check if we've exceed the max logs count & terminate if necessary
                if($countProcessedLogs >= $maxLogsBeforeTerm){
                    $this->log('LogShell hit max log before termination. Exiting gracefully.', 'info', 'device_logging');
                    exit(0);
                }
            }
            catch(\Exception $e){
                $this->log("Encountered exception:" . $e->getMessage(), 'error', 'device_logging');
            }
        }
    }

    private function _parseLog($log){

        //Format: IP address .* %Fac-Sev-Mnem: message
        if(preg_match('/^(([0-9]{1,3}\.?){4,}).*%([A-Z0-9\-_]+):(.*)$/', $log, $matches)){

            return array(
                'timestamp' => date('Y-m-d H:i:s'),
                'device_ip_addr' => ip2long($matches[1]),
                'fac_sev_mnem' => $matches[3],
                'message' => $matches[4]
            );
        }

        //Format: IP address .*
        if(preg_match('/^(([0-9]{1,3}\.?){4,})(.*)$/', $log, $matches)){

            return array(
                'timestamp' => date('Y-m-d H:i:s'),
                'device_ip_addr' => ip2long($matches[1]),
                'fac_sev_mnem' => false,
                'message' => $matches[3]
            );
        }

        return false;
    }
}
