CREATE TABLE `cron_jobresults` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cron_job_id` int(11) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Result status',
  `message` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Result message',
  `log` text COMMENT 'log output',
  `timestamp` int(11) unsigned DEFAULT NULL COMMENT 'execution timestamp',
  `client_ip` varchar(46) DEFAULT NULL COMMENT 'client IP address',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cron_jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Unique task alias',
  `class` varchar(255) NOT NULL COMMENT 'CronTask URL in CakePHP requestAction Format',
  `desc` varchar(255) DEFAULT NULL COMMENT 'Name of CronTask',
  `interval` int(11) NOT NULL DEFAULT '0' COMMENT 'Interval of execution in seconds',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Active Flag',
  `last_status` tinyint(4) DEFAULT NULL COMMENT 'Last execution status',
  `last_message` varchar(255) DEFAULT NULL COMMENT 'Last execution message',
  `last_executed` int(11) DEFAULT NULL COMMENT 'Last execution date',
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
