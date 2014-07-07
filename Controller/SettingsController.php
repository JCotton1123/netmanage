<?php

class SettingsController extends AppController {
 
    public function index() {

        if($this->request->is('post')){

            $rawNewSettings = $this->request->data;

            //The dot (.) in the field names is replaced with __
            //since PHP doesn't like associate array keys containing dots.
            //Convert __ to .
            $newSettings = array();
            foreach($rawNewSettings as $name => $value){
                $newSettings[str_replace('__', '.', $name)] = $value;
            }

            //Run through the validation rules for each setting
            //TO BE IMPLEMENTED

            //Update settings
            //This should be moved to the model
            $dataSource = $this->Setting->getDataSource();
            $dataSource->begin();
            $lastSaveSucceeded = true;
            foreach($newSettings as $var => $val){
                $lastSaveSucceeded = $this->Setting->updateAll(
                    array('Setting.val' => $dataSource->value($val, 'string')),
                    array('Setting.var' => $var)
                );
                if(!$lastSaveSucceeded)
                    break;
            }
            if($lastSaveSucceeded) {
                $dataSource->commit();
                $this->flash('Settings saved successfully', 'success');
            }
            else {
                $dataSource->rollback();
                $this->flash('Failed to save settings');
            }
        }
        
        $settings = $this->Setting->find('all', array(
            'contain' => array(),
            'order' => 'var'
        ));

        //Settings attributes are namespaced
        //We'll group them by namespace here
        $settingsGroupedByNamespace = array();
        foreach($settings as $setting){

            $name = $setting['Setting']['var'];
            $value = $setting['Setting']['val'];
            $descr = $setting['Setting']['description'];

            $firstPeriod = strpos($name, '.');
            $namespace = substr($name, 0, $firstPeriod);
            $shortName = substr($name, $firstPeriod+1);

            $settingsGroupedByNamespace[$namespace][] = array(
                'full_name' => $name,
                'short_name' => $shortName,
                'value' => $value,
                'description' => $descr
            );
        }

        //Prioritize the namespaces
        $nextPrio = 0;
        $settingsPrioritized = array();
        $settingsPrioritized["$nextPrio-global"] = $settingsGroupedByNamespace['global'];
        unset($settingsGroupedByNamespace['global']);
        //Unprioritized namespaces
        foreach($settingsGroupedByNamespace as $group => $groupSettings){
            $settingsPrioritized["$nextPrio-$group"] = $groupSettings;
        }

        $this->set('settings', $settingsPrioritized);
    }
}
