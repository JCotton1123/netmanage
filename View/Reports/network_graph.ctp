<?php

/*
 * Generate our nodes and edges so we can create a graph
 */

//Build nodes array
$nodes = array();
$nodeIds = array();
foreach($devices as $device){

    $id = $device['Device']['mgmt_ip_addr'];
    $label = $device['Device']['name'];
    $deviceDescr = <<<EOD
<ul>
  <li>{$device['Device']['name']}</li>
  <li>{$device['Device']['ip_addr']}</li>
  <li>{$device['Device']['model']}</li>
</ul>
EOD;

    $nodes[] = array(
        'id' => $id,
        'label' => $label,
        'title' => $deviceDescr
    );

    $nodeIds[$id] = true;
}

//Build edges array
$edges = array();
$existingEdges = array();
foreach($devices as $device){
    $localIpAddr = $device['Device']['mgmt_ip_addr'];
    foreach($device['DeviceNeighbor'] as $neighbor){
        $neighborIpAddr = $neighbor['mgmt_ip_addr'];
        if(!isset($nodeIds[$neighborIpAddr]))
            continue;
        $edgeHash = ($localIpAddr < $neighborIpAddr) ? "$localIpAddr|$neighborIpAddr" : "$neighborIpAddr|$localIpAddr";
        if(!isset($existingEdges["$edgeHash"])){
            $existingEdges["$edgeHash"] = true; 
            $edges[] = array(
                'from' => $localIpAddr,
                'to' => $neighborIpAddr
            );
        }
    }
}
?>
<section>
  <h2>Network Topology Graph</h2>
  <div class="vis-graph"
    data-nodes='<?php echo json_encode($nodes); ?>'
    data-edges='<?php echo json_encode($edges); ?>'
    data-options='{"stablize":false}'>
  </div>
</section>
