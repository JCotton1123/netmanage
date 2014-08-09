<?php

class DeviceAttrOidsController extends AppController {

    public function index() {

        $this->DataTables->setColumns(array(
            'SysObjectID' => array(
                'model' => 'DeviceAttrOid',
                'column' => 'sys_object_id'
            ),
            'Name' => array(
                'model' => 'DeviceAttrOid',
                'column' => 'name'
            ),
            'OID' => array(
                'model' => 'DeviceAttrOid',
                'column' => 'oid',
            ),
            'Filter' => array(
                'model' => 'DeviceAttrOid',
                'column' => 'filter'
            )
        ));

        if($this->request->isAjax()){

            $this->DataTables->process(array(
                'contain' => array(),
                'field' => array(
                    'DeviceAttrOid.*'
                )
            ));
        }
    }

    public function add() {
        
        if($this->request->is('post')){
            
            $result = $this->DeviceAttrOid->save($this->request->data);
            if($result){
                $this->flash('Device attribute oid added successfully.', 'success');
                $this->redirect('/device_attr_oids');
            }
            else {
                $valErr = print_r($this->DeviceAttrOid->validationErrors, true);
                $this->flash("Unable to add attribute OID. ${valErr}");
            }
        }
    }

    public function update() {

        $this->autoRender = false;

        $id = $this->request->data('id');
        $column = $this->request->data('columnName');
        $value = $this->request->data('value');

        if($column == 'SysObjectID')
            $column = 'sys_object_id';
        $dbColumn = strtolower($column);

        $this->DeviceAttrOid->id = $id;
        $result = $this->DeviceAttrOid->save(array(
            'DeviceAttrOid' => array(
                $dbColumn => $value
            )
        ));

        if($result){
            echo "\"$value\"";
            return;
        }

        throw new BadRequestException(
            print_r($this->DeviceAttrOid->validationErrors, true)
        );
    }

    public function delete($id=null) {

        if($this->request->is('get')){

            $this->DataTables->setColumns(array(
                'SysObjectID' => array(
                    'model' => 'DeviceAttrOid',
                    'column' => 'sys_object_id'
                ),
                'Name' => array(
                    'model' => 'DeviceAttrOid',
                    'column' => 'name'
                ),
                'OID' => array(
                    'model' => 'DeviceAttrOid',
                    'column' => 'oid',
                ),
                'Filter' => array(
                    'model' => 'DeviceAttrOid',
                    'column' => 'filter'
                )
            ));

            if($this->request->isAjax()){

                $this->DataTables->process(array(
                    'contain' => array(),
                    'field' => array(
                        'DeviceAttrOid.*'
                    )
                ));
            } 
        }
        elseif($this->request->is('post')){

            if($this->DeviceAttrOid->delete($id)){
                $this->flash('Device attribute oid successfully deleted.', 'success');
                $this->redirect('/device_attr_oids');
            }
            else {
                $this->flash('Unable to delete device attribute oid.');
                $this->redirect('/device_attr_oids');
            }
        }
    }
}
