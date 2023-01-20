/*
SQLyog Ultimate v11.3 (64 bit)
MySQL - 10.1.38-MariaDB : Database - dev_ubiz
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dev_ubiz` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dev_ubiz`;

/*Table structure for table `core_key` */

DROP TABLE IF EXISTS `core_key`;

CREATE TABLE `core_key` (
  `key_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key_code` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`key_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

/*Data for the table `core_key` */

insert  into `core_key`(`key_id`,`key_code`,`created_at`,`created_by`,`updated_at`,`updated_by`) values (1,'dashboard','2023-01-15 16:24:32',1,NULL,NULL),(2,'change_password',NULL,NULL,NULL,NULL),(3,'old_password',NULL,NULL,NULL,NULL),(4,'new_password',NULL,NULL,NULL,NULL),(5,'re_new_password',NULL,NULL,NULL,NULL),(6,'cancel',NULL,NULL,NULL,NULL),(7,'save',NULL,NULL,NULL,NULL),(8,'login',NULL,NULL,NULL,NULL),(9,'logout',NULL,NULL,NULL,NULL),(10,'required',NULL,NULL,NULL,NULL),(11,'process',NULL,NULL,NULL,NULL),(12,'old_password_is_wrong',NULL,NULL,NULL,NULL),(13,'new_password_and_re_new_password_not_match',NULL,NULL,NULL,NULL),(14,'user_not_found',NULL,NULL,NULL,NULL),(15,'success_change_password',NULL,NULL,NULL,NULL),(16,'failed_change_password',NULL,NULL,NULL,NULL),(17,'email',NULL,NULL,NULL,NULL),(18,'password',NULL,NULL,NULL,NULL),(19,'you_dont_have_access',NULL,NULL,NULL,NULL),(20,'email_or_password_is_wrong',NULL,NULL,NULL,NULL),(21,'product',NULL,NULL,NULL,NULL),(22,'product_category',NULL,NULL,NULL,NULL),(23,'user',NULL,NULL,NULL,NULL),(24,'dt_empty_table',NULL,NULL,NULL,NULL),(25,'dt_info',NULL,NULL,NULL,NULL),(26,'dt_info_empty',NULL,NULL,NULL,NULL),(27,'dt_info_filtered',NULL,NULL,NULL,NULL),(28,'dt_info_post_fix',NULL,NULL,NULL,NULL),(29,'dt_thousands',NULL,NULL,NULL,NULL),(30,'dt_length_menu',NULL,NULL,NULL,NULL),(31,'dt_loading_records',NULL,NULL,NULL,NULL),(32,'dt_processing',NULL,NULL,NULL,NULL),(33,'dt_search',NULL,NULL,NULL,NULL),(34,'dt_zero_records',NULL,NULL,NULL,NULL),(35,'dt_paginate_first',NULL,NULL,NULL,NULL),(36,'dt_paginate_last',NULL,NULL,NULL,NULL),(37,'dt_paginate_next',NULL,NULL,NULL,NULL),(38,'dt_paginate_previous',NULL,NULL,NULL,NULL),(39,'dt_aria_sort_ascending',NULL,NULL,NULL,NULL),(40,'dt_aria_sort_descending',NULL,NULL,NULL,NULL),(41,'number',NULL,NULL,NULL,NULL),(42,'action',NULL,NULL,NULL,NULL),(43,'name',NULL,NULL,NULL,NULL),(44,'role',NULL,NULL,NULL,NULL),(45,'last_login',NULL,NULL,NULL,NULL);

/*Table structure for table `core_key_text` */

DROP TABLE IF EXISTS `core_key_text`;

CREATE TABLE `core_key_text` (
  `keytext_key_id` bigint(20) NOT NULL,
  `keytext_lang_code` varchar(5) NOT NULL,
  `keytext_text` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`keytext_key_id`,`keytext_lang_code`),
  KEY `keytext_lang_code` (`keytext_lang_code`),
  KEY `keytext_key_id` (`keytext_key_id`),
  CONSTRAINT `core_key_text_ibfk_1` FOREIGN KEY (`keytext_key_id`) REFERENCES `core_key` (`key_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `core_key_text_ibfk_2` FOREIGN KEY (`keytext_lang_code`) REFERENCES `core_lang` (`lang_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `core_key_text` */

insert  into `core_key_text`(`keytext_key_id`,`keytext_lang_code`,`keytext_text`,`created_at`,`created_by`,`updated_at`,`updated_by`) values (1,'en','Dashboard','2023-01-15 16:25:45',1,NULL,NULL),(1,'id','Dashboard','2023-01-15 16:25:43',1,NULL,NULL),(2,'en','Change Password','2023-01-15 16:25:45',1,NULL,NULL),(2,'id','Ubah Password','2023-01-15 16:25:45',1,NULL,NULL),(3,'en','Old Password','2023-01-15 16:25:45',1,NULL,NULL),(3,'id','Password Lama','2023-01-15 16:25:45',1,NULL,NULL),(4,'en','New Password','2023-01-15 16:25:45',1,NULL,NULL),(4,'id','Password Baru','2023-01-15 16:25:45',1,NULL,NULL),(5,'en','Retype New Password','2023-01-15 16:25:45',1,NULL,NULL),(5,'id','Ulang Password Baru','2023-01-15 16:25:45',1,NULL,NULL),(6,'en','Cancel','2023-01-15 16:25:45',1,NULL,NULL),(6,'id','Batal','2023-01-15 16:25:45',1,NULL,NULL),(7,'en','Save','2023-01-15 16:25:45',1,NULL,NULL),(7,'id','Simpan','2023-01-15 16:25:45',1,NULL,NULL),(8,'en','Login','2023-01-15 16:25:45',1,NULL,NULL),(8,'id','Login','2023-01-15 16:25:45',1,NULL,NULL),(9,'en','Logout','2023-01-15 16:25:45',1,NULL,NULL),(9,'id','Logout','2023-01-15 16:25:45',1,NULL,NULL),(10,'en','Required','2023-01-15 16:25:45',1,NULL,NULL),(10,'id','Dibutuhkan','2023-01-15 16:25:45',1,NULL,NULL),(11,'en','Process','2023-01-15 16:25:45',1,NULL,NULL),(11,'id','Proses','2023-01-15 16:25:45',1,NULL,NULL),(12,'en','Old Password Is Wrong','2023-01-15 16:25:45',1,NULL,NULL),(12,'id','Password Lama Salah','2023-01-15 16:25:45',1,NULL,NULL),(13,'en','New Password And Retype Password Not Match','2023-01-15 16:25:45',1,NULL,NULL),(13,'id','Password Baru dan Ulang Password Baru Tidak Sama','2023-01-15 16:25:45',1,NULL,NULL),(14,'en','User Not Found','2023-01-15 16:25:45',1,NULL,NULL),(14,'id','Pengguna Tidak Ditemukan','2023-01-15 16:25:45',1,NULL,NULL),(15,'en','Success Change Password','2023-01-15 16:25:45',1,NULL,NULL),(15,'id','Sukses Mengubah Password','2023-01-15 16:25:45',1,NULL,NULL),(16,'en','Failed Change Password','2023-01-15 16:25:45',1,NULL,NULL),(16,'id','Gagal Mengubah Password','2023-01-15 16:25:45',1,NULL,NULL),(17,'en','Email','2023-01-15 16:25:45',1,NULL,NULL),(17,'id','Email','2023-01-15 16:25:45',1,NULL,NULL),(18,'en','Password','2023-01-15 16:25:45',1,NULL,NULL),(18,'id','Password','2023-01-15 16:25:45',1,NULL,NULL),(19,'en','You Dont Have Access','2023-01-15 16:25:45',1,NULL,NULL),(19,'id','Anda Tidak Memiliki Akses','2023-01-15 16:25:45',1,NULL,NULL),(20,'en','Email Or Password Is Wrong','2023-01-15 16:25:45',1,NULL,NULL),(20,'id','Email Atau Password Salah','2023-01-15 16:25:45',1,NULL,NULL),(21,'en','Product','2023-01-15 16:25:45',1,NULL,NULL),(21,'id','Produk','2023-01-15 16:25:45',1,NULL,NULL),(22,'en','Product Category','2023-01-15 16:25:45',1,NULL,NULL),(22,'id','Kategori Produk','2023-01-15 16:25:45',1,NULL,NULL),(23,'en','User','2023-01-15 16:25:45',1,NULL,NULL),(23,'id','Pengguna','2023-01-15 16:25:45',1,NULL,NULL),(24,'en','No data available in table',NULL,NULL,NULL,NULL),(24,'id','Data tidak tersedia',NULL,NULL,NULL,NULL),(25,'en','Showing _START_ to _END_ of _TOTAL_ data',NULL,NULL,NULL,NULL),(25,'id','Menampilkan _START_ sampai _END_ dari _TOTAL_ data',NULL,NULL,NULL,NULL),(26,'en','Showing 0 to 0 of 0 entries',NULL,NULL,NULL,NULL),(26,'id','Menampilkan 0 sampai 0 dari 0 data',NULL,NULL,NULL,NULL),(27,'en','(filtered from _MAX_ total entries)',NULL,NULL,NULL,NULL),(27,'id','(tersaring dari _MAX_ total data)',NULL,NULL,NULL,NULL),(28,'en','',NULL,NULL,NULL,NULL),(28,'id','',NULL,NULL,NULL,NULL),(29,'en',',',NULL,NULL,NULL,NULL),(29,'id','.',NULL,NULL,NULL,NULL),(30,'en','Show _MENU_ entries',NULL,NULL,NULL,NULL),(30,'id','Tampil _MENU_ data',NULL,NULL,NULL,NULL),(31,'en','Loading...',NULL,NULL,NULL,NULL),(31,'id','Memuat...',NULL,NULL,NULL,NULL),(32,'en','',NULL,NULL,NULL,NULL),(32,'id','',NULL,NULL,NULL,NULL),(33,'en','Search',NULL,NULL,NULL,NULL),(33,'id','Cari',NULL,NULL,NULL,NULL),(34,'en','No matching records found',NULL,NULL,NULL,NULL),(34,'id','Tidak ditemukan data yang cocok',NULL,NULL,NULL,NULL),(35,'en','First',NULL,NULL,NULL,NULL),(35,'id','Pertama',NULL,NULL,NULL,NULL),(36,'en','Last',NULL,NULL,NULL,NULL),(36,'id','Terakhir',NULL,NULL,NULL,NULL),(37,'en','Next',NULL,NULL,NULL,NULL),(37,'id','Selanjutnya',NULL,NULL,NULL,NULL),(38,'en','Previous',NULL,NULL,NULL,NULL),(38,'id','Sebelumnya',NULL,NULL,NULL,NULL),(39,'en',': activate to sort column ascending',NULL,NULL,NULL,NULL),(39,'id',': aktifkan untuk mengurutkan kolom kecil ke besar',NULL,NULL,NULL,NULL),(40,'en',': activate to sort column descending',NULL,NULL,NULL,NULL),(40,'id',': aktifkan untuk mengurutkan kolom besar ke kecil',NULL,NULL,NULL,NULL),(41,'en','Number',NULL,NULL,NULL,NULL),(41,'id','Nomer',NULL,NULL,NULL,NULL),(42,'en','Action',NULL,NULL,NULL,NULL),(42,'id','Aksi',NULL,NULL,NULL,NULL),(43,'en','Name',NULL,NULL,NULL,NULL),(43,'id','Nama',NULL,NULL,NULL,NULL),(44,'en','Role',NULL,NULL,NULL,NULL),(44,'id','Peran',NULL,NULL,NULL,NULL),(45,'en','Last Login',NULL,NULL,NULL,NULL),(45,'id','Login Terakhir',NULL,NULL,NULL,NULL);

/*Table structure for table `core_lang` */

DROP TABLE IF EXISTS `core_lang`;

CREATE TABLE `core_lang` (
  `lang_code` varchar(5) NOT NULL,
  `lang_name` varchar(250) DEFAULT NULL,
  `lang_icon` text COMMENT 'file upload',
  `insert_user_id` bigint(20) DEFAULT NULL,
  `insert_datetime` datetime DEFAULT NULL,
  `update_user_id` bigint(20) DEFAULT NULL,
  `update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `core_lang` */

insert  into `core_lang`(`lang_code`,`lang_name`,`lang_icon`,`insert_user_id`,`insert_datetime`,`update_user_id`,`update_datetime`) values ('en','English','e734a123ef3d64f10fb58bebd800dc77.png',1,'2019-09-07 19:26:03',1,'2019-09-15 15:02:37'),('id','Indonesia','4501677096c46ab34fd40f633b831e5d.png',1,'2019-09-07 19:26:01',1,'2019-09-11 05:44:13');

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_parent_id` int(11) DEFAULT NULL,
  `menu_desc` text,
  `menu_url` text,
  `menu_icon` varchar(200) DEFAULT NULL,
  `menu_status` tinyint(1) DEFAULT '1',
  `menu_order` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `menu` */

insert  into `menu`(`menu_id`,`menu_parent_id`,`menu_desc`,`menu_url`,`menu_icon`,`menu_status`,`menu_order`,`created_at`,`created_by`,`updated_at`,`updated_by`) values (1,0,'Dashboard','dashboard','nav-icon fas fa-tachometer-alt',1,1,'2023-01-13 20:23:30',1,NULL,NULL),(2,0,'Product',NULL,'nav-icon fas fa-box',1,2,'2023-01-13 20:27:03',1,NULL,NULL),(3,2,'Product','product','nav-icon far fa-circle nav-icon',1,1,NULL,NULL,NULL,NULL),(4,2,'Product Category','product-category','nav-icon far fa-circle nav-icon',1,2,NULL,NULL,NULL,NULL),(5,0,'User / Pengguna','user','nav-icon fas fa-user',1,3,NULL,NULL,NULL,NULL);

/*Table structure for table `menu_group` */

DROP TABLE IF EXISTS `menu_group`;

CREATE TABLE `menu_group` (
  `menugroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `menugroup_home_menu_id` int(11) DEFAULT NULL COMMENT 'default page home after login',
  `menugroup_menu_id` text COMMENT '1,2,3,4,6,7,11,dst',
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`menugroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `menu_group` */

insert  into `menu_group`(`menugroup_id`,`menugroup_home_menu_id`,`menugroup_menu_id`,`created_at`,`created_by`,`updated_at`,`updated_by`) values (1,1,'1,2,3,4,5','2023-01-13 20:25:43',1,NULL,NULL);

/*Table structure for table `menu_group_name` */

DROP TABLE IF EXISTS `menu_group_name`;

CREATE TABLE `menu_group_name` (
  `menugroupname_menugroup_id` int(11) DEFAULT NULL COMMENT 'ref to menu_group.menugroup_id',
  `menugroupname_lang_code` varchar(5) DEFAULT NULL COMMENT 'ref to core_lang.lang_code',
  `menugroupname_name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `menu_group_name` */

insert  into `menu_group_name`(`menugroupname_menugroup_id`,`menugroupname_lang_code`,`menugroupname_name`) values (1,'id','Admin'),(1,'en','Admin');

/*Table structure for table `menu_name` */

DROP TABLE IF EXISTS `menu_name`;

CREATE TABLE `menu_name` (
  `menuname_menu_id` int(11) DEFAULT NULL COMMENT 'ref to menu.menu_id',
  `menuname_lang_code` varchar(5) DEFAULT NULL COMMENT 'ref to core_lang.lang_code',
  `menuname_name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `menu_name` */

insert  into `menu_name`(`menuname_menu_id`,`menuname_lang_code`,`menuname_name`) values (1,'id','Dahsboard'),(1,'en','Dahsboard'),(2,'id','Produk'),(2,'en','Product'),(3,'id','Produk'),(3,'en','Product'),(4,'id','Kategori Produk'),(4,'en','Product Category'),(5,'id','Pengguna'),(5,'en','User');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0=not active, 1=active',
  `last_login` datetime DEFAULT NULL,
  `role` int(11) DEFAULT NULL COMMENT 'ref to menu_group.menugroup_id',
  `lang_code` varchar(5) DEFAULT NULL COMMENT 'ref to core_lang.lang_code',
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`status`,`last_login`,`role`,`lang_code`,`created_at`,`created_by`,`updated_at`,`updated_by`) values (1,'Riyan Trisna Wibowo','riyantrisnawibowo@gmail.com','$2y$10$AwTtvvJnRqt4O2WVVqAd3ua4mW2deMjOdff4S9TtNtzAcZzfRV12e',1,'2023-01-21 01:54:22',1,'id',NULL,NULL,'2023-01-21 01:54:22',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
