-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: netmanage
-- ------------------------------------------------------
-- Server version	5.1.73

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `client_ports`
--

DROP TABLE IF EXISTS `client_ports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_ports` (
  `mac_addr` bigint(20) unsigned NOT NULL,
  `device_ip_addr` int(11) unsigned NOT NULL,
  `port` int(11) unsigned NOT NULL,
  `vlan` int(4) unsigned NOT NULL,
  `up_down` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_ports`
--

LOCK TABLES `client_ports` WRITE;
/*!40000 ALTER TABLE `client_ports` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_ports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_attr_oids`
--

DROP TABLE IF EXISTS `device_attr_oids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_attr_oids` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sys_object_id` varchar(512) NOT NULL,
  `name` varchar(255) NOT NULL,
  `oid` varchar(512) NOT NULL,
  `filter` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_attr_oids`
--

LOCK TABLES `device_attr_oids` WRITE;
/*!40000 ALTER TABLE `device_attr_oids` DISABLE KEYS */;
INSERT INTO `device_attr_oids` VALUES (1,'1.3.6.1.4.1.9','name','1.3.6.1.2.1.1.5.0','/^([^.]+)\\./'),(2,'1.3.6.1.4.1.9','serial','1.3.6.1.4.1.9.3.6.3.0',NULL),(3,'1.3.6.1.4.1.9','firmware','1.3.6.1.4.1.9.2.1.73.0','/\\/?([^:\\/]+)\\.bin$/'),(4,'1.3.6.1.4.1.9','model','1.3.6.1.2.1.47.1.1.1.1.13.1001',NULL),(5,'1.3.6.1.4.1.9.1.283','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(6,'1.3.6.1.4.1.9.1.429','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(7,'1.3.6.1.4.1.9.1.366','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(8,'1.3.6.1.4.1.9.1.367','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(9,'1.3.6.1.4.1.9.1.876','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(10,'1.3.6.1.4.1.9.5.59','serial','1.3.6.1.4.1.9.5.1.2.19.0',NULL),(11,'1.3.6.1.4.1.9.5.59','firmware','1.3.6.1.4.1.9.5.1.1.38.0','/\\/?([^:\\/]+)\\.bin$/'),(12,'1.3.6.1.4.1.9.5.59','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(13,'1.3.6.1.4.1.9.1.427','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(14,'1.3.6.1.4.1.9.1.559','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(15,'1.3.6.1.4.1.9.1.283','firmware','1.3.6.1.4.1.9.2.1.73.0','/^sup-bootflash:(.+)\\.bin$/'),(16,'1.3.6.1.4.1.9.1.1011','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(17,'1.3.6.1.4.1.9.1.1358','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL),(18,'1.3.6.1.4.1.9.1.1525','model','1.3.6.1.2.1.47.1.1.1.1.13.1',NULL);
/*!40000 ALTER TABLE `device_attr_oids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_configs`
--

DROP TABLE IF EXISTS `device_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_configs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_ip_addr` int(11) unsigned NOT NULL,
  `config` text NOT NULL,
  `diff` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `device_configs_device_ip_addr_idx` (`device_ip_addr`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_configs`
--

LOCK TABLES `device_configs` WRITE;
/*!40000 ALTER TABLE `device_configs` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_logs`
--

DROP TABLE IF EXISTS `device_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_ip_addr` int(11) unsigned NOT NULL,
  `timestamp` datetime NOT NULL,
  `fac_sev_mnem` varchar(255) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`),
  KEY `device_logs_timestamp_idx` (`timestamp`),
  KEY `device_logs_device_ip_addr_idx` (`device_ip_addr`),
  KEY `device_logs_fac_sev_mnem_idx` (`fac_sev_mnem`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_logs`
--

LOCK TABLES `device_logs` WRITE;
/*!40000 ALTER TABLE `device_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_neighbors`
--

DROP TABLE IF EXISTS `device_neighbors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_neighbors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `device_ip_addr` int(11) unsigned NOT NULL,
  `neighbor_ip_addr` int(11) unsigned NOT NULL,
  `local_port` varchar(75) DEFAULT NULL,
  `neighbor_name` varchar(255) DEFAULT NULL,
  `neighbor_port` varchar(75) DEFAULT NULL,
  `neighbor_platform` varchar(255) DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `device_neighbors_device_ip_addr_idx` (`device_ip_addr`),
  KEY `device_neighbors_neighbor_ip_idx` (`neighbor_ip_addr`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_neighbors`
--

LOCK TABLES `device_neighbors` WRITE;
/*!40000 ALTER TABLE `device_neighbors` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_neighbors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` int(11) unsigned NOT NULL,
  `ip_addr` int(11) unsigned NOT NULL,
  `sys_object_id` varchar(512) NOT NULL,
  `name` text,
  `serial` text,
  `firmware` text,
  `model` text,
  `last_seen` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `devices_ip_addr_idx` (`ip_addr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metrics`
--

DROP TABLE IF EXISTS `metrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metrics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `var` varchar(255) NOT NULL,
  `val` text,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dashboard_stats_var_idx` (`var`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metrics`
--

LOCK TABLES `metrics` WRITE;
/*!40000 ALTER TABLE `metrics` DISABLE KEYS */;
/*!40000 ALTER TABLE `metrics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `netmanage_logs`
--

DROP TABLE IF EXISTS `netmanage_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `netmanage_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT 'info',
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `netmanage_logs`
--

LOCK TABLES `netmanage_logs` WRITE;
/*!40000 ALTER TABLE `netmanage_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `netmanage_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'administrator','Can perform all actions','2014-08-21 01:13:29','2014-08-21 01:13:29'),(2,'user','Restricted to view only actions','2014-08-21 01:13:29','2014-08-21 01:13:29');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `var` varchar(255) NOT NULL,
  `val` text,
  `description` text,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `setings_var_idx` (`var`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (12,'global.snmp_community','public','The SNMP read/write community NetManage will use to interface with your devices.',NULL,NULL),(13,'global.snmp_timeout','3000000','The number of microseconds NetManage will wait for an SNMP response from a device.',NULL,NULL),(14,'global.snmp_retry','5','The number of times NetManage will retry an SNMP operation after a timeout has occurred.',NULL,NULL),(15,'discovery.seeds','127.0.0.1','A comma seperated list of devices NetManage will seed the discovery process with.',NULL,NULL),(16,'discovery.ip_blacklist',NULL,'If this setting is defined, NetManage will not discover devices matching this PCRE filter. This filter supersedes the whitelist filter.',NULL,NULL),(17,'discovery.ip_whitelist','/^.*$/','If this setting is defined, NetManage will only discover devices matching this PCRE filter',NULL,NULL),(18,'device_logging.max_logs_before_term','200','An instance of the LogShell will process this many logs before terminating. The syslog daemon will start a new instance of the LogShell after termination automatically. This is meant as a workaround to address memory leaks.',NULL,NULL),(19,'device_logging.enable_notifications','0','A boolean indicating whether device log messages should be sent out as notifications. Before you enable this you should set the notification filter.',NULL,NULL),(20,'device_logging.notification_recipients','root@netmanage.local','Device log notifications will be sent to this comma separated list of recipients',NULL,NULL),(21,'device_logging.notification_filter','/(LINEPROTO-5-UPDOWN)|(LINK-3-UPDOWN)|(VQPCLIENT-3-THROTTLE)|(VQPCLIENT-3-VLANNAME)|(SYS-2-MALLOCFAIL)|(ILPOWER-5-POWER_GRANTED)|(ILPOWER-5-IEEE_DISCONNECT)|(LINK-5-CHANGED)|(DOT1X-5)|(AUTHMGR-5)|(MAB-5)|(ILPOWER-3-CONTROLLER_PORT_ERR)/','Device logs matching this PCRE filter will not be sent as notifications.',NULL,NULL),(22,'device_logging.storage_filter','/(LINEPROTO-5-UPDOWN)|(LINK-3-UPDOWN)|(LINK-5-CHANGED)/','Logs matching the supplied PCRE filter will not be stored.',NULL,NULL),(23,'tftp.root','/var/lib/tftpboot','Local tftp directory root',NULL,NULL),(24,'tftp.address','127.0.0.1','The local address the tftp process is listening on',NULL,NULL),(25,'config_backup.worker_count','4','Controls the number of workers that are spawned to retrieve your devices configurations.',NULL,NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_username_idx` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'admin','$2a$10$X8e1lXBqBlUZkpEuMT1adul6C2P/it.M7ECZBg5MXjdLGJxVz3/VG','','Builtin','Administrator','2014-08-20 19:47:34','2014-07-12 21:12:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-21 15:49:07
