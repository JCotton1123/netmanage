# Netmanage

A Cisco-centric open source network management solution, NetManage was designed to increase the efficency of a network team and provide the technical means to institute various best practices.

NetManage combines features from tools like [RANCID](http://www.shrubbery.net/rancid/), [NetDisco](http://www.netdisco.org/), and others into a more user-friendly and powerful tool that is easy to setup.

## Feature Set

* A clean and responsive control panel
* Configuration & change management
* Configuration backups
* Log aggregation and notifications
* Various reports
* Automatic device discovery via CDP

## Install

[NetManage Bootstrap](https://github.com/JCotton1123/netmanage-bootstrap) provides the tools to easily deploy an instance of NetManage including spinning up and configuring the infrastructure and deploying the application code contained here.

## Device Configurations

You must enable certain configurations on your Cisco gear to enable the features NetManage provides.

### Logging

```
logging trap notifications
logging facility local5
logging <netmanage server address>
```

### Change management

```
archive
 log config
  logging enable
  logging size 200
  notify syslog contenttype plaintext
  hidekeys
```

### SNMP traps

```
snmp-server host <netmanage server address> version 2c <snmp community> <snmp trap> <snmp trap>
```

#### Mac Notifications

From Cisco's documentation, "The SNMP ifIndex persistence feature provides an interface index (ifIndex) value that is retained and used when the router reboots. The ifIndex value is a unique identifying number associated with a physical or logical interface".

```
snmp ifmib ifindex persist
```

Enable mac notifications globally.

```
snmp-server enable traps mac-notification change
mac address-table notification change interval 5
mac address-table notification change history-size 100
mac address-table notification change
```

Instruct your device to log mac notifications to NetManage.

```
snmp-server host <netmanage server address> version 2c <snmp community> mac-notitication <snmp trap> <snmp trap> ...
```

Set the following on all **access** ports.

```
int range Fa0/1 - X
 snmp trap mac-notification change added
 snmp trap mac-notification change removed
!
```

### Software upgrades

Allow NetManage to reboot your device via SNMP

```
snmp-server system-shutdown
```

## To Do

* User mgmt
* Configuration mgmt
* Software mgmt

## Notes

Get SNMP OID
`snmptranslate -On $(snmptranslate -IR cdpCacheAddress)`
