<ul id="alerts">
<?php
  echo $this->Session->flash('flash');
  echo $this->Session->flash('error');
  echo $this->Session->flash('warning');
  echo $this->Session->flash('success');
?>
</ul>
