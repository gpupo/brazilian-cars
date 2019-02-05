-- MySQL dump 10.16  Distrib 10.1.37-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: mariadb    Database: app
-- ------------------------------------------------------
-- Server version	10.3.10-MariaDB-1:10.3.10+maria~bionic

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary table structure for view `bc_manufacturer_count`
--

DROP TABLE IF EXISTS `bc_manufacturer_count`;
/*!50001 DROP VIEW IF EXISTS `bc_manufacturer_count`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `bc_manufacturer_count` (
  `manufacturer` tinyint NOT NULL,
  `vehicles` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `bc_manufacturer`
--

DROP TABLE IF EXISTS `bc_manufacturer`;
/*!50001 DROP VIEW IF EXISTS `bc_manufacturer`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `bc_manufacturer` (
  `manufacturer_id` tinyint NOT NULL,
  `manufacturer` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `bc_manufacturer_count`
--

/*!50001 DROP TABLE IF EXISTS `bc_manufacturer_count`*/;
/*!50001 DROP VIEW IF EXISTS `bc_manufacturer_count`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`app_db_user`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `bc_manufacturer_count` AS select `a`.`manufacturer` AS `manufacturer`,(select count(0) from `bc_vehicle` `b` where `b`.`manufacturer_id` = `a`.`manufacturer_id`) AS `vehicles` from `bc_manufacturer` `a` order by (select count(0) from `bc_vehicle` `b` where `b`.`manufacturer_id` = `a`.`manufacturer_id`) desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `bc_manufacturer`
--

/*!50001 DROP TABLE IF EXISTS `bc_manufacturer`*/;
/*!50001 DROP VIEW IF EXISTS `bc_manufacturer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`app_db_user`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `bc_manufacturer` AS select distinct `bc_vehicle`.`manufacturer_id` AS `manufacturer_id`,`bc_vehicle`.`manufacturer` AS `manufacturer` from `bc_vehicle` order by `bc_vehicle`.`manufacturer` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-05 16:44:19
