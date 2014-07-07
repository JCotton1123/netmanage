<?php
class DataTablesComponent extends Object {

	//called before Controller::beforeFilter()
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
	}

	//called after Controller::beforeFilter()
	function startup(&$controller) {
	}

	//called after Controller::beforeRender()
	function beforeRender(&$controller) {
	}

	//called after Controller::render()
	function shutdown(&$controller) {
	}

	//called before Controller::redirect()
	function beforeRedirect(&$controller, $url, $status=null, $exit=true) {
	}

	/*
	function redirectSomewhere($value) {
		// utilizing a controller method
		$this->controller->redirect($value);
	}
	*/

	public function get_client_params(){

		return $this->controller->data;
	}

	public function find($model,$param_find_options=array()){

		$client_params = $this->get_client_params();

		//Input
		$columns = array_key_exists('sColumns',$client_params) ? explode(",",$client_params['sColumns']) : $this->get_model_columns($model);
		$column_count = array_key_exists('iColumns',$client_params) ? $client_params['iColumns'] : count($columns);
		$search = (array_key_exists('sSearch',$client_params) && !empty($client_params['sSearch'])) ? $client_params['sSearch'] : false; 
		$sEcho = array_key_exists('sEcho',$client_params) ? $client_params['sEcho'] : 1;
		$limit = array_key_exists('iDisplayLength',$client_params) ? $client_params['iDisplayLength'] : 10;
		$offset = array_key_exists('iDisplayStart',$client_params) ? $client_params['iDisplayStart'] : 0;

		//Sorted Columns
		$sorted_columns = array();
		for($x=0;$x<$column_count;$x++){
			if(array_key_exists('iSortCol_' . $x,$client_params) && $client_params['iSortCol_' . $x] != ""){
				$sorted_columns[$columns[$x]] = $client_params['sSortDir_' . $x];
			}
		}

		$columns_assoc = array();
		foreach($columns as $column){
			if(strpos($column,".") === false){
				array_push($columns_assoc,array('model' => $model,'field' => $column));
			}
			else {
				$tokens = explode(".",$column);
				array_push($columns_assoc,array('model' => $tokens[0],'field'=> $tokens[1]));
			}
		}


		//Default options
		$count_options = array(
			'fields' => array($columns[0], "count(*) as count")
		);

		$find_options = array(
			'fields' => $columns,
			'limit' => $limit,
			'offset' => $offset
		);

		//Add condition param if search term exists
                if($search !== false){
			$conditions = array();
                        foreach($columns as $col){
                                $conditions[$col . " LIKE"] = '%' . $search . '%';
                        }
                        $conditions = array('OR' => $conditions);

			$count_options['conditions'] = $conditions;
			$find_options['conditions'] = $conditions;
                }

		//Add sorting options if exist
		if(count($sorted_columns) > 0) {

			$orderby = array();

			foreach($sorted_columns as $col_name => $sort_dir){
				array_push($orderby,$col_name . " " . $sort_dir);
			}

			$find_options['order'] = $orderby;
		}


		//Overwrite options with user supplied if exist
		$count_options = array_merge($count_options,$param_find_options);
		$find_options = array_merge($find_options,$param_find_options);

		$results_total_count = $this->controller->$model->find('all',$count_options);
		CakeLog::write('debug',print_r($count_options,true));
		$results_total_count = $results_total_count[0][0]['count'];

		CakeLog::write('debug',print_r($find_options,true));
		$results = $this->controller->$model->find('all',$find_options);

		CakeLog::write('debug',print_r($results,true));

		//Transform results into JSON
		$json_result = array();

		$json_result['sEcho'] = $sEcho;
		$json_result['iTotalRecords'] = $results_total_count;
		$json_result['iTotalDisplayRecords'] = $results_total_count;
		$json_result['aaData'] = array();

		foreach($results as $result){
			$tmp = array();
			foreach($columns_assoc as $column_descr){
				array_push($tmp,$result[$column_descr['model']][$column_descr['field']]);
			}
			array_push($json_result['aaData'],$tmp);
		}

		return json_encode($json_result);
	}

	private function get_model_columns($model){
		$columns = array();
		$schema = $this->controller->$model->schema();
                foreach($schema as $name => $descr){
                	array_push($columns,$model . "." . $name);
                }
		return $columns;
	}


}
?>
