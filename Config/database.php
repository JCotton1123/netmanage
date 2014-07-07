<?php
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'app_netmanage',
		'password' => 'password',
		'database' => 'netmanage',
		'encoding' => 'utf8'
	);

    public $cisco_advisories_rss = array(
        'datasource' => 'RssSource',
        'feedUrl' => 'http://tools.cisco.com/security/center/psirtrss20/CiscoSecurityAdvisory.xml',
        'cacheTime' => '+1 day'
    );
}
