<?php

echo $this->DataTables->render(function($view, $outputRow, $rawRow) {

    $id = $rawRow['Device']['id'];
    $name = $rawRow['Device']['name'];
    $ipAddr = $rawRow['Device']['friendly_ip_addr'];
    $model = $rawRow['Device']['model'];
    $serial = $rawRow['Device']['serial'];

    //Ensure name always has a value since a name is required
    //to drill down into a device's details.
    //A device might not have a name if discovery failed to
    //get that attribute (b/c the correct OID hasn't been set).
    if(empty($name))
        $name = 'Unknown';

    $newOutputRow = array(
        $view->Html->link($name, "/devices/view/${id}"),
        $view->Html->link($ipAddr, "ssh://${ipAddr}"),
        $model,
        $serial
    );

    return $newOutputRow;
});
