<?php
	//Load additional JS libs/scripts for generating graphs
	$this->Html->script('/js/raphael/raphael', array('inline' => false));
	$this->Html->script('/js/raphael/g.raphael-min', array('inline' => false));
	$this->Html->script('/js/raphael/g.pie-min.js', array('inline' => false));
?>


<?php
//@Report
echo $this->Section->create(array(
	'id' => 'report',
	'title' => 'Device/Model Firmware Summary',
	'toggle_min_max' => false
));
?>

<div style="width:550px; margin:auto;">
	<div id="piechart" style="margin:auto;"></div>
</div>

<script>
<?php
echo $this->Raphael->pie(
   array(
	'content_area' => 'piechart',
	'center_x' => 140,
	'center_y' => 150,
	'radius' => 120,
	'legend_location' => 'east'
   ),
   $chart_legend,
   $chart_data
);
?>
   
</script>

<table class="datatable display">
	<thead>
	   <tr>
		<th>Percentage</th>
		<th>Device Count</th>
		<th>Model</th>
		<th>Firmware</th>
	   </tr>
	</thead>
	<tbody>
	<?php foreach($report_results as $row) { ?>
	   <tr>
		<td><?php echo $row[0]['percent']; ?></td>
		<td><?php echo $row[0]['device_count']; ?></td>
		<td><?php echo $row['device_model']['model']; ?></td>
		<td><?php echo $row['device_firmware']['firmware']; ?></td>
	   </tr>
	<?php } ?>
	</tbody>
</table>

<?php
echo $this->Section->end();
//@Report
?>



