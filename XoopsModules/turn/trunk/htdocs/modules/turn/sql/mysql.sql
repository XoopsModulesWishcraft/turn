CREATE TABLE `turn_books` (
  `bid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pids` varchar(1000) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `reference` varchar(128) DEFAULT 'turn_%id%_',
  `description` varchar(500) DEFAULT NULL,
  `language` varchar(64) DEFAULT 'english',
  `default` int(2) DEFAULT '0',
  `pages` int(10) DEFAULT '0',
  `background_colour` varchar(7) DEFAULT '#ccc',
  `path` varchar(255) DEFAULT 'turn',
  `filename` varchar(255) DEFAULT NULL,
  `filetype` enum('_MI_TURN_FILETYPE_JPG','_MI_TURN_FILETYPE_PNG','_MI_TURN_FILETYPE_GIF','_MI_TURN_FILETYPE_SWF') DEFAULT '_MI_TURN_FILETYPE_JPG',
  `extension` varchar(5) DEFAULT 'jpg',
  `width` int(10) unsigned DEFAULT '0',
  `height` int(10) unsigned DEFAULT '0',
  `size` int(10) unsigned DEFAULT '0',
  `bookWidth` int(5) unsigned DEFAULT '640',
  `bookHeight` int(5) DEFAULT '640',
  `uid` int(13) unsigned DEFAULT '0',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  `actioned` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`bid`),
  KEY `COMMON` (`pids`(100),`name`(50),`bid`,`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `turn_page_links` (
  `lid` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned DEFAULT '0',
  `pids` varchar(500) DEFAULT NULL,
  `page` int(10) unsigned DEFAULT '0',
  `uid` int(13) unsigned DEFAULT '0',
  `created` int(13) unsigned DEFAULT '0',
  `updated` int(13) unsigned DEFAULT '0',
  PRIMARY KEY (`lid`),
  KEY `COMMON` (`bid`,`pids`(50),`page`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `turn_pages` (
  `pid` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) unsigned DEFAULT '0',
  `lid` int(20) unsigned DEFAULT '0',
  `page` int(5) unsigned DEFAULT '0',
  `mode` enum('_MI_TURN_MODE_HTML','_MI_TURN_MODE_LANDSCAPE','_MI_TURN_MODE_PORTTRAIT') DEFAULT NULL,
  `location` enum('_MI_TURN_PATH_XOOPS_UPLOAD_PATH','_MI_TURN_PATH_XOOPS_VAR_PATH','_MI_TURN_PATH_OTHER') DEFAULT '_MI_TURN_PATH_XOOPS_UPLOAD_PATH',
  `path` varchar(255) DEFAULT 'turn',
  `filename` varchar(255) DEFAULT NULL,
  `filetype` enum('_MI_TURN_FILETYPE_JPG','_MI_TURN_FILETYPE_PNG','_MI_TURN_FILETYPE_GIF','_MI_TURN_FILETYPE_SWF') DEFAULT '_MI_TURN_FILETYPE_JPG',
  `extension` varchar(5) DEFAULT 'jpg',
  `width` int(10) unsigned DEFAULT '0',
  `height` int(10) unsigned DEFAULT '0',
  `size` int(10) unsigned DEFAULT '0',
  `html` mediumtext,
  `uid` int(13) unsigned DEFAULT '0',
  `created` int(13) unsigned DEFAULT '0',
  `updated` int(13) unsigned DEFAULT '0',
  `actioned` int(13) unsigned DEFAULT '0',
  PRIMARY KEY (`pid`),
  KEY `COMMON` (`bid`,`lid`,`page`,`mode`,`location`,`filetype`,`extension`,`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
