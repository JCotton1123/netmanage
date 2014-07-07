<?php

/**
* Helper to assist with the display of a section
* written by cottonj
*/

App::uses('AppHelper', 'View/Helper');

class SectionHelper extends AppHelper {
    
    public $helpers = array('Html');

	public function create($params){

		//Params
                $id = $params['id'];
                $title = ucfirst((array_key_exists('title',$params) ? $params['title'] : $id));
                $section_div_css_classes = "section" . (array_key_exists('css_classes',$params) ? " " . implode(' ',$params['css_classes']) : "");
                $toggle_min_max = (array_key_exists('toggle_min_max',$params) ? $params['toggle_min_max'] : true);
		$default_min_max = (array_key_exists('default_min_max',$params) ? $params['default_min_max'] : 'max');
		$new_win = (array_key_exists('new_win',$params) ? $params['new_win'] : false);

                $result_html = "";

                //Build parent div
                $result_html .= "<div id=\"${id}\" class=\"${section_div_css_classes}\" >";

                //Build title div
		if($toggle_min_max === false)
                	$result_html .= "<div class=\"title\">";
		else
			$result_html .= "<div class=\"title cursor-hand\" onClick=\"toggleSection('${id}');\">";
                $result_html .= "<span class=\"ui-icon ui-icon-document\" style=\"float:left;margin-right:4px;\"></span>";
                $result_html .= "<span class=\"title\">${title}</span>";
                if($toggle_min_max){

			$icon = "minus";
			if($default_min_max == 'min')
				$icon = "plus";

                        $result_html .= "<span " .
                                "class=\"min_max_sec section-nav-icon ui-icon ui-icon-circle-${icon}\" " .
                                "style=\"float:right;\"> " .
                        "</span>";
                }
		if($new_win !== false){
			$result_html .= "<a href=\"${new_win}\" class=\"section-nav-icon ui-icon ui-icon-extlink\"></a>"; 
		}
                $result_html .= "</div>";

		//Start of content div
		if($toggle_min_max && $default_min_max == 'min')
			$result_html .= "<div class=\"content\" style=\"display:none;\">";
		else
			$result_html .= "<div class=\"content\">";

		return $result_html;
	}

	public function end(){

		$result_html = "";

		//Close content div
		$result_html .= "</div> <!-- /content -->";

		//Close section div
                $result_html .= "</div> <!-- /section -->";

                return $result_html;	
	}

}
