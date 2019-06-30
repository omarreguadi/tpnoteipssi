
CREATE TABLE `community` (
                        `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
                        `user` varchar(30) NOT NULL,
                        `title` varchar(100) NOT NULL,
                        `description` text NOT NULL,
                        `startDate` datetime NOT NULL,
                        `endDate` datetime NOT NULL,
                        PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;