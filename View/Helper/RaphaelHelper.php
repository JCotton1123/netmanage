<?php

/**
* Helper to assist with Raphael graphing
* written by cottonj
*/

App::uses('AppHelper', 'View/Helper');

class RaphaelHelper extends AppHelper {
    
    public $helpers = array('Html');

	public function pie($options,$legend,$data){

		$result_html = "";

		$content_area = $options['content_area'];
		$center_x = $options['center_x'];
		$center_y = $options['center_y'];
		$radius = $options['radius'];
		$legend_location = $options['legend_location'];
		

		//Link chart to content area
		$result_html .= "var r = Raphael(\"${content_area}\"),";
	
		//Build chart
		$result_html .= "pie = r.piechart(${center_x},${center_y},${radius},";
		$result_html .= "[" . implode(",",$data) . "],";
		$result_html .= "{ legend: [\"" . implode("\",\"",$legend) . "\"], ";
		$result_html .= "legendpos: \"${legend_location}\"}";
		$result_html .=");";

		
		//Add chart animations
		$result_html .= <<<EOD
                pie.hover(function () {
                    this.sector.stop();
                    this.sector.scale(1.025, 1.025, this.cx, this.cy);

                    if (this.label) {
                        this.label[0].stop();
                        this.label[0].attr({ r: 7.5 });
                        this.label[1].attr({ "font-weight": 800 });
                    }
                }, function () {
                    this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");

                    if (this.label) {
                        this.label[0].animate({ r: 5 }, 500, "bounce");
                        this.label[1].attr({ "font-weight": 400 });
                    }
                });
EOD;
		

		return $result_html;
	}
}
