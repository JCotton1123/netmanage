<?php

class CiscoSNMPDevice extends SNMPDevice {

     public static $defaultAttrDefs = array(
        'name' => array(
            'oid' => '1.3.6.1.2.1.1.5.0',
            'type' => 's',
            'filter' => '/^([^.]+)\./'
        ),
        'serial' => array(
            'oid' => '1.3.6.1.4.1.9.3.6.3.0',
            'type' => 's'
        ),
        'firmware' => array(
            'oid' => '1.3.6.1.4.1.9.2.1.73.0',
            'type' => 's',
            'filter' => '/\/?([^:\/]+)\.bin$/'
        ),
        'model' => array(
            'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1001',
            'type' => 's',
            //'filter' => '/\w(.+)\w/'
        ),
        'ifdesc' => array(
            'oid' => '1.3.6.1.2.1.2.2.1.2',
            'type' => 's'
        ),
        'cdpCacheAddress' => array(
            'oid' => 'cdpCacheAddress',
            'type' => 'a'
        ),
        'cdpCacheDeviceId' => array(
            'oid' => 'cdpCacheDeviceId',
            'type' => 's',
            'filter' => '/^([^.]+)\./'
        ),
        'cdpCacheDevicePort' => array(
            'oid' => 'cdpCacheDevicePort',
            'type' => 's'
        ),
        'cdpCachePlatform' => array(
            'oid' => 'cdpCachePlatform',
            'type' => 's'
        ),
        'reload' => array(
            'oid' => '1.3.6.1.4.1.9.2.9.9.0',
            'type' => 'i',
            'values' => array(
                'reload' => 2
            )
        ),
        'ccCopyProtocol' => array(
            'oid' => '1.3.6.1.4.1.9.9.96.1.1.1.1.2',
            'type' => 'i',
            'values' => array(
                'tftp' => '1'
            )
        ),
        'ccCopySrcFileType' => array(
            'oid' => '1.3.6.1.4.1.9.9.96.1.1.1.1.3',
            'type' => 'i',
            'values' => array(
                'network' => '1',
                'startup' => '3',
                'running' => '4'
            )
        ),
        'ccCopyDstFileType' => array(
            'oid' => '1.3.6.1.4.1.9.9.96.1.1.1.1.4',
            'type' => 'i',
            'values' => array(
                'network' => '1',
                'startup' => '3',
                'running' => '4'
            )
        ),
        'ccCopyServerAddress' => array(
            'oid' => '1.3.6.1.4.1.9.9.96.1.1.1.1.5',
            'type' => 'a'
        ),
        'ccCopyFileName' => array(
            'oid' => '1.3.6.1.4.1.9.9.96.1.1.1.1.6',
            'type' => 's'
        ),
        'ccCopyEntryRowStatus' => array(
            'oid' => '1.3.6.1.4.1.9.9.96.1.1.1.1.14',
            'type' => 'i',
            'values' => array(
                'active' => '1',
                'notInService' => '2',
                'notReady' => '3',
                'createAndGo' => '4',
                'createAndWait' => '5',
                'destroy' => '6'
            )
         ),
        'ccCopyState' => array(
            'oid' => '1.3.6.1.4.1.9.9.96.1.1.1.1.10',
            'type' => 'i',
            'values' => array(
                'waiting' => '1',
                'running' => '2',
                'successful' => '3',
                'failed' => '4'
            )
        )
    );

    /**
     * Constructor
     */
    public function __construct($ipAddress, $snmpCommunity, $attrDefs=array(), $snmpTimeout=1000000, $snmpRetry=3){

        parent::__construct($ipAddress, $snmpCommunity, $attrDefs, $snmpTimeout, $snmpRetry);
    }

    /**
     * Reboot a cisco device
     */
    public function reload(){

        return $this->setAttribute('reload', 'reload');
    }

    /**
     * Ifindex to ifdescr
     */
    public function getIfdescr($ifindex, $timeout=false, $retry=false){

        return $this->getAttribute(
            array(
                'ifdesc',
                $ifindex
            ),
            $timeout,
            $retry
        );
    }

    /**
     * Retrieve a list of neighbors via CDP
     */
    public function getNeighbors($timeout=false, $retry=false) {

        $neighbors = array();

        //Get Device Neighbors (CDP)
        $neighborMap = $this->walkAttribute(
            'cdpCacheAddress',
            $timeout,
            $retry
        );

        if($neighborMap === false)
            return false;

        foreach($neighborMap as $oid => $neighborAddr){

            $neighbor = array();

            //Parse neighbor address
            $neighborAddr = long2ip(hexdec(bin2hex($neighborAddr)));

            //Parse oid for cdp index & ifindex
            $oidTokens = explode('.', $oid);
            $ifindex = $oidTokens[count($oidTokens)-2];
            $index = "${ifindex}." . $oidTokens[count($oidTokens)-1];

            //Set discovery timestamp
            $neighbor['discovery_timestamp'] = date('Y-m-d H:i:s');

            //Set neighbor address
            $neighbor['ip_addr'] = $neighborAddr;

            //Get neighbor name
            $neighbor['name'] = $this->getAttribute(
                array(
                    'cdpCacheDeviceId',
                    $index
                ),
                $timeout,
                $retry
            );

            //Local port
            $neighbor['local_port'] = $this->getIfdescr(
                $ifindex,
                $timeout,
                $retry
            );

            //Get neighbor port
            $neighbor['neighbor_port'] = $this->getAttribute(
                array(
                    'cdpCacheDevicePort',
                    $index
                ),
                $timeout,
                $retry
            );

            //Get neighbor platform
            $neighbor['platform'] = $this->getAttribute(
                array(
                    'cdpCachePlatform',
                    $index
                ),
                $timeout,
                $retry
            );

            $neighbors[] = $neighbor;
        }

        return $neighbors;
    }

    /**
     * Wr mem
     */
    public function copyRunningConfigToStartup($operTimeout=10000000){

        $pre = array(
            array(
                'attribute' => 'ccCopySrcFileType',
                'value' => 'running'
            ),
            array(
                'attribute' => 'ccCopyDstFileType',
                'value' => 'startup'
            ),
        );

        $start = array(
            'attribute' => 'ccCopyEntryRowStatus',
            'value' => 'active'
        );

        $poll = array(
            'attribute' => 'ccCopyState',
            'value' => 'running'
        );

        $cleanup = array(
            'attribute' => 'ccCopyEntryRowStatus',
            'value' => 'destroy'
        );

        $result = $this->execRowOper($pre, $start, $poll, $cleanup, $operTimeout);

        return $result == $this->friendlyAttrValToRaw('ccCopyState', 'successful');
    }

    /**
     * Push the running or startup config to a tftp server
     */
    public function pushConfigToTftp($type, $file, $tftpServerAddress, $operTimeout=10000000){

        if(!in_array($type, array('running', 'startup')))
            throw new \InvalidArgumentException('Invalid configuration type');

        return $this->tftpConfigCmd($type, 'network', $file, $tftpServerAddress, $operTimeout);
    }

    /**
     * Pull a config from a tftp server
     */
    public function pullConfigFromTftp($type, $file, $tftpServerAddress, $operTimeout=10000000){

        if(!in_array($type, array('running', 'startup')))
            throw new \InvalidArgumentException('Invalid configuration type');

        return $this->tftpConfigCmd('network', $type, $file, $tftpServerAddress, $operTimeout);
    }

    /**
     * Get/set a device's configuration with a src/dst of a tftp server
     */
    public function tftpConfigCmd($src, $dst, $file, $tftpServerAddress, $operTimeout=10000000){

        $pre = array(
            array(
                'attribute' => 'ccCopyProtocol',
                'value' => 'tftp'
            ),
            array(
                'attribute' => 'ccCopySrcFileType',
                'value' => $src
            ),
            array(
                'attribute' => 'ccCopyDstFileType',
                'value' => $dst
            ),
            array(
                'attribute' => 'ccCopyServerAddress',
                'value' => $tftpServerAddress,
            ),
            array(
                'attribute' => 'ccCopyFileName',
                'value' => basename($file)
            )
        );
        
        $start = array(
            'attribute' => 'ccCopyEntryRowStatus',
            'value' => 'active'
        );
        
        $poll = array(
            'attribute' => 'ccCopyState',
            'value' => 'running'
        );
        
        $cleanup = array(
            'attribute' => 'ccCopyEntryRowStatus',
            'value' => 'destroy'
        );
        
        $result = $this->execRowOper($pre, $start, $poll, $cleanup, $operTimeout);

        return $result == $this->friendlyAttrValToRaw('ccCopyState', 'successful');
    }

    /**
     * Execute a row operation
     */
    public function execRowOper($pre, $start, $poll, $cleanup, $operTimeout){

        $error = false;
        $operResult = false;
        
        $rowEntryId = rand(100,999);
        
        //Set each pre operation attributes
        foreach($pre as $p){
            $result = $this->setAttribute(
                array(
                    $p['attribute'],
                    $rowEntryId
                ),
                $p['value']
            );
            if($result === false) {
                $error = true;
                break;
            }
        }
        
        //Start the operation
        if(!$error){
            
            $result = $this->setAttribute(
                array(
                    $start['attribute'],
                    $rowEntryId
                ),
                $start['value']
            );
            
            if($result === false)
                $error = true;
        }
        
        //Start polling to determine when the operation has completed
        $startTime = microtime();
        if(!$error){
            while(true){
            
                usleep(10000000); //1 sec
                $operResult = $this->getAttribute(
                    array(
                        $poll['attribute'],
                        $rowEntryId
                    )
                );
               
                if(
                    $operResult !== false &&
                    $operResult != $this->friendlyAttrValToRaw($poll['attribute'], $poll['value'])
                ) {
                    break;
                }

                if(microtime() - $startTime >= $operTimeout)
                    throw new \RuntimeException('Operation timed out');
            }
        }
        
        //Cleanup - destroy the row entry
        $this->setAttribute(
            array(
                $cleanup['attribute'],
                $rowEntryId
            ),
            $cleanup['value']
        );

        return $operResult;
    }
}
