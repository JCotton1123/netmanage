<?php

class SNMPDevice {

    private $ipAddress;
    private $snmpCommunity;
    private $snmpTimeout;
    private $snmpRetry;
    private $attrDefs;
    private $debug = false;

    const SYS_OBJ_ID_OID = '1.3.6.1.2.1.1.2.0';

    public function __construct($ipAddress, $snmpCommunity, $attrDefs=array(), $snmpTimeout=1000000, $snmpRetry=3){

        $this->ipAddress = $ipAddress;
        $this->snmpCommunity = $snmpCommunity;
        $this->attrDefs = $attrDefs;
        $this->snmpTimeout = $snmpTimeout;
        $this->snmpRetry = $snmpRetry;

        //SNMP Options
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
        snmp_set_enum_print('1');
    }

    /**
     * Turn debugging on or off
     */
    public function setDebug($debug){

        $this->debug = $debug;
    }

    /*
     * Load attribute definitions
     */
    public function loadAttrDefs($attrDefs, $merge=true){

        if($merge)
            $this->attrDefs = array_replace_recursive($this->attrDefs, $attrDefs);
        else
            $this->attrDefs = $attrDefs;
    }

    /**
     * Check whether a device is responding to SNMP requests
     */
    public function ping($timeout=false, $retry=false){

        //SysObjID is supposed to be defined for every SNMP device
        //so this is a good test to see if the device is responding
        //to this community name
        return @$this->getSysObjectId($timeout, $retry) !== false;
    }

    /**
     * Query this devices sysObjectID.
     */
    public function getSysObjectId($timeout=false, $retry=false){

        $timeout = ($timeout === false) ? $this->snmpTimeout : $timeout;
        $retry = ($retry === false) ? $this->snmpRetry : $retry;

        if($this->debug){
            echo implode(" ", array(
                "snmpget",
                "-v 1",
                "-c " . $this->snmpCommunity,
                $this->ipAddress,
                self::SYS_OBJ_ID_OID
            )) . "\n";
        }

        $value = snmpget(
            $this->ipAddress,
            $this->snmpCommunity,
            self::SYS_OBJ_ID_OID,
            $timeout,
            $retry
        );

        if($value === false)
            return false;

        return trim($value, ".");
    }

    /**
     * Get an attribute
     */
    public function getAttribute($attribute, $timeout=false, $retry=false) {

        $baseAttribute = (is_array($attribute)) ? $attribute[0] : $attribute;

        $oid = $this->friendlyAttrToOid($attribute);
        $timeout = ($timeout === false) ? $this->snmpTimeout : $timeout;
        $retry = ($retry === false) ? $this->snmpRetry : $retry;

        if($this->debug){
            echo implode(" ", array(
                "snmpget",
                "-v 1",
                "-c " . $this->snmpCommunity,
                $this->ipAddress,
                $oid
            )) . "\n";
        }

        $result = snmpget(
            $this->ipAddress,
            $this->snmpCommunity,
            $oid,
            $timeout,
            $retry
        );

        return $this->applyAttrFilterIfExists($baseAttribute, $result);
    }

    /**
    * Walk an attribute
    */
    public function walkAttribute($attribute, $timeout=false, $retry=false) {

        $oid = $this->friendlyAttrToOid($attribute);
        $timeout = ($timeout === false) ? $this->snmpTimeout : $timeout;
        $retry = ($retry === false) ? $this->snmpRetry : $retry;

        if($this->debug){
            echo implode(" ", array(
                "snmprealwalk",
                "-v 1",
                "-c " . $this->snmpCommunity,
                $this->ipAddress,
                $oid
            )) . "\n";
        }

        return snmprealwalk(
            $this->ipAddress,
            $this->snmpCommunity,
            $oid,
            $timeout,
            $retry
        );
    }

    /**
     * Set an attribute
     */
    public function setAttribute($attribute, $value, $timeout=false, $retry=false) {

        $baseAttribute = (is_array($attribute)) ? $attribute[0] : $attribute;

        $oid = $this->friendlyAttrToOid($attribute);
        $valueType = $this->attrDefs[$baseAttribute]['type'];
        $value = $this->friendlyAttrValToRaw($baseAttribute, $value);
        $timeout = ($timeout === false) ? $this->snmpTimeout : $timeout;
        $retry = ($retry === false) ? $this->snmpRetry : $retry;

        if($this->debug){
            echo implode(" ", array(
                "snmpset",
                "-v 1",
                "-c " . $this->snmpCommunity,
                $this->ipAddress,
                $oid,
                $valueType,
                $value
            )) . "\n";
        }

        return snmpset(
            $this->ipAddress,
            $this->snmpCommunity,
            $oid,
            $valueType,
            $value,
            $timeout,
            $retry
        );
    }

    /**
     * Lookup the raw value for an attribute
     */
    public function friendlyAttrValToRaw($attribute, $value){

        if(!isset($this->attrDefs[$attribute]['values']))
            return $value;

        if(!isset($this->attrDefs[$attribute]['values'][$value]))
            return $value;

        return $this->attrDefs[$attribute]['values'][$value];
    }

    /**
     * Friendly attribute to OID
     */
    private function friendlyAttrToOid($attribute){

        if(is_array($attribute)){
            $baseAttribute =  $attribute[0];
            $attribute[0] = $this->attrDefs[$baseAttribute]['oid'];
            return implode('.', $attribute);
        }

        return $this->attrDefs[$attribute]['oid'];
    }

    /**
     * If defined, apply the attribute filter
     */
    private function applyAttrFilterIfExists($attribute, $value){

        if($value === false || !isset($this->attrDefs[$attribute]['filter']))
            return $value;

        if(preg_match($this->attrDefs[$attribute]['filter'], $value, $matches)){

            if(isset($matches[1]))
                return $matches[1];

            return $matches[0];
        }

        return $value;
    }
}
