<?php

echo $this->DataTables->render(function($view, $outputRow, $rawRow) {

    $neighborIpAddr = $rawRow['DeviceNeighbor']['neighbor_ip_addr'];
    $outputRow[0] = $view->Html->link($outputRow[0], "/devices/view/${neighborIpAddr}");

    return $outputRow;
});
