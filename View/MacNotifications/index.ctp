<?php
//@Mac Notifications
echo $this->Section->create(array(
	'id' => 'mac_notifs',
	'title' => 'Mac Notifications',	
	'toggle_min_max' => false
));
?>

	<div style="margin-bottom:10px; padding:10px; border:1px solid orange; border-radius:6px; background-color:#EECBAD;">
        	<span style="font-weight:bold">Port is not always representative of the physical port a device is attached to.
		Always run "sh mac address-table address <i>mac-address</i>" to verify the port.</span>
	</div>

	<fieldset class="inline_input" style="margin-bottom:10px;">
	<legend>Find</legend>
		<?php
			echo $this->Form->create('MacNotification', array('action' => 'filter'));
			echo $this->Form->input('mac_address', array('div' => 'inline_input'));
			echo $this->Form->submit('Find', array('div' => 'inline_input_button'));
			echo $this->Form->end();
		?>
		<?php
			echo $this->Form->create('MacNotification', array('action' => 'index'));
			echo $this->Form->submit('Clear Filter', array('div' => 'inline_input_button'));
			echo $this->Form->end();
		?>
	</fieldset>

	<?php
	echo $this->Table->table(
		array(
			'timestamp',
			'mac_addr' => array('display' => 'Mac Address'),
			'type',
			'host',
			'port',
			'vlan'
		),
		'MacNotification',
		$mac_notifications,
		array(
			'table_class' => 'standard'
		)
	);

	?>

	<?php echo $this->element('pagination_navigation'); ?>

<?php
echo $this->Section->end();
//@Mac Notifs
?>

