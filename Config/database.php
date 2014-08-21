<?php
class DATABASE_CONFIG {

	public $default = array();

    public function __construct() {

        $this->default = array(
            'datasource' => env('DB_TYPE'),
            'persistent' => env('DB_PERSIST_CONNS'),
            'host' => env('DB_HOST'),
            'login' => env('DB_LOGIN'),
            'password' => env('DB_PASSWD'),
            'database' => env('DB_NAME'),
            'prefix' => '',
        );
    }
}
