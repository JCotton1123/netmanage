<?php


/**
 * Request Handler extensions
 */
Router::parseExtensions('json');

/**
 * Custom routes
 */

Router::connect('/', array('controller' => 'dashboard', 'action' => 'index'));
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

Router::connect(
    '/configs/:action/*',
    array(
        'controller' => 'DeviceConfigs',
    )
);

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
