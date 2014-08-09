<?php

  $type = ($type == 'error') ? 'danger' : $type;

  $classes = array(
    "alert",
    "alert-${type}",
  );
  if($dismissable)
    $classes[] = "alert-dismissable";
?>
<div class="<?php echo implode(' ', $classes); ?>" role="alert">
  <?php if($dismissable) { ?>
    <button type="button" class="close" data-dismiss="alert">
      <span aria-hidden="true">&times;</span>
      <span class="sr-only">Close</span>
    </button>
  <?php } ?>
  <?php echo $message; ?>
</div>
