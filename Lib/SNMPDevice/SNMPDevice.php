<?php

class SNMPDevice {

    private $ipAddress;
    private $snmpCommunity;
    private $snmpWriteCommunity;
    private $snmpTimeout;
    private $snmpRetry;

    const SYS_OBJ_ID_OID = '1.3.6.1.2.1.1.2.0';

    public function __construct($ipAddress, $snmpCommunity, $snmpWriteCommunity=false, $snmpTimeout='1000000', $snmpRetry=3){

        $this->ipAddress = $ipAddress;
        $this->snmpCommunity = $snmpCommunity;
        $this->snmpWriteCommunity = $snmpWriteCommunity;
        $this->snmpTimeout = $snmpTimeout;
        $this->snmpRetry = $snmpRetry;

        //SNMP Options
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        snmp_set_enum_print('1');
    }

    /**
     * Check whether a device is responding to SNMP requests
     */
    public function ping($timeout=false, $retry=false){

        //SysObjID is supposed to be defined for every SNMP device
        //so this is a good test to see if the device is responding
        //to this community name
        if(@$this->getSysObjectId($timeout, $retry) === false)
            return false;
        else
            return true;
    }

    /**
     * Query this devices sysObjectID.
     */
    public function getSysObjectId($timeout=false, $retry=false){

        $result = $this->getAttribute(
            self::SYS_OBJ_ID_OID,
            $sysObjId,
            $timeout,
            $retry
        );

        if($result === false)
            return false;
            
        return trim($sysObjId, ".");
    }

    /**
     * Query this device for an attribute value.
     */
    public function getAttribute($oid, &$value, $timeout=false, $retry=false) {

        $timeout = ($timeout === false) ? $this->snmpTimeout : $timeout;
        $retry = ($retry === false) ? $this->snmpRetry : $retry;

        $queryError = false;
        $value = snmpget(
            $this->ipAddress,
            $this->snmpCommunity,
            $oid,
            $timeout,
            $retry
        );
        
        if($value === false){
            $queryError = true;
            $value = null;
        }

        return !$queryError;
    }

    /**
    * SNMP GetNext
    */
    public function walkAttribute($oid, &$values, $timeout=false, $retry=false) {

        $timeout = ($timeout === false) ? $this->snmpTimeout : $timeout;
        $retry = ($retry === false) ? $this->snmpRetry : $retry;

        $queryError = false;
        $values = snmprealwalk(
            $this->ipAddress,
            $this->snmpCommunity,
            $oid,
            $timeout,
            $retry
        );

        if($values === false){
            $queryError = true;
            $values = null;
        }

        return !$queryError;
    }

    /**
     * Set device attribute value
     */
    public function setAttribute($oid, $value_type, $value, $timeout=false, $retry=false) {

        $timeout = ($timeout === false) ? $this->snmpTimeout : $timeout;
        $retry = ($retry === false) ? $this->snmpRetry : $retry;

        return snmpset(
            $this->ipAddress,
            $this->snmpCommunity,
            $oid,
            $value_type,
            $value,
            $timeout,
            $retry
        );
    }
}
