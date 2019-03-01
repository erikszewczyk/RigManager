CREATE DATABASE `rigmanager` /*!40100 DEFAULT CHARACTER SET latin1 */;

CREATE TABLE `exchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pair` varchar(8) NOT NULL,
  `val` float NOT NULL,
  `lastupd` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE `rigstats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dtm` datetime DEFAULT CURRENT_TIMESTAMP,
  `userid` varchar(64) NOT NULL,
  `worker` varchar(45) NOT NULL,
  `pool` varchar(45) DEFAULT NULL,
  `miner` varchar(45) DEFAULT NULL,
  `algo` varchar(45) DEFAULT NULL,
  `hashrate` bigint(14) DEFAULT NULL,
  `diff` bigint(14) DEFAULT NULL,
  `watts` int(6) DEFAULT NULL,
  `temp` int(3) DEFAULT NULL,
  `coin_revenue` decimal(12,2) DEFAULT NULL,
  `fiat_revenue` decimal(12,2) DEFAULT NULL,
  `btc_revenue` decimal(8,8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=423076 DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(64) NOT NULL,
  `mpmaddress` varchar(64) DEFAULT NULL,
  `subscription` varchar(16) DEFAULT 'standard',
  `retention_period` int(4) DEFAULT '30',
  `fiat` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`,`userid`),
  UNIQUE KEY `userid_UNIQUE` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

CREATE TABLE `workers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `worker` varchar(45) NOT NULL,
  `workerkey` varchar(45) DEFAULT NULL,
  `userid` varchar(64) NOT NULL,
  `lastupd` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
