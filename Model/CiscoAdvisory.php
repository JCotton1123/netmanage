<?php
class CiscoAdvisory extends AppModel {

	public $useTable = false;
	public $useDbConfig = 'cisco_advisories_rss';

	public function afterFind($results){

	    $formatted_results = array();

	    foreach($results as $result){	

		$descr = trim(strip_tags($result['CiscoAdvisory']['description']));
		$descr = htmlspecialchars($descr);

		if($descr == "")
			$descr = "-- No description provided --";

		$result['CiscoAdvisory']['description'] = $descr;

		array_push($formatted_results,$result);
	    }

	    return $formatted_results;
	}

}
?>
