<?php

App::uses('AppHelper', 'View/Helper');

class ActionMenuHelper extends AppHelper {
    
    public $helpers = array('Html');

    public function menu($menu){

        $menuItems = "";
        foreach($menu as $name => $item){
            $enabled = !(isset($item['enabled']) && !$item['enabled']);
            $confirmMsg = $enabled && isset($item['confirm']) ? $item['confirm'] : false;
            $url = $this->Html->link(
                $name,
                ($enabled ? $item['url'] : '#'),
                array(
                    'role' => 'menuitem',
                    'tabindex' => '-1'
                ),
                $confirmMsg
            );
            $class = $enabled ? '' : 'disabled';
            $menuItems .= implode("\n", array(
                "<li class=\"${class}\">",
                $url,
                "</li>\n"
            ));
        }

        $html = <<<EOD
<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="action-menu" data-toggle="dropdown">
    Actions <span class="caret"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="action-menu">
    ${menuItems}
  </ul>
</div>
EOD;

        return $html;
    }
}
