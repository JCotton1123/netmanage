<?php

App::uses('Component', 'Controller');

class DataTablesComponent extends Component
{

    /**
     * Table columns
     */
    public $columns = array();

    /**
     * Find parameters
     */
     public $findParameters = array();

    /**
     * Model that will be used for retrieving the data
     */
    public $model = null;

    /**
     * Constructor
     */
    public function initialize(&$controller, $settings = array()) {

        //Set controller
        $this->controller = $controller;
        
        //Set model
        $model = $this->controller->modelClass;
        if(!empty($model))
            $this->model = $this->controller->$model;
        
        //Set request
        $this->request = $this->controller->request;
    }

    public function setColumns($columns,$view='default'){

        $controller = $this->controller;

        if(!isset($controller->helpers['DataTables.DataTables'][$view]))
            $controller->helpers['DataTables.DataTables'][$view] = array();

        $this->columns[$view] = $columns;
        $controller->helpers['DataTables.DataTables'][$view]['dataTable']['columns'] = $columns;
    }

    public function process($findParameters=array(),$model=false,$view='default'){

        $controller = $this->controller;

        $columns = $this->columns[$view];
        if($model === false)
            $model = $this->model;

        //Set request data based on method
        if($this->request->is('post'))
            $request = $this->request->data;
        else
            $request = $this->request->query;

        //Is this a datatables request?
        if(!isset($request['sEcho'])){
            throw new BadRequestException('Request does not contain parameter sEcho');
        }
        else {

            $dataTablesRequest = new DataTablesRequest($columns,$request);

            $defaultFindParameters = $findParameters;
            $filteredFindParameters = array_merge_recursive($dataTablesRequest->getFindParameters(),$findParameters);

            //Get data
            $data = $model->find('all',$filteredFindParameters);

            //Get filtered count
            unset($filteredFindParameters['limit']);
            unset($filteredFindParameters['offset']);
            $filteredCount = $model->find('count',$filteredFindParameters);

            //Get unfiltered count
            $unfilteredCount = $model->find('count',$defaultFindParameters);

            $controller->helpers['DataTables.DataTables'][$view]['dataTable'] = array(
                'echo' => $dataTablesRequest->getEcho(),
                'columns' => $columns,
                'data' => $data,
                'filteredCount' => $filteredCount,
                'unfilteredCount' => $unfilteredCount,
            );
        }
    }
}

class DataTablesRequest
{
    public function __construct($columns,$request){
        
        $this->columns = $columns;
        $this->request = $request;
    }

    public function getColumns(){
        return $this->columns;
    }

    public function getIndexedColumns(){

        $columns = $this->columns;

        $index = 0;
        foreach($columns as $k => $v)
            $columns[$index++] = $v;

        return $columns;
    }
    
    public function getEcho(){
        return (isset($this->request['sEcho']) ? intval($this->request['sEcho']) : 1);
    }
    
    public function getLimit($defaultLimit=10){
        if(isset($this->request['iDisplayLength']))
            return $this->request['iDisplayLength'];
        else
            return $defaultLimit;
    }
    
    public function getOffset(){
        if(isset($this->request['iDisplayStart']))
            return $this->request['iDisplayStart'];
        else
            return 0;
    }
    
    public function getOrder(){

        $columns = $this->getIndexedColumns();
        
        $order = array();
        if(isset($this->request['iSortCol_0'])){
            for ($i=0;$i<intval($this->request['iSortingCols']);$i++){
                if($this->request['bSortable_'.intval($this->request['iSortCol_'.$i])] == "true"){
                    $orderColumn = $columns[intval($this->request['iSortCol_'.$i])];
                    $order[] = $orderColumn['model'].".".$orderColumn['column']." ".$this->request['sSortDir_'.$i];
                }
            }
        }
        
        return $order;
    }
    
    public function getFilter(){

        $columns = $this->getIndexedColumns();

        $filter = array();
        if(isset($this->request['sSearch']) && $this->request['sSearch'] != ""){
            for($i=0;$i<count($columns);$i++){
                if(isset($this->request['bSearchable_'.$i]) && $this->request['bSearchable_'.$i] == "true"){
                    $column = $columns[$i];
                    $filter[] = array($column['model'].".".$column['column']." LIKE" => "%" . $this->request['sSearch'] . "%");
                }
            }
        }
        if(!empty($filter))
            return array('OR' => $filter);
        else
            return array();
    }
    
    public function getFindParameters(){
    
        $findParameters = array(
            'conditions' => $this->getFilter(),
            'order' => $this->getOrder(),
            'offset' => $this->getOffset(),
        );

        $limit = $this->getLimit();
        if($limit != -1)
            $findParameters['limit'] = $limit;

        return $findParameters;
    }
}
