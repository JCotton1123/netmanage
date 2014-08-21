<?php
  $id = $user['User']['id'];
  $name = $user['User']['name'];
?>

<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Reset Password');
?>

<ol class="breadcrumb">
  <li><?php echo $this->Html->link('Users', '/users'); ?></li>
  <li><?php echo $this->Html->link($name, "/users/view/${id}"); ?></li>
  <li class="active">Reset Password</li>
</ol>

<section>
  <form method="post" role="form" />
    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control" name="password" />
    </div>
    <div class="form-group">
      <label>Confirm Password</label>
      <input type="password" class="form-control" name="confirm_password" />
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
  </form>
</section>
