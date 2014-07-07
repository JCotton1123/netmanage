"name","ip address","model","serial","firmware"
<?php foreach($devices as $device) {
  $deviceFields = array(
    $device['Device']['name'],
    $device['Device']['ip_addr'],
    $device['Device']['model'],
    $device['Device']['serial'],
    $device['Device']['firmware']
  );
  $deviceFields = array_map("trim", $deviceFields);
  $deviceFields = array_map("addslashes", $deviceFields);
  echo "\"" . implode('","', $deviceFields) . "\"\n";
}
