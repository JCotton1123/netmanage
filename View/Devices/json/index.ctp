<?php

echo $this->DataTables->render(function($view, $outputRow, $rawRow) {

    $id = $rawRow['Device']['id'];
    $name = $rawRow['Device']['name'];
    $ipAddr = $rawRow['Device']['ip_addr'];
    $model = $rawRow['Device']['model'];
    $serial = $rawRow['Device']['serial'];

    $newOutputRow = array(
        $view->Html->link($name, "/devices/view/${id}"),
        $view->Html->link($ipAddr, "ssh://${ipAddr}"),
        $model,
        $serial
    );

    return $newOutputRow;
});
