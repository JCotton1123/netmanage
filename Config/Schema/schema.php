<?php

App::uses('ClassRegistry', 'Utility');

class NetmanageSchema extends CakeSchema {

    public function before($event = array()) {

        $this->db = ConnectionManager::getDataSource('default');
        $this->db->cacheSources = false;

        return true;
    }

    private function initModel($model) {

        App::uses($model, 'Model');
        return ClassRegistry::init($model);
    }

    public function after($event = array()) {

        if(isset($event['create'])) {
            switch($event['create']) {
                case 'device_attr_oids':
                    return $this->afterDeviceAttrOids();
                case 'users':
                    return $this->afterUsers();
                case 'roles':
                    return $this->afterRoles();
                case 'settings':
                    return $this->afterSettings();
            }
        }
    }

    private function afterDeviceAttrOids() {

        $deviceAttrOid = $this->initModel('DeviceAttrOid');

        $defaultAttrOids = array(
            array(
                'sys_object_id' => '1.3.6.1.4.1.9',
                'name' => 'name',
                'oid' => '1.3.6.1.2.1.1.5.0',
                'filter' => '/^([^.]+)\\./'
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9',
                'name' => 'serial',
                'oid' => '1.3.6.1.4.1.9.3.6.3.0',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9',
                'name' => 'firmware',
                'oid' => '1.3.6.1.4.1.9.2.1.73.0',
                'filter' => '/\\/?([^:\\/]+)\\.bin$/'
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1001',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.283',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.429',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.366',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.367',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.876',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.5.59',
                'name' => 'serial',
                'oid' => '1.3.6.1.4.1.9.5.1.2.19.0',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.5.59',
                'name' => 'firmware',
                'oid' => '1.3.6.1.4.1.9.5.1.1.38.0',
                'filter' => '/\\/?([^:\\/]+)\\.bin$/'
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.5.59',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.427',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.559',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.283',
                'name' => 'firmware',
                'oid' => '1.3.6.1.4.1.9.2.1.73.0',
                'filter' => '/^sup-bootflash:(.+)\\.bin$/'
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.1011',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.1358',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            ),
            array(
                'sys_object_id' => '1.3.6.1.4.1.9.1.1525',
                'name' => 'model',
                'oid' => '1.3.6.1.2.1.47.1.1.1.1.13.1',
                'filter' => NULL
            )
        );

        $deviceAttrOid->saveMany($defaultAttrOids);
    }

    private function afterUsers() {

        $user = $this->initModel('User');

        $user->id = 1;
        $user->save(
            array('User' => array(
                'role_id' => 1,
                'username' => 'admin',
                'password' => 'netmanage',
                'first_name' => 'Builtin',
                'last_name' => 'Administrator',
                'email' => 'root@netmanage.local'
            ),
            false
        ));
    }

    private function afterRoles() {

        $role = $this->initModel('Role');

        $role->id = 1;
        $role->save(
            array('Role' => array(
                'name' => 'administrator',
                'description' => 'Can perform all actions'
            ),
            false
        ));

        $role->id=2;
        $role->save(
            array('Role' => array(
                'name' => 'user',
                'description' => 'Restricted to view only actions'
            ),
            false
        ));
    }

    public function afterSettings(){

        $setting = $this->initModel('Setting');

        $defaultSettings = array(
            array(
                'var' => 'global.snmp_community',
                'val' => 'public',
                'description' => 'The SNMP read/write community NetManage will use to interface with your devices.'
            ),
            array(
                'var' => 'global.snmp_timeout',
                'val' => '3000000',
                'description' => 'The number of microseconds NetManage will wait for an SNMP response from a device.'
            ),
            array(
                'var' => 'global.snmp_retry',
                'val' => '5',
                'description' => 'The number of times NetManage will retry an SNMP operation after a timeout has occurred.'
            ),
            array(
                'var' => 'discovery.seeds',
                'val' => '127.0.0.1',
                'description' => 'A comma seperated list of devices NetManage will seed the discovery process with.'
            ),
            array(
                'var' => 'discovery.ip_blacklist',
                'val' => NULL,
                'description' => 'If this setting is defined, NetManage will not discover devices matching this PCRE filter. This filter supersedes the whitelist filter.'
            ),
            array(
                'var' => 'discovery.ip_whitelist',
                'val' => '/^.*$/',
                'description' => 'If this setting is defined, NetManage will only discover devices matching this PCRE filter'
            ),
            array(
                'var' => 'device_logging.max_logs_before_term',
                'val' => '200',
                'description' => 'An instance of the LogShell will process this many logs before terminating. The syslog daemon will start a new instance of the LogShell after termination automatically. This is meant as a workaround to address memory leaks.'
            ),
            array(
                'var' => 'device_logging.enable_notifications',
                'val' => '0',
                'description' => 'A boolean indicating whether device log messages should be sent out as notifications. Before you enable this you should set the notification filter.'
            ),
            array(
                'var' => 'device_logging.notification_recipients',
                'val' => 'root@netmanage.local',
                'description' => 'Device log notifications will be sent to this comma separated list of recipients'
            ),
            array(
                'var' => 'device_logging.notification_filter',
                'val' => '/(LINEPROTO-5-UPDOWN)|(LINK-3-UPDOWN)|(VQPCLIENT-3-THROTTLE)|(VQPCLIENT-3-VLANNAME)|(SYS-2-MALLOCFAIL)|(ILPOWER-5-POWER_GRANTED)|(ILPOWER-5-IEEE_DISCONNECT)|(LINK-5-CHANGED)|(DOT1X-5)|(AUTHMGR-5)|(MAB-5)|(ILPOWER-3-CONTROLLER_PORT_ERR)/',
                'description' => 'Device logs matching this PCRE filter will not be sent as notifications.'
            ),
            array(
                'var' => 'device_logging.storage_filter',
                'val' => '/(LINEPROTO-5-UPDOWN)|(LINK-3-UPDOWN)|(LINK-5-CHANGED)/',
                'description' => 'Logs matching the supplied PCRE filter will not be stored.'
            ),
            array(
                'var' => 'tftp.root',
                'val' => '/var/lib/tftpboot',
                'description' => 'Local tftp directory root'
            ),
            array(
                'var' => 'tftp.address',
                'val' => '127.0.0.1',
                'description' => 'The local address the tftp process is listening on'
            ),
            array(
                'var' => 'config_backup.worker_count',
                'val' => '4',
                'description' => 'Controls the number of workers that are spawned to retrieve your devices configurations.'
            )
        );

        $setting->saveMany($defaultSettings, array('validate' => false));
    }

    public $client_ports = array(
        'mac_addr' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true),
        'device_ip_addr' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
        'port' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
        'vlan' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4, 'unsigned' => true),
        'up_down' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(

        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
    );

    public $device_attr_oids = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'sys_object_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 512, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'oid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 512, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'filter' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $device_configs = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'device_ip_addr' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
        'config' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'diff' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'device_configs_device_ip_addr_idx' => array('column' => 'device_ip_addr', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $device_logs = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'device_ip_addr' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
        'timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'index'),
        'fac_sev_mnem' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'message' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'device_logs_timestamp_idx' => array('column' => 'timestamp', 'unique' => 0),
            'device_logs_device_ip_addr_idx' => array('column' => 'device_ip_addr', 'unique' => 0),
            'device_logs_fac_sev_mnem_idx' => array('column' => 'fac_sev_mnem', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $device_neighbors = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'device_ip_addr' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
        'neighbor_ip_addr' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
        'local_port' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 75, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'neighbor_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'neighbor_port' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 75, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'neighbor_platform' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'last_seen' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'device_neighbors_device_ip_addr_idx' => array('column' => 'device_ip_addr', 'unique' => 0),
            'device_neighbors_neighbor_ip_idx' => array('column' => 'neighbor_ip_addr', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $devices = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'ip_addr' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
        'sys_object_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 512, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'name' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'serial' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'firmware' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'model' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'last_seen' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'devices_ip_addr_idx' => array('column' => 'ip_addr', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $metrics = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'var' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'val' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'dashboard_stats_var_idx' => array('column' => 'var', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $netmanage_logs = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'timestamp' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'type' => array('type' => 'string', 'null' => true, 'default' => 'info', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'message' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $roles = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $settings = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'var' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'val' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'setings_var_idx' => array('column' => 'var', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    public $users = array(
        'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'role_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
        'username' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'password' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'first_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'last_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'users_username_idx' => array('column' => 'username', 'unique' => 0)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

}
