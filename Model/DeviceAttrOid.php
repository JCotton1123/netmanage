<?php

class DeviceAttrOid extends AppModel {

    public function groupedBySysObjectId(){

        $attrOids = $this->find('all', array(
            'contain' => array(),
        ));

        //Group the entries by sysObjectId
        $attrsGroupedBySysObjectId = array();
        foreach($attrOids as $attr){
            $sysObjectId = $attr['DeviceAttrOid']['sys_object_id'];
            $attrName = $attr['DeviceAttrOid']['name'];
            $attrsGroupedBySysObjectId["$sysObjectId"]["$attrName"] = $attr['DeviceAttrOid'];
        }

        return $attrsGroupedBySysObjectId;
    }
}
