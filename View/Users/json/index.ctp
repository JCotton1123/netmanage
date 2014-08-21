<?php

echo $this->DataTables->render(function($view, $outputRow, $rawRow) {

    $id = $rawRow['User']['id'];
    $name = $rawRow['User']['name'];

    $outputRow[0] = $view->Html->link($name, "/users/view/${id}");

    return $outputRow;
});
