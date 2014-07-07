<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Settings');
?>
<?php
foreach($settings as $group => $groupSettings){
  //Remove the priority from the group
  $group = preg_replace('/^[0-9]+-/', '', $group);
  //To human friendly name
  $group = ucwords(str_replace('_', ' ', $group));
  ?>
  <section>
    <h3><?php echo $group; ?></h3>
    <form role="form-group" method="post">
      <?php foreach($groupSettings as $setting){
        $fieldName = str_replace('.','__',$setting['full_name']);
        $shortName = $setting['short_name'];
        $value = $setting['value'];
        $descr = $setting['description'];
        ?>
        <div class="form-group">
          <label
            for="<?php echo $fieldName; ?>"
            title="<?php echo $descr; ?>">
              <?php echo $shortName; ?>
          </label>
          <input
            class="form-control" 
            type="text"
            id="<?php echo $fieldName; ?>"
            name="<?php echo $fieldName; ?>"
            value="<?php echo $value; ?>"
          />
        </div>
      <?php } ?>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
  </section>
<?php } ?>
