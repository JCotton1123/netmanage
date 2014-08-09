<div class="alerts">
  <?php echo $this->element('alerts'); ?>
</div>
<div class="content-header">
  <div class="col-md-8">
    <h1><?php echo $this->fetch('title'); ?></h1>
  </div>
  <div class="col-md-4">
    <div class="pull-right">
      <?php echo $this->fetch('actions'); ?>
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="clearfix"></div>
</div>
<div class="content col-md-12">
  <?php echo $this->fetch('content'); ?>
</div>
