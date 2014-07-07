<?php

echo $this->DataTables->render(function($view, $outputRow, $rawRow){

  //Timestamp w/ link to full config
  $configId = $rawRow['DeviceConfig']['id'];
  $outputRow[0] = $view->Html->link(
    $outputRow[0],
    "/configs/view/${configId}"
  );

  $outputRow[1] = "<pre>${outputRow[1]}</pre>";
  return $outputRow;
});
