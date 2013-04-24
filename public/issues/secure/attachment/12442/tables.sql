CREATE TABLE `ctr_details` (
  `contract_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` tinyint(3) unsigned NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`contract_id`),
  KEY `branch_idx` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ctr_tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` int(10) unsigned NOT NULL,
  `tag` varchar(30) NOT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `contract_id_idx` (`contract_id`),
  KEY `tag_idx` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ctr_files` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` int(10) unsigned NOT NULL,
  `file_name` varchar(250) NOT NULL,
  `file_size` int(10) unsigned NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `contract_id_idx` (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;