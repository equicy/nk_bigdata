<?php

/**
 * App Global Config.
 */
$_CONFIG = [
    'ioport_ranking_number' => 3,
    'tourist_attraction_ranking_number' => 3,
    'PASSWORD_SALT' => 'E$z8',
    'PERCENT_RADIX' => 1000000
];

/*use bigdata;
create table `tj_inbound` (
`id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`year` VARCHAR(4) NOT NULL,
	`month` VARCHAR(2) NOT NULL,
	`team_number` INT UNSIGNED NOT NULL DEFAULT 0,
	`people_number` INT UNSIGNED NOT NULL DEFAULT 0,
	`lastmonth_people_number` INT UNSIGNED NOT NULL DEFAULT 0,
	`entryport_team_number` VARCHAR(1000) NOT NULL DEFAULT '',
	`inbound` VARCHAR(10000) NOT NULL DEFAULT '',
	`modify_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE = InnoDB DEFAULT CHARSET = utf8;*/