<?php

$navItems = array(
    'Home' => array(
        'url' => '/',
        'controllers' => array(
            'dashboard'
        ),
    ),
    'Clients' => array(
        'url' => '/clients',
        'controllers' => array(
            'clients'
        )
    ),
    'Devices' => array(
        'controllers' => array(
            'devices',
            'device_configs',
            'device_attr_oids',
            'device_config_mgmt',
        ),
        'subitems' => array(
            'Device Management' => array(
                'url' => '/devices'
            ),
            'Configuration Management' => array(
                'url' => '/device_config_mgmt',
                'roles' => array(
                    'administrator'
                )
            ),
            'Device Attribute OIDs' => array(
                'url' => '/device_attr_oids',
                'roles' => array(
                    'administrator'
                )
            ),
        )
    ),
    'Reports' => array(
        'url' => '/reports',
        'controllers' => array(
            'reports'
        )
    ),
    'Settings' => array(
        'url' => '/settings',
        'controllers' => array(
            'settings'
        )
    ),
    'Users' => array(
        'url' => '/users',
        'controllers' => array(
            'users',
            'roles'
        )
    ),
);
?>

<?php foreach($navItems as $name => $item){ 
  $navActive = in_array($__controller, $item['controllers']);
  $navDisabled = isset($item['roles']) && !in_array($__userRole, $item['roles']);
  $hasSubItems = isset($item['subitems']);
  $url = isset($item['url']) && !$navDisabled ? $item['url'] : '#';
  $subitems = $hasSubItems ? $item['subitems'] : array();
  $class = array();
  if($navActive)
    $class[] = 'active';
  if($navDisabled)
    $class[] = 'disabled';
  $class = implode(' ', $class);
  if(!$hasSubItems){ ?>
    <li class="<?php echo $class; ?>">
      <a href="<?php echo $url; ?>"><?php echo $name; ?></a>
    </li>
  <?php }
  else { ?>
    <li class="dropdown <?php echo $class; ?>">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?php echo $name; ?> <span class="caret"></span>
      </a>
      <ul class="dropdown-menu" role="menu">
        <?php foreach($subitems as $subitemName => $subitem) { 
          $subnavDisabled = isset($subitem['roles']) && !in_array($__userRole, $subitem['roles']);
          $url = !$subnavDisabled ? $subitem['url'] : '#'; 
          $subnavClass = $subnavDisabled ? 'disabled' : '';
          ?>
          <li class="<?php echo $subnavClass; ?>">
            <?php echo $this->Html->link($subitemName, $url); ?>
          </li>
        <?php } ?>
      </ul>
    </li>
  <?php } ?>
<?php } ?>
