-- MariaDB dump 10.17  Distrib 10.4.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: office
-- ------------------------------------------------------
-- Server version	10.4.13-MariaDB-1:10.4.13+maria~xenial

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
-- Table structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar` (
  `repDate` date NOT NULL COMMENT '日期',
  `repYear` int(8) NOT NULL COMMENT '年号',
  `repMonth` int(8) NOT NULL COMMENT '月',
  `repDay` int(8) NOT NULL COMMENT '天',
  `repWeek` int(8) NOT NULL COMMENT '周',
  `repStatus` int(8) DEFAULT 0 COMMENT '0:工作日；1：周末休息日；2：法定休息日；3：调休工作日',
  PRIMARY KEY (`repDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='全年日期表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar`
--

LOCK TABLES `calendar` WRITE;
/*!40000 ALTER TABLE `calendar` DISABLE KEYS */;
INSERT INTO `calendar` VALUES ('2020-01-01',2020,1,1,3,0),('2020-01-02',2020,1,2,4,0),('2020-01-03',2020,1,3,5,0),('2020-01-04',2020,1,4,6,1),('2020-01-05',2020,1,5,7,1),('2020-01-06',2020,1,6,1,0),('2020-01-07',2020,1,7,2,0),('2020-01-08',2020,1,8,3,0),('2020-01-09',2020,1,9,4,0),('2020-01-10',2020,1,10,5,0),('2020-01-11',2020,1,11,6,1),('2020-01-12',2020,1,12,7,1),('2020-01-13',2020,1,13,1,0),('2020-01-14',2020,1,14,2,0),('2020-01-15',2020,1,15,3,0),('2020-01-16',2020,1,16,4,0),('2020-01-17',2020,1,17,5,0),('2020-01-18',2020,1,18,6,1),('2020-01-19',2020,1,19,7,1),('2020-01-20',2020,1,20,1,0),('2020-01-21',2020,1,21,2,0),('2020-01-22',2020,1,22,3,0),('2020-01-23',2020,1,23,4,0),('2020-01-24',2020,1,24,5,0),('2020-01-25',2020,1,25,6,1),('2020-01-26',2020,1,26,7,1),('2020-01-27',2020,1,27,1,0),('2020-01-28',2020,1,28,2,0),('2020-01-29',2020,1,29,3,0),('2020-01-30',2020,1,30,4,0),('2020-01-31',2020,1,31,5,0),('2020-02-01',2020,2,1,6,1),('2020-02-02',2020,2,2,7,1),('2020-02-03',2020,2,3,1,0),('2020-02-04',2020,2,4,2,0),('2020-02-05',2020,2,5,3,0),('2020-02-06',2020,2,6,4,0),('2020-02-07',2020,2,7,5,0),('2020-02-08',2020,2,8,6,1),('2020-02-09',2020,2,9,7,1),('2020-02-10',2020,2,10,1,0),('2020-02-11',2020,2,11,2,0),('2020-02-12',2020,2,12,3,0),('2020-02-13',2020,2,13,4,0),('2020-02-14',2020,2,14,5,0),('2020-02-15',2020,2,15,6,1),('2020-02-16',2020,2,16,7,1),('2020-02-17',2020,2,17,1,0),('2020-02-18',2020,2,18,2,0),('2020-02-19',2020,2,19,3,0),('2020-02-20',2020,2,20,4,0),('2020-02-21',2020,2,21,5,0),('2020-02-22',2020,2,22,6,1),('2020-02-23',2020,2,23,7,1),('2020-02-24',2020,2,24,1,0),('2020-02-25',2020,2,25,2,0),('2020-02-26',2020,2,26,3,0),('2020-02-27',2020,2,27,4,0),('2020-02-28',2020,2,28,5,0),('2020-02-29',2020,2,29,6,1),('2020-03-01',2020,3,1,7,1),('2020-03-02',2020,3,2,1,0),('2020-03-03',2020,3,3,2,0),('2020-03-04',2020,3,4,3,0),('2020-03-05',2020,3,5,4,0),('2020-03-06',2020,3,6,5,0),('2020-03-07',2020,3,7,6,1),('2020-03-08',2020,3,8,7,1),('2020-03-09',2020,3,9,1,0),('2020-03-10',2020,3,10,2,0),('2020-03-11',2020,3,11,3,0),('2020-03-12',2020,3,12,4,0),('2020-03-13',2020,3,13,5,0),('2020-03-14',2020,3,14,6,1),('2020-03-15',2020,3,15,7,1),('2020-03-16',2020,3,16,1,0),('2020-03-17',2020,3,17,2,0),('2020-03-18',2020,3,18,3,0),('2020-03-19',2020,3,19,4,0),('2020-03-20',2020,3,20,5,0),('2020-03-21',2020,3,21,6,1),('2020-03-22',2020,3,22,7,1),('2020-03-23',2020,3,23,1,0),('2020-03-24',2020,3,24,2,0),('2020-03-25',2020,3,25,3,0),('2020-03-26',2020,3,26,4,0),('2020-03-27',2020,3,27,5,0),('2020-03-28',2020,3,28,6,1),('2020-03-29',2020,3,29,7,1),('2020-03-30',2020,3,30,1,0),('2020-03-31',2020,3,31,2,0),('2020-04-01',2020,4,1,3,0),('2020-04-02',2020,4,2,4,0),('2020-04-03',2020,4,3,5,0),('2020-04-04',2020,4,4,6,1),('2020-04-05',2020,4,5,7,1),('2020-04-06',2020,4,6,1,0),('2020-04-07',2020,4,7,2,0),('2020-04-08',2020,4,8,3,0),('2020-04-09',2020,4,9,4,0),('2020-04-10',2020,4,10,5,0),('2020-04-11',2020,4,11,6,1),('2020-04-12',2020,4,12,7,1),('2020-04-13',2020,4,13,1,0),('2020-04-14',2020,4,14,2,0),('2020-04-15',2020,4,15,3,0),('2020-04-16',2020,4,16,4,0),('2020-04-17',2020,4,17,5,0),('2020-04-18',2020,4,18,6,1),('2020-04-19',2020,4,19,7,1),('2020-04-20',2020,4,20,1,0),('2020-04-21',2020,4,21,2,0),('2020-04-22',2020,4,22,3,0),('2020-04-23',2020,4,23,4,0),('2020-04-24',2020,4,24,5,0),('2020-04-25',2020,4,25,6,1),('2020-04-26',2020,4,26,7,1),('2020-04-27',2020,4,27,1,0),('2020-04-28',2020,4,28,2,0),('2020-04-29',2020,4,29,3,0),('2020-04-30',2020,4,30,4,0),('2020-05-01',2020,5,1,5,0),('2020-05-02',2020,5,2,6,1),('2020-05-03',2020,5,3,7,1),('2020-05-04',2020,5,4,1,0),('2020-05-05',2020,5,5,2,0),('2020-05-06',2020,5,6,3,0),('2020-05-07',2020,5,7,4,0),('2020-05-08',2020,5,8,5,0),('2020-05-09',2020,5,9,6,1),('2020-05-10',2020,5,10,7,1),('2020-05-11',2020,5,11,1,0),('2020-05-12',2020,5,12,2,0),('2020-05-13',2020,5,13,3,0),('2020-05-14',2020,5,14,4,0),('2020-05-15',2020,5,15,5,0),('2020-05-16',2020,5,16,6,1),('2020-05-17',2020,5,17,7,1),('2020-05-18',2020,5,18,1,0),('2020-05-19',2020,5,19,2,0),('2020-05-20',2020,5,20,3,0),('2020-05-21',2020,5,21,4,0),('2020-05-22',2020,5,22,5,0),('2020-05-23',2020,5,23,6,1),('2020-05-24',2020,5,24,7,1),('2020-05-25',2020,5,25,1,0),('2020-05-26',2020,5,26,2,0),('2020-05-27',2020,5,27,3,0),('2020-05-28',2020,5,28,4,0),('2020-05-29',2020,5,29,5,0),('2020-05-30',2020,5,30,6,1),('2020-05-31',2020,5,31,7,1),('2020-06-01',2020,6,1,1,0),('2020-06-02',2020,6,2,2,0),('2020-06-03',2020,6,3,3,0),('2020-06-04',2020,6,4,4,0),('2020-06-05',2020,6,5,5,0),('2020-06-06',2020,6,6,6,1),('2020-06-07',2020,6,7,7,1),('2020-06-08',2020,6,8,1,0),('2020-06-09',2020,6,9,2,0),('2020-06-10',2020,6,10,3,0),('2020-06-11',2020,6,11,4,0),('2020-06-12',2020,6,12,5,0),('2020-06-13',2020,6,13,6,1),('2020-06-14',2020,6,14,7,1),('2020-06-15',2020,6,15,1,0),('2020-06-16',2020,6,16,2,0),('2020-06-17',2020,6,17,3,0),('2020-06-18',2020,6,18,4,0),('2020-06-19',2020,6,19,5,0),('2020-06-20',2020,6,20,6,1),('2020-06-21',2020,6,21,7,1),('2020-06-22',2020,6,22,1,0),('2020-06-23',2020,6,23,2,0),('2020-06-24',2020,6,24,3,0),('2020-06-25',2020,6,25,4,0),('2020-06-26',2020,6,26,5,0),('2020-06-27',2020,6,27,6,1),('2020-06-28',2020,6,28,7,1),('2020-06-29',2020,6,29,1,0),('2020-06-30',2020,6,30,2,0),('2020-07-01',2020,7,1,3,0),('2020-07-02',2020,7,2,4,0),('2020-07-03',2020,7,3,5,0),('2020-07-04',2020,7,4,6,1),('2020-07-05',2020,7,5,7,1),('2020-07-06',2020,7,6,1,0),('2020-07-07',2020,7,7,2,0),('2020-07-08',2020,7,8,3,0),('2020-07-09',2020,7,9,4,0),('2020-07-10',2020,7,10,5,0),('2020-07-11',2020,7,11,6,1),('2020-07-12',2020,7,12,7,1),('2020-07-13',2020,7,13,1,0),('2020-07-14',2020,7,14,2,0),('2020-07-15',2020,7,15,3,0),('2020-07-16',2020,7,16,4,0),('2020-07-17',2020,7,17,5,0),('2020-07-18',2020,7,18,6,1),('2020-07-19',2020,7,19,7,1),('2020-07-20',2020,7,20,1,0),('2020-07-21',2020,7,21,2,0),('2020-07-22',2020,7,22,3,0),('2020-07-23',2020,7,23,4,0),('2020-07-24',2020,7,24,5,0),('2020-07-25',2020,7,25,6,1),('2020-07-26',2020,7,26,7,1),('2020-07-27',2020,7,27,1,0),('2020-07-28',2020,7,28,2,0),('2020-07-29',2020,7,29,3,0),('2020-07-30',2020,7,30,4,0),('2020-07-31',2020,7,31,5,0),('2020-08-01',2020,8,1,6,1),('2020-08-02',2020,8,2,7,1),('2020-08-03',2020,8,3,1,0),('2020-08-04',2020,8,4,2,0),('2020-08-05',2020,8,5,3,0),('2020-08-06',2020,8,6,4,0),('2020-08-07',2020,8,7,5,0),('2020-08-08',2020,8,8,6,1),('2020-08-09',2020,8,9,7,1),('2020-08-10',2020,8,10,1,0),('2020-08-11',2020,8,11,2,0),('2020-08-12',2020,8,12,3,0),('2020-08-13',2020,8,13,4,0),('2020-08-14',2020,8,14,5,0),('2020-08-15',2020,8,15,6,1),('2020-08-16',2020,8,16,7,1),('2020-08-17',2020,8,17,1,0),('2020-08-18',2020,8,18,2,0),('2020-08-19',2020,8,19,3,0),('2020-08-20',2020,8,20,4,0),('2020-08-21',2020,8,21,5,0),('2020-08-22',2020,8,22,6,1),('2020-08-23',2020,8,23,7,1),('2020-08-24',2020,8,24,1,0),('2020-08-25',2020,8,25,2,0),('2020-08-26',2020,8,26,3,0),('2020-08-27',2020,8,27,4,0),('2020-08-28',2020,8,28,5,0),('2020-08-29',2020,8,29,6,1),('2020-08-30',2020,8,30,7,1),('2020-08-31',2020,8,31,1,0),('2020-09-01',2020,9,1,2,0),('2020-09-02',2020,9,2,3,0),('2020-09-03',2020,9,3,4,0),('2020-09-04',2020,9,4,5,0),('2020-09-05',2020,9,5,6,1),('2020-09-06',2020,9,6,7,1),('2020-09-07',2020,9,7,1,0),('2020-09-08',2020,9,8,2,0),('2020-09-09',2020,9,9,3,0),('2020-09-10',2020,9,10,4,0),('2020-09-11',2020,9,11,5,0),('2020-09-12',2020,9,12,6,1),('2020-09-13',2020,9,13,7,1),('2020-09-14',2020,9,14,1,0),('2020-09-15',2020,9,15,2,0),('2020-09-16',2020,9,16,3,0),('2020-09-17',2020,9,17,4,0),('2020-09-18',2020,9,18,5,0),('2020-09-19',2020,9,19,6,1),('2020-09-20',2020,9,20,7,1),('2020-09-21',2020,9,21,1,0),('2020-09-22',2020,9,22,2,0),('2020-09-23',2020,9,23,3,0),('2020-09-24',2020,9,24,4,0),('2020-09-25',2020,9,25,5,0),('2020-09-26',2020,9,26,6,1),('2020-09-27',2020,9,27,7,1),('2020-09-28',2020,9,28,1,0),('2020-09-29',2020,9,29,2,0),('2020-09-30',2020,9,30,3,0),('2020-10-01',2020,10,1,4,0),('2020-10-02',2020,10,2,5,0),('2020-10-03',2020,10,3,6,1),('2020-10-04',2020,10,4,7,1),('2020-10-05',2020,10,5,1,0),('2020-10-06',2020,10,6,2,0),('2020-10-07',2020,10,7,3,0),('2020-10-08',2020,10,8,4,0),('2020-10-09',2020,10,9,5,0),('2020-10-10',2020,10,10,6,1),('2020-10-11',2020,10,11,7,1),('2020-10-12',2020,10,12,1,0),('2020-10-13',2020,10,13,2,0),('2020-10-14',2020,10,14,3,0),('2020-10-15',2020,10,15,4,0),('2020-10-16',2020,10,16,5,0),('2020-10-17',2020,10,17,6,1),('2020-10-18',2020,10,18,7,1),('2020-10-19',2020,10,19,1,0),('2020-10-20',2020,10,20,2,0),('2020-10-21',2020,10,21,3,0),('2020-10-22',2020,10,22,4,0),('2020-10-23',2020,10,23,5,0),('2020-10-24',2020,10,24,6,1),('2020-10-25',2020,10,25,7,1),('2020-10-26',2020,10,26,1,0),('2020-10-27',2020,10,27,2,0),('2020-10-28',2020,10,28,3,0),('2020-10-29',2020,10,29,4,0),('2020-10-30',2020,10,30,5,0),('2020-10-31',2020,10,31,6,1),('2020-11-01',2020,11,1,7,1),('2020-11-02',2020,11,2,1,0),('2020-11-03',2020,11,3,2,0),('2020-11-04',2020,11,4,3,0),('2020-11-05',2020,11,5,4,0),('2020-11-06',2020,11,6,5,0),('2020-11-07',2020,11,7,6,1),('2020-11-08',2020,11,8,7,1),('2020-11-09',2020,11,9,1,0),('2020-11-10',2020,11,10,2,0),('2020-11-11',2020,11,11,3,0),('2020-11-12',2020,11,12,4,0),('2020-11-13',2020,11,13,5,0),('2020-11-14',2020,11,14,6,1),('2020-11-15',2020,11,15,7,1),('2020-11-16',2020,11,16,1,0),('2020-11-17',2020,11,17,2,0),('2020-11-18',2020,11,18,3,0),('2020-11-19',2020,11,19,4,0),('2020-11-20',2020,11,20,5,0),('2020-11-21',2020,11,21,6,1),('2020-11-22',2020,11,22,7,1),('2020-11-23',2020,11,23,1,0),('2020-11-24',2020,11,24,2,0),('2020-11-25',2020,11,25,3,0),('2020-11-26',2020,11,26,4,0),('2020-11-27',2020,11,27,5,0),('2020-11-28',2020,11,28,6,1),('2020-11-29',2020,11,29,7,1),('2020-11-30',2020,11,30,1,0),('2020-12-01',2020,12,1,2,0),('2020-12-02',2020,12,2,3,0),('2020-12-03',2020,12,3,4,0),('2020-12-04',2020,12,4,5,0),('2020-12-05',2020,12,5,6,1),('2020-12-06',2020,12,6,7,1),('2020-12-07',2020,12,7,1,0),('2020-12-08',2020,12,8,2,0),('2020-12-09',2020,12,9,3,0),('2020-12-10',2020,12,10,4,0),('2020-12-11',2020,12,11,5,0),('2020-12-12',2020,12,12,6,1),('2020-12-13',2020,12,13,7,1),('2020-12-14',2020,12,14,1,0),('2020-12-15',2020,12,15,2,0),('2020-12-16',2020,12,16,3,0),('2020-12-17',2020,12,17,4,0),('2020-12-18',2020,12,18,5,0),('2020-12-19',2020,12,19,6,1),('2020-12-20',2020,12,20,7,1),('2020-12-21',2020,12,21,1,0),('2020-12-22',2020,12,22,2,0),('2020-12-23',2020,12,23,3,0),('2020-12-24',2020,12,24,4,0),('2020-12-25',2020,12,25,5,0),('2020-12-26',2020,12,26,6,1),('2020-12-27',2020,12,27,7,1),('2020-12-28',2020,12,28,1,0),('2020-12-29',2020,12,29,2,0),('2020-12-30',2020,12,30,3,0),('2020-12-31',2020,12,31,4,0);
/*!40000 ALTER TABLE `calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `departmentNo` varchar(5) CHARACTER SET utf8 NOT NULL COMMENT '部门编号',
  `departmentName` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '部门名称',
  `members` int(5) NOT NULL DEFAULT 0 COMMENT '部门人数',
  `parentId` int(3) NOT NULL DEFAULT 0 COMMENT '父部门id',
  `isLeaf` int(1) NOT NULL DEFAULT 1 COMMENT '是否为叶子节点：0-非，1-是',
  `level` int(3) DEFAULT NULL,
  `remark` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `operator` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '操作者',
  `operatorId` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '操作者id',
  `create_time` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT '创建时间',
  `update_time` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'001','董事会',2,0,0,0,'一级部门，最高级','Admin','19','2020-06-30 11:11','2020-08-25 14:33'),(2,'003','塑化材料中心',0,1,0,1,'二级部门','admin','1','2020-06-30 11:11','2020-07-01 16:44'),(4,'004','策划部',12,2,1,2,'三级','admin','1','2020-06-30 11:11','2020-06-30 11:11'),(5,'005','监督办事处',0,1,1,0,'一级','admin','1','2020-06-30 11:11','2020-07-01 19:57'),(11,'006','监督会',0,1,1,NULL,NULL,NULL,NULL,'2020-07-01 14:33','2020-07-01 15:16'),(32,'009','执行部',0,33,1,NULL,NULL,'Admin','19','2020-07-21 15:27','2020-07-31 16:54'),(33,'010','技术中心',0,1,0,NULL,NULL,'Admin','19','2020-07-31 13:45','2020-07-31 16:49'),(34,'011','研发部',6,33,1,NULL,NULL,NULL,NULL,'2020-07-31 13:46','2020-07-31 13:46'),(35,'012','产品部',0,33,1,NULL,NULL,NULL,NULL,'2020-07-31 13:55','2020-07-31 13:55'),(36,'013','设计部',0,33,1,NULL,NULL,NULL,NULL,'2020-07-31 13:56','2020-07-31 13:56'),(39,'014','2C事业中心',0,33,1,NULL,NULL,'Admin','19','2020-07-31 14:27','2020-07-31 16:48');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goods`
--

DROP TABLE IF EXISTS `goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `category` int(3) NOT NULL COMMENT '物品分类',
  `goodsNo` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '物品编号',
  `goodsName` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '物品名称',
  `number` int(11) NOT NULL DEFAULT 0 COMMENT '库存数量',
  `sent` tinyint(4) NOT NULL DEFAULT 0 COMMENT '发送邮件通知标记,0:未发,1:已发',
  `applyNumber` int(11) NOT NULL DEFAULT 0 COMMENT '已申领数量',
  `unit` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT '计量单位',
  `operator` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作者',
  `operatorId` int(5) NOT NULL DEFAULT 0 COMMENT '操作者id',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '删除时间:时间存在,表示已软删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods`
--

LOCK TABLES `goods` WRITE;
/*!40000 ALTER TABLE `goods` DISABLE KEYS */;
INSERT INTO `goods` VALUES (1,1,'A001','电脑夹',108,0,60,'件','Admin',19,'2020-08-18 17:02:48','2020-08-21 14:03:00',NULL),(2,5,'B001','笔记本',45,0,5,'本','Admin',19,'2020-08-19 11:08:58','2020-08-21 14:03:00',NULL),(6,6,'C001','抽纸1',0,0,0,'卷','',0,'2020-08-21 14:18:19','2020-08-21 14:18:19',NULL),(7,6,'C002','抽纸2',0,0,0,'卷','',0,'2020-08-21 14:19:09','2020-08-21 14:19:09',NULL),(8,6,'C003','抽纸3',0,0,0,'卷','',0,'2020-08-21 14:19:09','2020-08-21 14:19:09',NULL),(10,6,'C005','抽纸5',12,0,0,'卷','',0,'2020-08-21 14:20:55','2020-08-21 14:20:55',NULL),(11,6,'C006','抽纸6',0,0,0,'卷','',0,'2020-08-21 14:20:55','2020-08-21 14:20:55',NULL),(12,6,'C007','洗手液',100,0,0,'瓶','Admin',19,'2020-08-21 18:53:29','2020-08-21 18:53:00',NULL),(13,1,'A002','鼠标',120,0,0,'个','Admin',19,'2020-08-24 13:44:51','2020-08-24 13:44:00',NULL);
/*!40000 ALTER TABLE `goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goodsCategory`
--

DROP TABLE IF EXISTS `goodsCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goodsCategory` (
  `id` int(3) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `categoryNo` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '分类编号',
  `categoryName` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '分类名称',
  `operator` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作者',
  `operatorId` int(5) NOT NULL DEFAULT 0 COMMENT '操作者id',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `categoryNo` (`categoryNo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='办公用品分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goodsCategory`
--

LOCK TABLES `goodsCategory` WRITE;
/*!40000 ALTER TABLE `goodsCategory` DISABLE KEYS */;
INSERT INTO `goodsCategory` VALUES (1,'A','电脑耗材','Admin',19,'2020-08-18 17:00:06','2020-08-25 11:14:00'),(5,'B','办公设备','Admin',19,'2020-08-19 09:34:30','2020-08-19 13:59:00'),(6,'C','生活用品','Admin',19,'2020-08-21 11:21:50','2020-08-21 11:21:00');
/*!40000 ALTER TABLE `goodsCategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goodsRecord`
--

DROP TABLE IF EXISTS `goodsRecord`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goodsRecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `goodsId` int(11) NOT NULL COMMENT '物品id',
  `handler` int(5) NOT NULL DEFAULT 0 COMMENT '经手人:关联用户',
  `operate` tinyint(4) NOT NULL COMMENT '入库/出库标记,0:出库,1:入库',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '申领状态(出库)\r\n0:申请中\r\n1:待领取\r\n2:已领取\r\n3:拒绝申请\r\n4:取消申请',
  `supplier` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '盟大前台' COMMENT '供应商',
  `number` int(5) NOT NULL COMMENT '流水数量',
  `operator` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作者',
  `operatorId` int(5) NOT NULL DEFAULT 0 COMMENT '操作者id',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='物品流水记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goodsRecord`
--

LOCK TABLES `goodsRecord` WRITE;
/*!40000 ALTER TABLE `goodsRecord` DISABLE KEYS */;
INSERT INTO `goodsRecord` VALUES (1,1,0,1,2,'',10,'',0,'2020-08-19 20:12:35','2020-08-19 20:12:35'),(2,1,21,0,0,'',5,'',0,'2020-08-19 20:12:56','2020-08-19 20:12:56'),(3,2,1,1,2,'',50,'',0,'2020-08-20 08:36:23','2020-08-20 08:36:23'),(4,2,0,0,1,'',10,'',0,'2020-08-20 08:36:29','2020-08-20 08:36:29'),(5,1,0,0,1,'',10,'',0,'2020-08-20 08:36:35','2020-08-20 08:36:35'),(6,1,21,1,2,'大易有塑',15,'',0,'2020-08-20 08:36:54','2020-08-20 08:36:54'),(7,1,0,0,1,'',10,'',0,'2020-08-20 08:37:01','2020-08-20 08:37:01'),(8,1,22,1,2,'大易有塑',20,'',0,'2020-08-20 08:37:04','2020-08-20 08:37:04'),(9,1,1,0,3,'',10,'Admin',19,'2020-08-20 08:37:07','2020-08-24 17:19:00'),(10,1,0,1,2,'',10,'',0,'2020-08-20 08:37:10','2020-08-20 08:37:10'),(11,1,0,0,2,'',10,'Admin',19,'2020-08-20 08:37:12','2020-08-24 17:15:00'),(12,1,23,1,2,'',100,'',0,'2020-08-20 08:37:14','2020-08-20 08:37:14'),(13,10,1,1,2,'雀喜易购',12,'Admin',19,'2020-08-21 20:52:48','2020-08-21 20:52:00'),(14,2,1,1,2,'大易有塑',10,'Admin',19,'2020-08-22 16:16:32','2020-08-22 16:16:00'),(15,13,21,1,2,'罗技',100,'Admin',19,'2020-08-24 13:49:49','2020-08-24 13:49:00'),(16,13,1,0,2,'盟大前台',20,'Admin',19,'2020-08-24 13:50:37','2020-08-24 17:14:00'),(17,1,1,1,2,'晨光',20,'Admin',19,'2020-08-24 14:30:19','2020-08-24 14:30:00'),(18,1,21,0,0,'盟大前台',3,'陈子繁',21,'2020-08-24 20:02:47','2020-08-24 20:02:00'),(19,1,21,0,2,'盟大前台',3,'Admin',19,'2020-08-24 20:05:50','2020-08-25 15:41:00'),(20,1,21,0,1,'盟大前台',4,'陈子繁',21,'2020-08-24 20:07:02','2020-08-25 17:57:00'),(21,1,21,0,3,'盟大前台',2,'Admin',19,'2020-08-24 20:08:40','2020-08-24 20:35:00'),(22,1,21,0,2,'盟大前台',2,'Admin',19,'2020-08-24 20:14:29','2020-08-24 20:31:00'),(23,1,21,0,3,'盟大前台',2,'Admin',19,'2020-08-24 20:14:56','2020-08-24 20:33:00'),(24,2,19,0,2,'盟大前台',5,'Admin',19,'2020-08-24 20:56:04','2020-08-24 20:56:00'),(25,2,1,0,4,'盟大前台',2,'树莺',1,'2020-08-25 09:32:21','2020-08-25 09:45:00'),(26,1,21,0,4,'盟大前台',8,'陈子繁',21,'2020-08-25 15:55:35','2020-08-25 15:55:00'),(27,12,21,1,2,'舒肤佳',100,'陈子繁',21,'2020-08-25 17:56:20','2020-08-25 17:56:00');
/*!40000 ALTER TABLE `goodsRecord` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meetingMember`
--

DROP TABLE IF EXISTS `meetingMember`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meetingMember` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `roomApplyId` int(6) NOT NULL COMMENT '会议室申请id外键',
  `userId` int(5) NOT NULL COMMENT '参会人id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meetingMember`
--

LOCK TABLES `meetingMember` WRITE;
/*!40000 ALTER TABLE `meetingMember` DISABLE KEYS */;
INSERT INTO `meetingMember` VALUES (1,1,1),(2,1,19),(3,1,20),(21,14,1),(22,14,19),(23,14,20),(24,14,21),(25,14,22),(26,14,23),(27,14,24),(28,14,25),(29,14,26),(30,14,27),(35,18,1),(36,18,21),(38,20,1),(40,22,1),(41,23,1),(42,24,19),(43,24,20),(44,24,21),(45,24,22),(46,24,23),(47,24,24),(48,24,25),(49,24,26),(50,24,27),(51,24,28),(54,26,19),(55,26,20),(56,27,19),(57,27,20),(59,29,19),(60,29,20);
/*!40000 ALTER TABLE `meetingMember` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(3) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `permissionName` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
  `method` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '方法规则',
  `module` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '所属模块',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,'岗位添加','common/position/add','公共模块','2020-07-04 11:55:36','2020-07-04 11:55:36'),(5,'后台页面','admin/index/index','后台模块','2020-07-07 10:54:00','2020-07-07 10:54:00'),(7,'会议室实体修改','room/entity/edit','会议室模块','2020-07-08 09:31:00','2020-07-08 09:31:00'),(8,'会议室实体删除','room/entity/delete','会议室模块','2020-07-08 09:59:00','2020-07-08 09:59:00'),(10,'会议室状态查看','room/apply/index','会议室模块','2020-07-08 10:05:00','2020-07-08 10:05:00'),(14,'岗位修改','common/position/edit','公共模块','2020-07-08 14:36:00','2020-07-08 14:36:00'),(18,'部门查看','common/department/index','公共模块','2020-07-13 15:50:00','2020-07-13 15:50:00'),(19,'部门添加','common/department/add','公共模块','2020-07-13 15:50:00','2020-07-13 15:50:00'),(20,'部门修改','common/department/edit','公共模块','2020-07-13 15:51:00','2020-07-13 15:51:00'),(21,'部门删除','common/department/delete','公共模块','2020-07-13 15:51:00','2020-07-13 15:51:00'),(22,'岗位查看','common/position/index','公共模块','2020-07-13 18:51:00','2020-07-13 18:51:00'),(23,'岗位删除','common/position/delete','公共模块','2020-07-13 18:58:00','2020-07-13 18:58:00'),(24,'登录','admin/identify/login','后台模块','2020-07-13 19:08:00','2020-07-13 19:08:00'),(25,'用户查看','common/user/index','公共模块','2020-07-13 19:09:00','2020-07-13 19:09:00'),(26,'用户添加','common/user/add','公共模块','2020-07-13 19:09:00','2020-07-13 19:09:00'),(27,'用户修改','common/user/edit','公共模块','2020-07-13 19:09:00','2020-07-13 19:09:00'),(28,'用户删除','common/user/delete','公共模块','2020-07-13 19:09:00','2020-07-13 19:09:00'),(29,'角色查看','common/role/index','公共模块','2020-07-14 14:27:00','2020-07-14 14:27:00'),(30,'角色添加','common/role/add','公共模块','2020-07-14 14:27:00','2020-07-14 14:27:00'),(31,'角色修改','common/role/edit','公共模块','2020-07-14 14:27:00','2020-07-14 14:27:00'),(32,'角色删除','common/role/delete','公共模块','2020-07-14 14:27:00','2020-07-14 14:27:00'),(34,'权限添加','common/permission/add','公共模块','2020-07-14 14:30:00','2020-07-14 14:30:00'),(35,'权限修改','common/permission/edit','公共模块','2020-07-14 14:30:00','2020-07-14 14:30:00'),(36,'权限删除','common/permission/delete','公共模块','2020-07-14 14:30:00','2020-07-14 14:30:00'),(38,'会议室实体查看','room/entity/index','会议室模块','2020-07-15 09:26:00','2020-07-15 09:26:00'),(39,'会议室实体添加','room/entity/add','会议室模块','2020-07-15 09:27:00','2020-07-15 09:27:00'),(40,'查看用户角色','common/user/role','公共模块','2020-07-15 11:46:00','2020-07-15 11:46:00'),(41,'会议室申请添加','room/apply/add','会议室模块','2020-07-20 17:08:00','2020-07-20 17:08:00'),(42,'会议室申请修改','room/apply/edit','会议室模块','2020-07-20 17:10:00','2020-07-20 17:10:00'),(43,'会议室申请删除','room/apply/delete','会议室模块','2020-07-20 17:12:00','2020-07-20 17:12:00'),(44,'权限查看','common/permission/index','公共模块','2020-08-03 16:06:00','2020-08-03 16:06:00'),(45,'休假查看','vacation/vacation/index','休假模块','2020-08-12 20:02:00','2020-08-12 20:02:00'),(46,'休假修改','vacation/vacation/edit','休假模块','2020-08-12 21:12:00','2020-08-12 21:12:00'),(47,'休假类型查看','vacation/vacationType/index','休假模块','2020-08-18 17:14:00','2020-08-18 17:14:00'),(48,'办公用品查看','supply/storehouse/index','办公用品模块','2020-08-25 16:04:00','2020-08-25 16:04:00'),(49,'办公用品添加','supply/storehouse/add','办公用品模块','2020-08-25 16:05:00','2020-08-25 16:05:00'),(50,'办公用品修改','supply/storehouse/edit','办公用品模块','2020-08-25 16:05:00','2020-08-25 16:05:00'),(51,'办公用品删除','supply/storehouse/delete','办公用品模块','2020-08-25 16:05:00','2020-08-25 16:05:00'),(52,'办公用品分类查看','supply/goodsCategory/index','办公用品模块','2020-08-25 16:09:00','2020-08-25 16:09:00'),(53,'办公用品分类添加','supply/goodsCategory/add','办公用品模块','2020-08-25 16:09:00','2020-08-25 16:09:00'),(54,'办公用品分类修改','supply/goodsCategory/edit','办公用品模块','2020-08-25 16:09:00','2020-08-25 16:09:00'),(55,'办公用品分类删除','supply/goodsCategory/delete','办公用品模块','2020-08-25 16:09:00','2020-08-25 16:09:00'),(56,'流水记录查看','supply/goodsRecord/index','办公用品模块','2020-08-25 16:13:00','2020-08-25 16:13:00'),(57,'办公用品入库','supply/goodsRecord/inHouse','办公用品模块','2020-08-25 16:16:00','2020-08-25 16:16:00'),(60,'办公用品申领记录删除','supply/goodsRecord/delete','办公用品模块','2020-08-25 16:17:00','2020-08-25 16:17:00'),(61,'办公用品出库审核','supply/goodsRecord/outHouse','办公用品模块','2020-08-25 16:29:00','2020-08-25 16:29:00');
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `position` (
  `id` int(3) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `departmentId` int(3) NOT NULL COMMENT '部门id',
  `positionNo` varchar(5) CHARACTER SET utf8 NOT NULL COMMENT '岗位编号',
  `positionName` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '岗位名称',
  `members` int(5) NOT NULL COMMENT '岗位人数',
  `operator` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'admin' COMMENT '操作者',
  `operatorId` int(5) NOT NULL DEFAULT 0 COMMENT '操作者id',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position`
--

LOCK TABLES `position` WRITE;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
INSERT INTO `position` VALUES (26,1,'00101','董事长',2,'admin',0,'2020-07-01 18:31:00','2020-07-01 18:31:00'),(34,4,'00401','新品推广员',8,'admin',0,'2020-07-03 14:19:00','2020-07-29 09:08:00'),(37,34,'01101','PHP工程师',5,'admin',0,'2020-07-31 17:13:00','2020-07-31 17:13:00'),(38,34,'01102','Java工程师',0,'admin',0,'2020-07-31 17:15:00','2020-07-31 17:15:00');
/*!40000 ALTER TABLE `position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(3) NOT NULL AUTO_INCREMENT COMMENT '角色表，自增id',
  `roleName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `detail` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色权限描述',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '启用状态，1:启用，0:禁用',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'部门负责人','拥有部门负责人相关权限',1,'2020-07-03 09:18:00','2020-08-13 11:38:00'),(15,'普通用户','拥有查看权限',1,'2020-07-14 17:40:11','2020-08-25 11:48:00'),(16,'超级管理员','拥有一切权限',0,'2020-08-06 09:28:36','2020-08-06 11:43:00'),(17,'组长','部门下各组组长权限',1,'2020-08-10 09:59:20','2020-08-25 18:20:00');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permission` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT '角色-权限联系表:自增id',
  `roleId` int(3) NOT NULL COMMENT '角色id外键',
  `permissionId` int(3) NOT NULL COMMENT '权限id外键',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=503 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色－权限联系表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (295,15,38,'2020-08-03 15:46:53','2020-08-03 15:46:53'),(296,15,7,'2020-08-03 15:46:53','2020-08-03 15:46:53'),(297,15,10,'2020-08-03 15:46:53','2020-08-03 15:46:53'),(298,15,25,'2020-08-03 15:46:53','2020-08-03 15:46:53'),(299,15,29,'2020-08-03 15:46:53','2020-08-03 15:46:53'),(300,15,22,'2020-08-03 15:46:53','2020-08-03 15:46:53'),(301,15,18,'2020-08-03 15:46:53','2020-08-03 15:46:53'),(474,16,7,'2020-08-06 11:38:42','2020-08-06 11:38:42'),(475,16,8,'2020-08-06 11:39:11','2020-08-06 11:39:11'),(477,16,42,'2020-08-06 11:42:39','2020-08-06 11:42:39'),(478,16,38,'2020-08-06 11:42:57','2020-08-06 11:42:57'),(479,17,38,'2020-08-10 09:59:20','2020-08-10 09:59:20'),(481,17,25,'2020-08-10 09:59:20','2020-08-10 09:59:20'),(482,17,22,'2020-08-10 09:59:20','2020-08-10 09:59:20'),(483,17,18,'2020-08-10 09:59:20','2020-08-10 09:59:20'),(484,17,5,'2020-08-10 09:59:20','2020-08-10 09:59:20'),(485,17,24,'2020-08-10 09:59:20','2020-08-10 09:59:20'),(486,17,45,'2020-08-12 20:03:15','2020-08-12 20:03:15'),(487,17,46,'2020-08-12 21:12:19','2020-08-12 21:12:19'),(490,1,45,'2020-08-13 11:38:24','2020-08-13 11:38:24'),(491,1,46,'2020-08-13 11:38:24','2020-08-13 11:38:24'),(492,1,38,'2020-08-13 11:38:24','2020-08-13 11:38:24'),(493,1,5,'2020-08-13 11:38:24','2020-08-13 11:38:24'),(494,1,24,'2020-08-13 11:38:24','2020-08-13 11:38:24'),(495,17,47,'2020-08-18 17:14:47','2020-08-18 17:14:47'),(496,17,48,'2020-08-25 16:05:50','2020-08-25 16:05:50'),(497,17,52,'2020-08-25 16:10:33','2020-08-25 16:10:33'),(498,17,56,'2020-08-25 17:45:50','2020-08-25 17:45:50'),(499,17,57,'2020-08-25 17:48:46','2020-08-25 17:48:46'),(502,17,61,'2020-08-25 18:20:39','2020-08-25 18:20:39');
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room` (
  `id` int(3) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `roomNo` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT '会议室编号',
  `roomName` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '会议室名称',
  `device` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '设备',
  `capacity` int(3) NOT NULL COMMENT '容纳人数',
  `operator` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '操作者',
  `operatorId` int(3) NOT NULL DEFAULT 0 COMMENT '操作者id',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否可用',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (1,'A1','人才招待室','投影仪',6,'Admin',19,1,'2020-07-17 17:58:46','2020-07-20 15:57:00'),(2,'A2','洽谈室','空调、茶几',2,'Admin',19,1,'2020-07-20 08:39:21','2020-07-20 15:57:00'),(3,'A3','会议室','投影仪、茶几',5,'Admin',19,1,'2020-07-20 12:27:00','2020-07-28 17:37:00'),(8,'A4','进步空间','8台笔记本电脑、投影仪',8,'Admin',19,1,'2020-07-28 17:37:00','2020-07-28 17:37:00'),(9,'A5','聚客厅','音响、麦克风、大屏幕、控制台',100,'Admin',19,1,'2020-07-29 14:05:00','2020-07-29 14:05:00');
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roomApply`
--

DROP TABLE IF EXISTS `roomApply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roomApply` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `roomId` int(3) NOT NULL COMMENT '会议室实体id外键',
  `applicant` int(5) NOT NULL COMMENT '申请人id外键',
  `theme` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '会议主题',
  `status` enum('0','1','2','3','4','5') CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '申请状态，0:申请中，1:申请通过，2:正在使用，3:已使用,4:申请过期，5:申请关闭',
  `date` date DEFAULT NULL COMMENT '预约使用日期',
  `start` time DEFAULT NULL COMMENT '申请使用的开始时间',
  `finish` time DEFAULT NULL COMMENT '申请使用的结束时间',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '申请创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '申请更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roomApply`
--

LOCK TABLES `roomApply` WRITE;
/*!40000 ALTER TABLE `roomApply` DISABLE KEYS */;
INSERT INTO `roomApply` VALUES (14,3,19,'技术大讨论','5','2020-07-31','10:00:00','18:00:00','2020-07-27 19:59:14','2020-07-27 19:59:14'),(18,3,19,'学习报告','4','2020-07-29','09:30:00','12:00:00','2020-07-29 09:22:59','2020-07-29 09:22:59'),(20,1,1,'测试邮箱','4','2020-07-29','17:10:00','18:00:00','2020-07-29 17:58:40','2020-07-29 17:58:40'),(22,1,1,'测试邮件','3','2020-07-29','21:20:00','23:00:00','2020-07-29 19:25:08','2020-07-29 19:25:08'),(23,9,1,'测试静态方法','3','2020-07-30','11:20:00','20:30:00','2020-07-30 09:22:54','2020-07-30 09:22:54'),(24,9,1,'发邮件','5','2020-07-31','13:30:00','18:00:00','2020-07-30 10:40:33','2020-07-30 10:40:33'),(26,8,1,'取消会议','1','2020-08-31','09:00:00','18:00:00','2020-07-30 15:55:46','2020-07-30 15:55:46'),(27,2,1,'技术分享','5','2020-08-31','15:00:00','18:00:00','2020-08-03 10:28:03','2020-08-03 10:28:03'),(29,9,1,'雏鸟训练营','4','2020-08-13','08:30:00','18:00:00','2020-08-11 11:26:20','2020-08-11 11:26:20');
/*!40000 ALTER TABLE `roomApply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `headImg` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'touxiang.jpg' COMMENT '用户头像',
  `userNo` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT '工号',
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户姓名',
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码（登录名）',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户登录密码',
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '企业邮箱',
  `isAdmin` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是管理员,0:非管理员,1:管理员',
  `superior` int(5) NOT NULL DEFAULT 0 COMMENT '直接上级',
  `departmentHead` int(5) NOT NULL DEFAULT 0 COMMENT '部门负责人',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态,1:启用｜0:禁用',
  `departmentId` int(3) NOT NULL COMMENT '部门id',
  `positionId` int(3) NOT NULL COMMENT '岗位id',
  `operator` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '操作者',
  `operatorId` int(5) NOT NULL DEFAULT 0 COMMENT '操作者id',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userNo` (`userNo`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'headImg_5f2cb8ad34ecb.jpg','1708','树莺','13433612409','542f5d2f3575a5828224f69a4e893d31','zhengyb@pvc123.com',0,21,22,1,34,37,'树莺',1,'2020-07-02 15:26:14','2020-08-27 15:23:00'),(19,'headImg_5f2cbebdd6554.jpg','1709','Admin','15812350438','542f5d2f3575a5828224f69a4e893d31','867512099@qq.com',1,20,20,1,1,26,'Admin',19,'2020-07-13 20:23:00','2020-08-07 17:56:00'),(20,'headImg_5f2cb84eec8f1.jpeg','0001','傻鸟','13716881688','542f5d2f3575a5828224f69a4e893d31','1599602540@qq.com',1,27,27,1,1,26,'傻鸟',20,'2020-07-20 08:43:00','2020-08-13 17:23:00'),(21,'head.jpg','1007','陈子繁','13433612002','542f5d2f3575a5828224f69a4e893d31','867512099@qq.com',0,22,22,1,34,37,'陈子繁',21,'2020-07-20 08:46:00','2020-08-25 18:07:00'),(22,'head.jpg','0007','肖欢','13433612003','542f5d2f3575a5828224f69a4e893d31','1599602540@qq.com',1,20,20,1,34,37,'肖欢',22,'2020-07-25 10:45:00','2020-08-18 11:43:00'),(23,'head.jpg','0008','郑怡斌','13433612001','542f5d2f3575a5828224f69a4e893d31','zhengyb@pvc123.com',0,21,22,1,34,37,'Admin',19,'2020-07-25 10:46:00','2020-08-10 11:48:00'),(24,'head.jpg','0009','陈七','13628922659','e10adc3949ba59abbe56e057f20f883e','chenqi@qq.com',0,0,0,1,4,0,NULL,0,'2020-07-25 10:46:00','2020-07-25 10:46:00'),(25,'head.jpg','0010','刘八','13925819225','e10adc3949ba59abbe56e057f20f883e','liuba@qq.com',0,0,0,1,4,0,NULL,0,'2020-07-25 10:50:00','2020-07-25 10:50:00'),(27,'head.jpg','0002','郑一','13925819229','e10adc3949ba59abbe56e057f20f883e','zhengyi@qq.com',0,0,0,1,4,0,NULL,0,'2020-07-25 10:52:00','2020-07-25 10:52:00'),(28,'head.jpg','0003','黄二','13433612004','542f5d2f3575a5828224f69a4e893d31','huanger@qq.com',1,0,0,1,4,0,NULL,0,'2020-07-25 10:52:00','2020-07-25 10:52:00'),(31,'head.jpg','1230','郑怡斌','15812350431','e10adc3949ba59abbe56e057f20f883e','867512099@qq.com',0,0,0,1,34,37,NULL,0,'2020-08-26 18:57:00','2020-08-26 18:57:00'),(32,'head.jpg','1001','郑怡斌','15812350439','542f5d2f3575a5828224f69a4e893d31','867512099@qq.com',0,0,0,1,4,34,NULL,0,'2020-08-26 19:31:00','2020-08-26 19:31:00'),(33,'head.jpg','1231','郑怡斌','15812350411','542f5d2f3575a5828224f69a4e893d31','867512099@qq.com',0,0,0,1,4,34,NULL,0,'2020-08-26 19:36:00','2020-08-26 19:36:00'),(34,'head.jpg','2131','郑怡斌','15812350432','542f5d2f3575a5828224f69a4e893d31','867512099@qq.com',0,0,0,1,4,34,NULL,0,'2020-08-26 19:39:00','2020-08-26 19:39:00'),(35,'head.jpg','2211','郑怡斌','15812350408','542f5d2f3575a5828224f69a4e893d31','867512099@qq.com',0,0,0,1,4,34,NULL,0,'2020-08-26 19:43:00','2020-08-26 19:43:00'),(36,'head.jpg','1021','郑怡斌','13423612409','542f5d2f3575a5828224f69a4e893d31','867512099@qq.com',0,0,0,1,4,34,NULL,0,'2020-08-26 19:49:00','2020-08-26 19:49:00'),(37,'head.jpg','1742','郑怡斌','15312350431','e10adc3949ba59abbe56e057f20f883e','867512099@qq.com',0,0,0,1,4,34,NULL,0,'2020-08-26 19:50:00','2020-08-26 19:50:00'),(38,'head.jpg','2151','郑怡斌','13812350431','542f5d2f3575a5828224f69a4e893d31','867512099@qq.com',0,0,0,1,4,34,NULL,0,'2020-08-26 20:03:00','2020-08-26 20:03:00'),(39,'head.jpg','1002','郑怡斌','13812955436','e10adc3949ba59abbe56e057f20f883e','867512099@qq.com',0,0,0,1,4,34,NULL,0,'2020-08-27 11:14:00','2020-08-27 11:14:00');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT '用户角色关系表：自增id',
  `userId` int(5) NOT NULL COMMENT '用户id',
  `roleId` int(3) NOT NULL COMMENT '角色id',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (16,19,1,'2020-07-15 10:24:17','2020-07-15 10:24:17'),(17,19,15,'2020-07-15 10:24:17','2020-07-15 10:24:17'),(22,1,15,'2020-08-07 08:59:13','2020-08-07 08:59:13'),(24,1,16,'2020-08-07 08:59:44','2020-08-07 08:59:44'),(25,21,17,'2020-08-12 19:44:43','2020-08-12 19:44:43'),(26,22,1,'2020-08-12 21:17:16','2020-08-12 21:17:16'),(27,20,1,'2020-08-13 17:25:02','2020-08-13 17:25:02'),(28,20,17,'2020-08-13 17:25:02','2020-08-13 17:25:02'),(29,22,16,'2020-08-18 10:02:55','2020-08-18 10:02:55');
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacation`
--

DROP TABLE IF EXISTS `vacation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacation` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `applicant` int(5) NOT NULL COMMENT '申请人:用户id',
  `agent` int(5) NOT NULL COMMENT '代理人:用户id',
  `type` int(11) NOT NULL COMMENT '请假类型',
  `reason` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '请假原因',
  `refuseReason` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '拒绝理由',
  `start` datetime NOT NULL DEFAULT current_timestamp() COMMENT '休假开始时间',
  `end` datetime NOT NULL DEFAULT current_timestamp() COMMENT '休假结束时间',
  `days` float NOT NULL DEFAULT 0.5 COMMENT '请假天数',
  `superior` int(5) NOT NULL DEFAULT 0 COMMENT '直接上级',
  `superiorCheckStatus` tinyint(4) NOT NULL DEFAULT 0 COMMENT '直接上级审核结果,0:未审核,1:同意,2:拒绝',
  `superiorCheckTime` datetime DEFAULT NULL COMMENT '直接上级审核时间',
  `departmentHead` int(5) NOT NULL DEFAULT 0 COMMENT '部门负责人',
  `departmentHeadCheckStatus` tinyint(4) NOT NULL DEFAULT 0 COMMENT '部门负责人审核结果,0:未审核,1:同意,2:拒绝',
  `departmentHeadCheckTime` datetime DEFAULT NULL COMMENT '部门负责人审核时间',
  `presentCheck` int(5) NOT NULL DEFAULT 0 COMMENT '当前审核人',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '申请状态,0:申请中,1:申请成功,2:拒绝申请,3:取消申请,4:申请过期',
  `operator` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '操作人',
  `operatorId` int(5) NOT NULL DEFAULT 0 COMMENT '操作人id',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='休假记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacation`
--

LOCK TABLES `vacation` WRITE;
/*!40000 ALTER TABLE `vacation` DISABLE KEYS */;
INSERT INTO `vacation` VALUES (1,1,1,1,'肚子疼','忍一忍','2020-08-07 12:00:00','2020-08-08 18:00:00',1.5,21,1,'2020-08-17 10:56:00',22,2,'2020-08-17 10:56:00',22,2,'肖欢',22,'2020-08-07 20:28:49','2020-08-17 10:56:00'),(2,23,1,2,'老婆待产期,大师傅的房间卡还是得尽快发货加快速度换房间卡回来就见客户的放假啊可是对方','你都没有老婆','2020-08-20 08:30:00','2020-08-29 18:00:00',0.5,21,2,'2020-08-14 20:57:00',22,1,'2020-08-14 20:10:00',21,2,'陈子繁',21,'2020-08-10 11:51:03','2020-08-14 20:57:00'),(4,1,1,1,'心情低落','','2020-08-17 12:00:00','2020-08-17 18:00:00',0.56,21,1,'2020-08-17 10:13:00',22,1,'2020-08-17 10:29:00',22,1,'树莺',1,'2020-08-13 11:50:35','2020-08-17 16:27:00'),(5,1,1,2,'女朋友待产期','你都没女朋友','2020-08-16 13:28:00','2020-08-29 18:00:00',10,21,1,'2020-08-17 16:38:00',22,2,'2020-08-17 16:43:00',22,2,'肖欢',22,'2020-08-13 13:29:39','2020-08-17 16:43:00'),(7,1,21,1,'心情有点嗨',NULL,'2020-08-18 16:00:00','2020-08-18 18:00:00',0.25,21,0,NULL,22,0,NULL,21,4,'陈子繁',21,'2020-08-18 14:24:09','2020-08-18 14:24:00'),(8,21,21,2,'老婆产二',NULL,'2020-08-18 18:00:00','2020-08-20 18:00:00',2,22,0,NULL,22,0,NULL,22,4,'陈子繁',21,'2020-08-18 15:48:07','2020-08-18 15:48:00'),(9,19,19,1,'心情好好',NULL,'2020-08-28 13:30:00','2020-08-28 18:00:00',0.56,20,0,NULL,20,0,NULL,20,0,'Admin',19,'2020-08-27 15:03:28','2020-08-27 15:03:00');
/*!40000 ALTER TABLE `vacation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacationType`
--

DROP TABLE IF EXISTS `vacationType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacationType` (
  `id` int(3) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `typeNo` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '请假类型编号',
  `typeName` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '请假类型名称',
  `operator` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作者',
  `operatorId` int(5) NOT NULL COMMENT '操作者id',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `typeNo` (`typeNo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='请假类型';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacationType`
--

LOCK TABLES `vacationType` WRITE;
/*!40000 ALTER TABLE `vacationType` DISABLE KEYS */;
INSERT INTO `vacationType` VALUES (1,'xqj','心情假','Admin',19,'2020-08-17 18:20:06','2020-08-18 10:40:00'),(2,'pcj','陪产假','Admin',19,'2020-08-17 18:20:06','2020-08-18 11:09:00'),(6,'sj','事假','Admin',19,'2020-08-18 11:08:57','2020-08-18 11:08:00');
/*!40000 ALTER TABLE `vacationType` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-28  8:32:47
