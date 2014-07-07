<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Nagios Export');
?>

<ol class="breadcrumb">
  <li><?php echo $this->Html->link('Reports', '/reports'); ?></li>
  <li class="active">Nagios Export</li>
</ol>

<section>
  <h3>Host Declarations</h3>
  <textarea></textarea>
</section>

<section>
  <h3>Hostgroup Declarations</h3>
  <textarea></textarea>
</section>
