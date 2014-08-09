<?php

echo $this->DataTables->render(function($view, $outputRow, $rawRow){

  $id = $rawRow['DeviceAttrOid']['id'];

  $outputRow['DT_RowId'] = $id;

/*
  $outputRow[0] = <<<EOD
<a
 href="/device_attr_oids/delete/${id}"
 onclick="if(confirm('Click OK to confirm you would like to delete this entry.')) { return true; } return false;"
 style="margin-right:10px;"
 >
  <span class="glyphicon glyphicon-trash"></span>
</a>
${outputRow[0]}
EOD;
*/

  return $outputRow;
});
