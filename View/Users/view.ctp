<?php
  $id = $user['User']['id'];
  $name = $user['User']['name'];
  $firstName = $user['User']['first_name'];
  $lastName = $user['User']['last_name'];
  $username = $user['User']['username'];
  $role = $user['Role']['name'];
  $created = $user['User']['created'];
?>

<?php
  $this->extend('/Common/default');
  $this->assign('title', $name);
?>

<?php $this->start('actions');
  echo $this->ActionMenu->menu(array(
    'Reset Password' => array(
      'url' => "/users/reset_password/${id}",
      'enabled' => in_array($__userRole, array('administrator'))
    ),
    'Edit' => array(
      'url' => "/users/edit/${id}",
      'enabled' => in_array($__userRole, array('administrator'))
    ),
    'Delete' => array(
      'url' => "/users/delete/${id}",
      'confirm' => 'Click OK to confirm you would like to delete this user',
      'enabled' => in_array($__userRole, array('administrator'))
    )
  ));
$this->end(); ?>

<ol class="breadcrumb">
  <li><?php echo $this->Html->link('Users', '/users'); ?></li>
  <li class="active"><?php echo $name; ?></li>
</ol>

<section>
  <h3>Attributes</h3>
  <table class="table">
    <tr>
      <td>First Name</td>
      <td><?php echo $firstName; ?></td>
    </tr>
    <tr>
      <td>Last Name</td>
      <td><?php echo $lastName; ?></td>
    </tr>
    <tr>
      <td>Username</td>
      <td><?php echo $username; ?></td>
    </tr>
    <tr>
      <td>Role</td>
      <td><?php echo $role; ?></td>
    </tr>
    <tr>
      <td>Created</td>
      <td><?php echo $created; ?></td>
    </tr>
  </table>
</section> <!-- /.attributes -->

