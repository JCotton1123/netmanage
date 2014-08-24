<?php

echo $this->DataTables->render(function($view, $outputRow, $rawRow) {

    $id = $rawRow['Device']['id'];
    $name = $rawRow['Device']['name'];
    $ipAddr = $rawRow['Device']['friendly_ip_addr'];
    $model = $rawRow['Device']['model'];
    $serial = $rawRow['Device']['serial'];

    $newOutputRow = array(
        "<a href='#' class='action add' data-id='${id}' data-name='${name}'>Add</a>",
        $name,
        $ipAddr,
        $model,
        $serial
    );

    return $newOutputRow;
});
