<?php

/**
* Helper to assist with the display of table data
* written by cottonj
*/

App::uses('AppHelper', 'View/Helper');

class TableHelper extends AppHelper {
    
    public $helpers = array('Html');

	public function table($columns,$model,$data,$options=array()){
	
		$result_html = "";
	
		if(empty($data)){
		
			$css_class = 'no_results';
			$msg = 'No results found';
		
			if(array_key_exists('no_results',$options)){
				
				if(array_key_exists('css_class',$options['no_results'])){
					$css_class = $options['no_results']['css_class'];
				}
				
				if(array_key_exists('msg',$options['no_results'])){
					$msg = $options['no_results']['msg'];
				}
			}
		
			$result_html = "<div class=\"${css_class}\"><span>${msg}</span></div>";
		}
		else {

			//Convert column input into struct that is easier to parse
			$column_struct = array();
			foreach($columns as $key => $val){
				if(is_array($val)){
					$column_struct[$key] = $val;
				}
				else
					$column_struct[$val] = array();
			}

			if(array_key_exists('table_class',$options)){
				$table_class = $options['table_class'];
				$result_html = "<table class=\"${table_class}\">";
			}
			else
				$result_html = "<table>";
			
			//Build table header
			$result_html .= "<thead><tr>";
			foreach($column_struct as $col_name => $col_options){

				$result_html .= "<th>";
				
				if(array_key_exists('display',$col_options))
					$result_html .= $col_options['display'];
				else
					$result_html .= ucfirst($col_name);
				
				$result_html .= "</th>";
			}
			$result_html .= "</tr></thead>";
			
			//Build table body
			$result_html .= "<tbody>";
			$alt = 0; //Track alternate rows
			foreach($data as $row){
				
				if($alt++ % 2 == 0)
					$result_html .= "<tr class=\"alt\">";
				else
					$result_html .= "<tr>";
				
				foreach($column_struct as $col_name => $col_options){
					
					$result_html .= "<td>";

					if(array_key_exists('custom',$col_options)){
						
						$col_val = $col_options['custom'];
						
						if(array_key_exists('var',$col_options)) {

							if($model === false)
								$col_val = str_replace('{$VAR$}',$row[$col_options]['td']['var']);
							else
								$col_val = str_replace('{$VAR$}',$row[$model][$col_options['td']['var']]); 
						}
						
						$result_html .= $col_val;
					}
					else {
						if($model === false)
							$result_html .= $row[$col_name];
						else
							$result_html .= $row[$model][$col_name];
					}
					$result_html .= "</td>";
				}
				$result_html .= "</tr>";
			}
			$result_html .= "</tbody>";
			$result_html .= "</table>";
		}

		return $result_html;
	}
}
