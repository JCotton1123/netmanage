# Device Configurations

You must enable certain configurations on your Cisco gear to support the features NetManage provides.

## Log Aggregation & Change Configuration

```
logging trap notifications
logging facility local5
logging <netmanage server address>
```

```
archive
 log config
  logging enable
  logging size 200
  notify syslog contenttype plaintext
  hidekeys
```

## Client Locations

```
snmp ifmib ifindex persist
```

```
snmp-server enable traps mac-notification change
mac address-table notification change interval 5
mac address-table notification change history-size 100
mac address-table notification change
```

```
snmp-server host <netmanage server address> version 2c <snmp community> mac-notitication <snmp trap> <snmp trap> ...
```

On all **access** ports:

```
int range Fa0/1 - X
 snmp trap mac-notification change added
 snmp trap mac-notification change removed
!
```

## Software upgrades

Allow NetManage to reboot your device via SNMP

```
snmp-server system-shutdown
```


