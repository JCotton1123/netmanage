<?php

echo $this->DataTables->render(function($view, $outputRow, $rawRow) {

    $deviceId = $rawRow['Device']['id'];
    $deviceName = $rawRow['Device']['name'];

    $outputRow[1] = $view->Html->link($deviceName, "/devices/view/${deviceId}");
    return $outputRow;
});
