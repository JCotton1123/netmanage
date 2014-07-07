<style>
  .signin {
    max-width: 250px;
    margin: auto;
  }
  .signin * {
    width: 100%;
  }
  .signin h2 {
    text-align: center;
  }
</style>
<div class="signin">
<h2>NetManage</h2>
<?php
    echo $this->Form->create('User');
    echo $this->Form->input('username', array(
      'label' => false,
      'placeholder' => 'Username'
    ));
    echo $this->Form->input('password', array(
      'label' => false,
      'placeholder' => 'Password'
    ));
    echo $this->Form->button('Login', array(
      'type' => 'submit',
      'class' => 'btn btn-primary'
    ));
    echo $this->Form->end();
?>
</div>
