<?php

class Sql
{

    public static $ORDINANCE_ID = 102;
    public static $RESOLUTION_ID = 103;
    public static $DROP_EMAIl_AUDIT_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_email_audit`;
EOT;
    public static $DROP_EMAIL_QUEUE_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_email_queue`;
EOT;
    public static $DROP_MAPS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_maps`;
EOT;
    public static $DROP_ORDINANCES_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_ordinances`;
EOT;
    public static $DROP_ORDINANCES_USERS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_ordinances_users`;
EOT;
    public static $DROP_ORDINANCES_FILES_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_ordinances_files`;
EOT;
    public static $DROP_POSTS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_posts`;
EOT;
    public static $DROP_POSTS_POST_TYPES_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_posts_post_types`;
EOT;
    public static $DROP_POST_IMAGES_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_post_images`;
EOT;
    public static $DROP_POST_TYPES_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_post_types`;
EOT;
    public static $DROP_SURVEYS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_surveys`;
EOT;
    public static $DROP_SURVEY_OPTIONS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_survey_options`;
EOT;
    public static $DROP_SURVEY_QUESTIONS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_survey_questions`;
EOT;
    public static $DROP_SURVEY_USERS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `b_survey_users`;
EOT;
    public static $DROP_SYS_ROLES_TABLE = <<<EOT
                DROP TABLE IF EXISTS `sys_roles`;
EOT;
    public static $DROP_SYS_ROLE_MAPPINGS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `sys_role_mappings`;
EOT;
    public static $DROP_SYS_USERS_TABLE = <<<EOT
                DROP TABLE IF EXISTS `sys_users`;
EOT;
    public static $DROP_SYS_USER_ROLES_TABLE = <<<EOT
                DROP TABLE IF EXISTS `sys_user_roles`;
EOT;
    public static $CREATE_EMAIL_AUDIT_TABLE = <<<EOT
                CREATE TABLE `b_email_audit` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `from` CHAR(50) NOT NULL,
                        `subject` VARCHAR(300) NOT NULL,
                        `message` VARCHAR(1000) NOT NULL,
                        `recipients` TEXT NOT NULL,
                        `sent_datetime` DATETIME NOT NULL,
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='latin1_swedish_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=110;
EOT;
    public static $CREATE_EMAIL_QUEUE_TABLE = <<<EOT
                CREATE TABLE `b_email_queue` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `from` CHAR(50) NOT NULL,
                        `subject` VARCHAR(300) NOT NULL,
                        `message` VARCHAR(1000) NOT NULL,
                        `recipients` TEXT NOT NULL,
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='latin1_swedish_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=6;
EOT;
    public static $CREATE_MAPS_TABLE = <<<EOT
                CREATE TABLE `b_maps` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `name` VARCHAR(100) NOT NULL DEFAULT '0',
                        `url` VARCHAR(1000) NOT NULL DEFAULT '0',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=2;
EOT;
    public static $CREATE_ORDINANCES_TABLE = <<<EOT
                CREATE TABLE `b_ordinances` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `name` TEXT NOT NULL,
                        `summary` LONGTEXT NOT NULL,
                        `link_to_file` VARCHAR(1000) NOT NULL DEFAULT '0',
                        `status` VARCHAR(50) NOT NULL DEFAULT 'NOT_PUBLISHED',
                        `type` VARCHAR(50) NOT NULL DEFAULT 'ORDINANCE',
                        `searchable` TINYINT(1) NOT NULL DEFAULT '0',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=102;
EOT;
    public static $CREATE_ORDINANCE_TABLE = <<<EOT
                CREATE TABLE `b_ordinances` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `name` TEXT NOT NULL,
                        `summary` LONGTEXT NOT NULL,
                        `link_to_file` VARCHAR(1000) NOT NULL DEFAULT '0',
                        `status` VARCHAR(50) NOT NULL DEFAULT 'NOT_PUBLISHED',
                        `type` VARCHAR(50) NOT NULL DEFAULT 'ORDINANCE',
                        `searchable` TINYINT(1) NOT NULL DEFAULT '0',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=101;
EOT;
    public static $CREATE_ORDINANCES_USERS_TABLE = <<<EOT
                CREATE TABLE `b_ordinances_users` (
                        `id` INT(10) NOT NULL AUTO_INCREMENT,
                        `ordinance_id` INT(11) NOT NULL,
                        `sys_user_id` INT(11) NOT NULL,
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='latin1_swedish_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=14;
EOT;
    public static $CREATE_ORDINANCES_FILES_TABLE = <<<EOT
                CREATE TABLE `b_ordinances_files` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `ordinance_id` INT(11) NOT NULL,
                        `filename` VARCHAR(1000) NOT NULL DEFAULT '0',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                ROW_FORMAT=COMPACT
                AUTO_INCREMENT=108;
EOT;
    public static $CREATE_POSTS_TABLE = <<<EOT
                CREATE TABLE `b_posts` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `title` VARCHAR(100) NOT NULL DEFAULT '0',
                        `body` MEDIUMTEXT NOT NULL,
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=4;
EOT;
    public static $CREATE_POSTS_POST_TYPES_TABLE = <<<EOT
                CREATE TABLE `b_posts_post_types` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `post_type_id` INT(11) NOT NULL DEFAULT '0',
                        `post_id` INT(11) NOT NULL DEFAULT '0',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=4;
EOT;
    public static $CREATE_POST_IMAGES_TABLE = <<<EOT
                CREATE TABLE `b_post_images` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `post_id` INT(11) NOT NULL DEFAULT '0',
                        `name` VARCHAR(10000) NOT NULL,
                        `caption` VARCHAR(1000) NOT NULL,
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL DEFAULT 'Anonymous',
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='latin1_swedish_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=56;
EOT;
    public static $CREATE_POST_TYPES_TABLE = <<<EOT
                CREATE TABLE `b_post_types` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `name` VARCHAR(50) NOT NULL DEFAULT '0',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=4;
EOT;
    public static $CREATE_SURVEYS_TABLE = <<<EOT
                CREATE TABLE `b_surveys` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `b_survey_question_id` INT(11) NOT NULL,
                        `b_survey_option_id` INT(11) NOT NULL,
                        `votes` INT(11) NOT NULL DEFAULT '0',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL,
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=5;
EOT;
    public static $CREATE_SURVEY_OPTIONS_TABLE = <<<EOT
                CREATE TABLE `b_survey_options` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `answer_text` VARCHAR(1000) NOT NULL DEFAULT '0',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=5;
EOT;
    public static $CREATE_SURVEY_QUESTIONS_TABLE = <<<EOT
                CREATE TABLE `b_survey_questions` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `question` VARCHAR(1000) NOT NULL,
                        `status` VARCHAR(50) NOT NULL DEFAULT 'NOT_PUBLISHED',
                        `featured` VARCHAR(50) NOT NULL DEFAULT 'NOT_FEATURED',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=2;
EOT;
    public static $CREATE_SURVEY_USERS_TABLE = <<<EOT
                CREATE TABLE `b_survey_users` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `b_survey_question_id` INT(11) NOT NULL,
                        `sys_user_id` INT(11) NOT NULL,
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL,
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                ROW_FORMAT=COMPACT;
EOT;
    public static $CREATE_SYS_ROLES_TABLE = <<<EOT
                CREATE TABLE `sys_roles` (
                        `id` INT(10) NOT NULL AUTO_INCREMENT,
                        `name` VARCHAR(50) NOT NULL,
                        `description` VARCHAR(100) NOT NULL,
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=7;
EOT;
    public static $CREATE_SYS_ROLE_MAPPINGS_TABLE = <<<EOT
                CREATE TABLE `sys_role_mappings` (
                        `id` INT(10) NOT NULL AUTO_INCREMENT,
                        `parent_id` INT(10) NOT NULL,
                        `child_id` INT(10) NOT NULL,
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=8;
EOT;
    public static $CREATE_SYS_USERS_TABLE = <<<EOT
                CREATE TABLE `sys_users` (
                    `id` INT(10) NOT NULL AUTO_INCREMENT,
                    `firstname` VARCHAR(100) NOT NULL,
                    `lastname` VARCHAR(100) NOT NULL,
                    `mi` CHAR(1) NOT NULL,
                    `designation` CHAR(10) NOT NULL,
                    `address` VARCHAR(100) NOT NULL,
                    `contacts` VARCHAR(50) NOT NULL,
                    `email_add` VARCHAR(100) NOT NULL,
                    `signature` VARCHAR(200) NOT NULL,
                    `searchable` TINYINT(1) NOT NULL DEFAULT '0',
                    `district` TINYINT(1) NOT NULL,
                    `date_created` DATE NOT NULL,
                    `user_created` VARCHAR(50) NOT NULL,
                    `date_last_modified` DATE NOT NULL,
                    `user_last_modified` VARCHAR(50) NOT NULL,
                    `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=31;
EOT;
    public static $CREATE_SYS_USER_ROLES_TABLE = <<<EOT
                CREATE TABLE `sys_user_roles` (
                        `id` INT(10) NOT NULL AUTO_INCREMENT,
                        `sys_user_id` INT(10) NOT NULL,
                        `sys_role_id` INT(10) NOT NULL,
                        `username` VARCHAR(50) NOT NULL,
                        `password` VARCHAR(200) NOT NULL,
                        `pwc` TINYINT(10) NOT NULL DEFAULT '1',
                        `status` CHAR(10) NOT NULL DEFAULT 'NEW',
                        `date_created` DATE NOT NULL,
                        `user_created` VARCHAR(50) NOT NULL,
                        `date_last_modified` DATE NOT NULL,
                        `user_last_modified` VARCHAR(50) NOT NULL,
                        `deleted` TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                )
                COLLATE='utf8mb4_general_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=62;
EOT;
    public static $INSERT_POST_TYPES_TABLE = <<<EOT
                INSERT INTO `b_post_types` (`id`, `name`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (1, 'ABOUT', '0000-00-00', '', '0000-00-00', '', 0);
                INSERT INTO `b_post_types` (`id`, `name`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (2, 'NEWS', '0000-00-00', '', '0000-00-00', '', 0);
                INSERT INTO `b_post_types` (`id`, `name`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (3, 'CONTACT', '0000-00-00', '', '0000-00-00', '', 0);
                INSERT INTO `b_post_types` (`id`, `name`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (4, 'EVENT', '0000-00-00', '', '0000-00-00', '', 0);
EOT;
    public static $INSERT_POSTS_TABLE = <<<EOT
                INSERT INTO `b_posts` (`id`, `title`, `body`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (1, 'About Us', 'TAGLISH Version\r\n\r\nThis is the official website of the Sanggguniang Panglungsod of the City of Bacoor, Cavite.\r\n\r\nThe historic town of Bacoor, was founded in 1671. Its former name "Bakood" (meaning "fence" in Tagalog) \r\nwas suggestive of its role as the boundary between the towns of Paranaque and Kawit. Bacoor was also the site of the Battle of Zapote Bridge in 1897 between the Filipinos and Americans. Upon the declaration of Philippine Independence on 12 June 1898, the town was then designated as the first capital of Philippine Revolutionary Government by Emilio Aguinaldo.\r\n\r\nOn June 23, 2012, under the administration of its 18th Municipal Mayor, Hon. Strike B. Revilla, Bacoor was converted into the City of Bacoor by virtue of Republic Act No. 10160 known as the "Charter of the City of Bacoor". The city is composed of seventy-three (73) barangays under two (2) legislative districts namely Bacoor East and Bacoor West and has a population of around 650,000.\r\n\r\nUnder the Article IV, Section 11 of RA 10160, the Sangguniang Panlungsod is the legislative body of the City which has the power to enact ordinances, approve resolutions, and appropriate funds for the general welfare of the City and its inhabitants. The sanggunian is headed by the City Vice Mayor, Hon. catherine sarino - Evaristo, and comprised of twelve (12) city councilors as regular sanggunian members, the president of the city chapter of the Liga ng mga Barangay, and the president of the Panlungsod na Pederasyon ng mga Sangguniang Kabataan.\r\n\r\nThis website is the product of the Sangguniang Panlungsod\'s determination to be more transparent and to directly involve the people of the City of Bacoor not only in the crafting of vital legislative measures but also to have active role in shaping the future of their beloved city. Welcom to the historic City of Bacoor!\r\n\r\nTAGALOG\r\n\r\nIto ang opisyal na pahina ng Sangguniang Panlungsod ng Lungsod ng Bacoor, Cavite.\r\n\r\nAng makasaysayang Lungsod ng Bacoor ay Itinatag noong 1671. Ang dati nitong pangalan na "Bakood" (o "bakod" sa Tagalog) ay sumasagisag sa pagkakahati ng mga bayan ng Paranaque at Kawit. Ito rin ang lugar kung saan naganap ang Battle of Zapote Bridge noong 1897 sa pagitan ng mga Pilipino at mga Amerikano. Matapos ang deklarasyon ng Kalayaan ng Pilipinas noong ika-12 ng Hunyo taong 1898, ang bayan ng Bacoor ay itinalaga bilang unang kapitolyo ng Pamahalaang Rebolusyonaryo sa ilalim ni Hen. Emilio Aguinaldo.\r\n\r\nNong ika-23 ng Hunyo taong 2012, sa ilalin ng pamumuno ng ika-18 Alkalde ng Bacoor na si Hon. Strike B. Revilla, ang bayan ng Bacoor at naiconvert bilang isang lungsod ayon sa Republic Act No. 10160 na kilala rin bilang "Charter of the City of Bacoor". Ang lungsod ay binubuo ng 73 na barangay na kumakatawan sa dalawang distrito, ang Bacoo East at Bacoor West na may populasyon na humigit kumulang na 650,000.\r\n\r\nAyon sa Articile IV, Section 11 ng RA 10160 ng nasabing batas, ang Sangguniang Panlungsod ay ang legislative body ng lungsod na may kapangyarihan na magpatupad ng mga ordinansa, mag-apruba ng mga resolusyon, at ng mga kinauukulang pondo para sa ikauunlad ng lungsod at ng mga mamamayan. Ang Sanggunian ay pinamumunuan ng Bise-Alkalde ng Lungsod, Hon. Catherine Sarino-Evaristo at binubuo ng labindalawang (12) konsehal ng lungsod, presidente ng liga ng mga barangay,at nang presidente ng Panlungsod na Pederasyon ng mga Sangguniang Kabataan.\r\n\r\nAng website na ito ay produkto ng determinasyon ng Sangguniang Panlungsod na maging mas tranparent at upang bigyan ang mga mamamayan ng direktang partisipasyon di lamang sa pagbalangkas ng mga ordinansa at resolusyon ngunit upang magkaroon din ng aktibong pagganap sa paghubog sa kinabukasan ng ating pinakamamahal na lungsod. Welcome sa makasaysayang Lungsod ng Bacoor.', '2014-08-04', 'admin1', '2014-08-12', 'admin1', 0);
EOT;
    public static $INSERT_POSTS_POST_TYPES = <<<EOT
                INSERT INTO `b_posts_post_types` (`id`, `post_type_id`, `post_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (1, 1, 1, '0000-00-00', '', '0000-00-00', '', 0);
EOT;
    public static $INSERT_SYS_ROLE_MAPPINGS_TABLE = <<<EOT
                INSERT INTO `sys_role_mappings` (`id`, `parent_id`, `child_id`) VALUES (1, 1, 2);
                INSERT INTO `sys_role_mappings` (`id`, `parent_id`, `child_id`) VALUES (2, 2, 3);
                INSERT INTO `sys_role_mappings` (`id`, `parent_id`, `child_id`) VALUES (3, 3, 4);
                INSERT INTO `sys_role_mappings` (`id`, `parent_id`, `child_id`) VALUES (4, 4, 5);
                INSERT INTO `sys_role_mappings` (`id`, `parent_id`, `child_id`) VALUES (6, 5, 6);
                INSERT INTO `sys_role_mappings` (`id`, `parent_id`, `child_id`) VALUES (7, 6, 0);
EOT;
    public static $INSERT_SYS_USERS_TABLE = <<<EOT
                INSERT INTO `sys_users` (`id`, `firstname`, `lastname`, `mi`, `designation`, `address`, `contacts`, `email_add`, `signature`, `searchable`, `district`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
                (1, 'Catherine', 'Evaristo', 'J', '', 'Bacoor City', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-02', 'khalid', 0),
                (2, 'Khalid', 'Atega', 'H', 'Sr', 'Bacoor City', '09156473743', 'joshauza@gmail.com', 'sig.png?1407856938', 1, 2, '0000-00-00', '', '2014-08-12', 'khalid', 0),
                (3, 'Avelino', 'Solis', 'K', '', 'Manila', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-02', 'khalid', 0),
                (4, 'Edwin', 'Gawaran', 'K', '', 'Brgy. Holy Spirit Mandaluyong', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-08', 'egawaran', 0),
                (5, 'Miguel', 'Bautista', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'mbautista', 0),
                (6, 'Rowena', 'Bautista Mendiola', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'rbmendiola', 0),
                (7, 'Reynaldo', 'Fabian', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'rfabian', 0),
                (8, 'Venus', 'De Castro', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'vdecastro', 0),
                (9, 'Victorio', 'Guerrero', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-07-25', 'khalid', 0),
                (10, 'Reynaldo', 'Palabrica', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 2, '0000-00-00', '', '2014-07-25', 'rpalabrica', 0),
                (11, 'Hernando', 'Gutierrez', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 2, '0000-00-00', '', '2014-07-25', 'hgutierrez', 0),
                (12, 'Leandro', 'De Leon', '', '', '', '', 'joshauza@gmail.com', 'sig.png?1407857722', 1, 2, '0000-00-00', '', '2014-08-12', 'ldeleon', 0),
                (13, 'Bayani', 'De Leon', '', '', '', '', 'joshauza@gmail.com', 'sig.png?1407857091', 1, 2, '0000-00-00', '', '2014-08-12', 'bdeleon', 0),
                (14, 'Gaudencio', 'Nolasco', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 2, '0000-00-00', '', '2014-07-25', 'gnolasco', 0),
                (15, 'Robert', 'Javier', '', '', '', '', 'joshauza@gmail.com', 'sig.png', 1, 2, '0000-00-00', '', '2014-07-25', 'rjavier', 0),
                (16, 'Admin', 'Admin', 'G', '', 'Manila', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-04', 'admin1', 0),
                (17, 'Voter', 'Voter', 'G', '', 'Manila', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-04', 'admin1', 0);
EOT;
    public static $INSERT_SYS_USER_ROLES_TABLE = <<<EOT
                INSERT INTO `sys_user_roles` (`id`, `sys_user_id`, `sys_role_id`, `username`, `password`, `pwc`, `status`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
                (1, 1, 1, 'cevaristo', 'a', 2, 'REG', '0000-00-00', '', '2014-08-02', 'khalid', 0),
                (2, 2, 2, 'khalid', 'a', 2, 'REG', '0000-00-00', '', '2014-08-02', 'khalid', 0),
                (3, 3, 5, 'asolis', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
                (4, 4, 5, 'egawaran', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
                (5, 5, 5, 'mbautista', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
                (6, 6, 5, 'rbmendiola', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
                (7, 7, 5, 'rfabian', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
                (8, 8, 5, 'vdecastro', 'a', 0, 'REG', '0000-00-00', '', '2014-07-30', 'admin1', 0),
                (9, 9, 5, 'vguerrero', 'a', 0, 'REG', '0000-00-00', '', '2014-07-31', 'admin1', 0),
                (10, 10, 5, 'rpalabrica', 'a', 0, 'REG', '0000-00-00', '', '2014-08-04', 'admin1', 0),
                (11, 11, 5, 'hgutierrez', 'a', 0, 'REG', '0000-00-00', '', '0000-00-00', '', 0),
                (12, 12, 5, 'ldeleon', 'a', 0, 'REG', '0000-00-00', '', '0000-00-00', '', 0),
                (13, 13, 5, 'bdeleon', 'a', 0, 'REG', '0000-00-00', '', '0000-00-00', '', 0),
                (14, 14, 5, 'gnolasco', 'a', 0, 'REG', '0000-00-00', '', '0000-00-00', '', 0),
                (15, 15, 5, 'rlavier', 'a', 0, 'REG', '0000-00-00', '', '2014-07-31', 'admin1', 0),
                (16, 16, 3, 'admin1', 'a', 0, 'REG', '0000-00-00', '', '2014-08-04', 'admin1', 0),
                (17, 17, 4, 'voter1', 'a', 0, 'REG', '0000-00-00', '', '2014-08-04', 'admin1', 0);
EOT;
    public static $INSERT_ONE_SYS_USER = <<<EOT
                INSERT INTO `sys_users` (`id`, `firstname`, `lastname`, `mi`, `designation`, `address`, `contacts`, `email_add`, `signature`, `searchable`, `district`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
                (100, 'Test', 'Test', 'J', '', 'Bacoor City', '09156473743', 'joshauza@gmail.com', 'sig.png', 1, 1, '0000-00-00', '', '2014-08-02', 'khalid', 0);
EOT;
    public static $INSERT_ONE_SYS_USER_ROLE = <<<EOT
                INSERT INTO `sys_user_roles` (`id`, `sys_user_id`, `sys_role_id`, `username`, `password`, `pwc`, `status`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
                (100, 100, 4, 'tester', 'a', 1, 'NEW', '0000-00-00', '', '2014-08-02', 'khalid', 0);
EOT;
    public static $INSERT_SYS_ROLES_TABLE = <<<EOT
                INSERT INTO `sys_roles` (`id`, `name`, `description`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES
                (1, 'Super Administrator', 'Vice Mayor account', '0000-00-00', '', '0000-00-00', '', 0),
                (2, 'Super User', 'City Lawyer account', '0000-00-00', '', '0000-00-00', '', 0),
                (3, 'Administrator', 'Web Administrator', '0000-00-00', '', '0000-00-00', '', 0),
                (4, 'Voter', 'Citizens', '0000-00-00', '', '0000-00-00', '', 0),
                (5, 'Councilor', 'Councilors', '0000-00-00', '', '0000-00-00', '', 0),
                (6, 'Guest', 'Guest', '0000-00-00', '', '0000-00-00', '', 0);
EOT;
    public static $INSERT_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances` (`id`, `name`, `summary`, `link_to_file`, `status`, `type`, `searchable`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (102, 'Ordinance one', '\r\nThe standard Lorem Ipsum passage, used since the 1500s\r\n\r\n"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\nSection 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"\r\n1914 translation by H. Rackham\r\n\r\n"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"\r\nSection 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat."\r\n1914 translation by H. Rackham\r\n\r\n"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains."\r\n', '0', 'NOT_PUBLISHED', 'ORDINANCE', 1, '2014-10-11', 'khalid', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ONE_RESOLUTION = <<<EOT
            INSERT INTO `b_ordinances` (`id`, `name`, `summary`, `link_to_file`, `status`, `type`, `searchable`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (103, 'Ordinance one', '\r\nThe standard Lorem Ipsum passage, used since the 1500s\r\n\r\n"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\nSection 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"\r\n1914 translation by H. Rackham\r\n\r\n"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"\r\nSection 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat."\r\n1914 translation by H. Rackham\r\n\r\n"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains."\r\n', '0', 'NOT_PUBLISHED', 'RESOLUTION', 1, '2014-10-11', 'khalid', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ONE_BUDGET = <<<EOT
            INSERT INTO `b_ordinances` (`id`, `name`, `summary`, `link_to_file`, `status`, `type`, `searchable`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (104, 'Ordinance one', '\r\nThe standard Lorem Ipsum passage, used since the 1500s\r\n\r\n"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\nSection 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"\r\n1914 translation by H. Rackham\r\n\r\n"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"\r\nSection 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat."\r\n1914 translation by H. Rackham\r\n\r\n"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains."\r\n', '0', 'NOT_PUBLISHED', 'BUDGET', 1, '2014-10-11', 'khalid', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ONE_BUDGET_PUBLISHED = <<<EOT
            INSERT INTO `b_ordinances` (`id`, `name`, `summary`, `link_to_file`, `status`, `type`, `searchable`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (104, 'Ordinance one', '\r\nThe standard Lorem Ipsum passage, used since the 1500s\r\n\r\n"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\nSection 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"\r\n1914 translation by H. Rackham\r\n\r\n"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"\r\nSection 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat."\r\n1914 translation by H. Rackham\r\n\r\n"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains."\r\n', '0', 'PUBLISHED', 'BUDGET', 1, '2014-10-11', 'khalid', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ONE_PROCUREMENT = <<<EOT
            INSERT INTO `b_ordinances` (`id`, `name`, `summary`, `link_to_file`, `status`, `type`, `searchable`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (105, 'Ordinance one', '\r\nThe standard Lorem Ipsum passage, used since the 1500s\r\n\r\n"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\nSection 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"\r\n1914 translation by H. Rackham\r\n\r\n"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"\r\nSection 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat."\r\n1914 translation by H. Rackham\r\n\r\n"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains."\r\n', '0', 'NOT_PUBLISHED', 'PROCUREMENT', 1, '2014-10-11', 'khalid', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ONE_PROCUREMENT_PUBLISHED = <<<EOT
            INSERT INTO `b_ordinances` (`id`, `name`, `summary`, `link_to_file`, `status`, `type`, `searchable`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (105, 'Ordinance one', '\r\nThe standard Lorem Ipsum passage, used since the 1500s\r\n\r\n"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\nSection 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"\r\n1914 translation by H. Rackham\r\n\r\n"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?"\r\nSection 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC\r\n\r\n"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat."\r\n1914 translation by H. Rackham\r\n\r\n"On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains."\r\n', '0', 'PUBLISHED', 'PROCUREMENT', 1, '2014-10-11', 'khalid', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ONE_APPROVAL_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (27, 102, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_TWO_APPROVAL_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (27, 102, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (28, 102, 2, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_TWO_APPROVAL_FOR_ONE_RESOLUTION = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (29, 103, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (30, 103, 2, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ONE_APPROVAL_FOR_EACH_LEGISLATION = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (27, 102, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (28, 103, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (29, 104, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (30, 105, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_VICEMAYOR_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (27, 102, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (28, 102, 2, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (14, 102, 3, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (23, 102, 4, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (22, 102, 5, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (21, 102, 6, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (20, 102, 7, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (19, 102, 8, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (18, 102, 9, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (17, 102, 10, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (16, 102, 11, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (15, 102, 12, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (24, 102, 13, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (25, 102, 14, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (26, 102, 15, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (28, 102, 2, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (14, 102, 3, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (23, 102, 4, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (22, 102, 5, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (21, 102, 6, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (20, 102, 7, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (19, 102, 8, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (18, 102, 9, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (17, 102, 10, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (16, 102, 11, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (15, 102, 12, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (24, 102, 13, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (25, 102, 14, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (26, 102, 15, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_ATTORNEY_COUNCILORS_APPROVALS_FOR_ONE_RESOLUTION = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (28, 103, 2, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (14, 103, 3, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (23, 103, 4, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (22, 103, 5, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (21, 103, 6, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (20, 103, 7, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (19, 103, 8, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (18, 103, 9, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (17, 103, 10, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (16, 103, 11, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (15, 103, 12, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (24, 103, 13, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (25, 103, 14, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (26, 103, 15, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_VICEMAYOR_ATTORNEY_APPROVALS_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (27, 102, 1, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (28, 102, 2, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            
EOT;
    public static $INSERT_ATTORNEY_APPROVALS_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (28, 102, 2, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_COUNCILORS_APPROVALS_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (14, 102, 3, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (23, 102, 4, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (22, 102, 5, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (21, 102, 6, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (20, 102, 7, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (19, 102, 8, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (18, 102, 9, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (17, 102, 10, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (16, 102, 11, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (15, 102, 12, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (24, 102, 13, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (25, 102, 14, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (26, 102, 15, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_3D1_3D2_APPROVALS_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (14, 102, 3, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (23, 102, 4, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (22, 102, 5, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (18, 102, 10, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (17, 102, 11, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (16, 102, 12, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_3D1_3D2_APPROVALS_FOR_ONE_RESOLUTION = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (30, 103, 3, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (31, 103, 4, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (32, 103, 5, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (33, 103, 10, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (34, 103, 11, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (35, 103, 12, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_DISTRICT_ONE_APPROVALS_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (14, 102, 3, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (23, 102, 4, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (22, 102, 5, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (21, 102, 6, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (20, 102, 7, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (19, 102, 8, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (24, 102, 9, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_DISTRICT_TWO_APPROVALS_FOR_ONE_ORDINANCE = <<<EOT
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (17, 102, 10, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (16, 102, 11, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (15, 102, 12, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (24, 102, 13, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (25, 102, 14, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_users` (`id`, `ordinance_id`, `sys_user_id`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (26, 102, 15, '2014-10-11', 'vdecastro', '0000-00-00', 'Anonymous', 0);
EOT;
    public static $INSERT_FILES_TO_ONE_PROCUREMENT = <<<EOT
            INSERT INTO `b_ordinances_files` (`id`, `ordinance_id`, `filename`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (108, 105, 'Ordinance one(2).pdf', '2014-10-26', 'admin1', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_files` (`id`, `ordinance_id`, `filename`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (109, 105, 'Ordinance one(3).pdf', '2014-10-26', 'admin1', '0000-00-00', 'Anonymous', 0);

EOT;
    public static $INSERT_FILES_TO_ONE_BUDGET = <<<EOT
            INSERT INTO `b_ordinances_files` (`id`, `ordinance_id`, `filename`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (108, 104, 'Ordinance one(2).pdf', '2014-10-26', 'admin1', '0000-00-00', 'Anonymous', 0);
            INSERT INTO `b_ordinances_files` (`id`, `ordinance_id`, `filename`, `date_created`, `user_created`, `date_last_modified`, `user_last_modified`, `deleted`) VALUES (109, 104, 'Ordinance one(3).pdf', '2014-10-26', 'admin1', '0000-00-00', 'Anonymous', 0);

EOT;
    public static $ALTER_ORDINANCE_STATUS = <<<EOT
           UPDATE `b_ordinances` SET `status`='PUBLISHED' WHERE `id`=102;
EOT;
    public static $SELECT_ORDINANCE_APPROVALS = <<<EOT
            SELECT 
                    b_ordinances_users.id AS id,
                    b_ordinances_users.ordinance_id AS or_id,
                    b_ordinances_users.sys_user_id AS user_id,
                    temp_table.firstname AS firstname,
                    temp_table.lastname AS lastname,
                    temp_table.signature AS signature,
                    temp_table.district AS district,
                    temp_table.sys_role_id AS role_id
            FROM 
                    b_ordinances_users
                    LEFT JOIN b_ordinances
                    ON b_ordinances_users.ordinance_id = b_ordinances.id
                    LEFT JOIN 
                        (
                            SELECT
                                   sys_user_roles.id AS sys_user_role_id,
                                   sys_users.id AS sys_user_id,
                                   sys_users.firstname AS firstname,
                                   sys_users.lastname AS lastname,
                                   sys_users.signature AS signature,
                                   sys_users.district AS district,
                                   sys_users.deleted AS deleted,
                                   sys_roles.id AS sys_role_id
                            FROM
                                   sys_user_roles
                                   LEFT JOIN sys_users
                                   ON sys_user_roles.sys_user_id = sys_users.id
                                   LEFT JOIN sys_roles
                                   ON sys_user_roles.sys_role_id = sys_roles.id
                            )
                           AS temp_table
                    ON b_ordinances_users.sys_user_id = temp_table.sys_user_id
            WHERE
                    b_ordinances.deleted = 0
                    AND b_ordinances_users.deleted = 0
                            AND temp_table.deleted = 0
EOT;
    
    public static $SELECT_NEWS_BY_ID = <<<EOT
            SELECT
                    b_posts.id AS id,
                    b_posts.title AS title,
                    b_posts.body AS body,
                    b_posts.date_created AS date_created,
                    b_posts.user_created AS user_created,
                    b_posts_post_types.post_type_id AS post_type,
                    b_post_types.name AS post_type_name
            FROM
                    b_posts_post_types
                    LEFT JOIN
                    b_posts 
                    ON b_posts_post_types.post_id = b_posts.id
                    LEFT JOIN
                    b_post_types 
                    ON b_posts_post_types.post_type_id = b_post_types.id
            WHERE
                    b_posts.deleted = 0
                    AND b_posts_post_types.deleted = 0
                    AND b_post_types.deleted = 0
                    AND b_posts_post_types.id = ?
EOT;

    public static function reset()
    {
        $db = Zend_Registry::get( 'db' );
        $db->query( Sql::$DROP_EMAIl_AUDIT_TABLE );
        $db->query( Sql::$CREATE_EMAIL_AUDIT_TABLE );
        $db->query( Sql::$DROP_EMAIL_QUEUE_TABLE );
        $db->query( Sql::$CREATE_EMAIL_QUEUE_TABLE );
        $db->query( Sql::$DROP_MAPS_TABLE );
        $db->query( Sql::$CREATE_MAPS_TABLE );
        $db->query( Sql::$DROP_ORDINANCES_TABLE );
        $db->query( Sql::$CREATE_ORDINANCES_TABLE );
        $db->query( Sql::$DROP_ORDINANCES_FILES_TABLE );
        $db->query( Sql::$CREATE_ORDINANCES_FILES_TABLE );
        $db->query( Sql::$DROP_ORDINANCES_USERS_TABLE );
        $db->query( Sql::$CREATE_ORDINANCES_USERS_TABLE );
        $db->query( Sql::$DROP_POSTS_TABLE );
        $db->query( Sql::$CREATE_POSTS_TABLE );
        $db->query( Sql::$DROP_POSTS_POST_TYPES_TABLE );
        $db->query( Sql::$CREATE_POSTS_POST_TYPES_TABLE );
        $db->query( Sql::$DROP_POST_IMAGES_TABLE );
        $db->query( Sql::$CREATE_POST_IMAGES_TABLE );
        $db->query( Sql::$DROP_POST_TYPES_TABLE );
        $db->query( Sql::$CREATE_POST_TYPES_TABLE );
        $db->query( Sql::$DROP_SURVEYS_TABLE );
        $db->query( Sql::$CREATE_SURVEYS_TABLE );
        $db->query( Sql::$DROP_SURVEY_OPTIONS_TABLE );
        $db->query( Sql::$CREATE_SURVEY_OPTIONS_TABLE );
        $db->query( Sql::$DROP_SURVEY_QUESTIONS_TABLE );
        $db->query( Sql::$CREATE_SURVEY_QUESTIONS_TABLE );
        $db->query( Sql::$DROP_SURVEY_USERS_TABLE );
        $db->query( Sql::$CREATE_SURVEY_USERS_TABLE );
        $db->query( Sql::$DROP_SYS_ROLES_TABLE );
        $db->query( Sql::$CREATE_SYS_ROLES_TABLE );
        $db->query( Sql::$DROP_SYS_ROLE_MAPPINGS_TABLE );
        $db->query( Sql::$CREATE_SYS_ROLE_MAPPINGS_TABLE );
        $db->query( Sql::$DROP_SYS_USERS_TABLE );
        $db->query( Sql::$CREATE_SYS_USERS_TABLE );
        $db->query( Sql::$DROP_SYS_USER_ROLES_TABLE );
        $db->query( Sql::$CREATE_SYS_USER_ROLES_TABLE );
        //inserts
        $db->query( Sql::$INSERT_POST_TYPES_TABLE );
        $db->query( Sql::$INSERT_POSTS_TABLE );
        $db->query( Sql::$INSERT_POSTS_POST_TYPES );
        $db->query( Sql::$INSERT_SYS_ROLE_MAPPINGS_TABLE );
        $db->query( Sql::$INSERT_SYS_USERS_TABLE );
        $db->query( Sql::$INSERT_SYS_USER_ROLES_TABLE );
        $db->query( Sql::$INSERT_SYS_ROLES_TABLE );
    }

}
