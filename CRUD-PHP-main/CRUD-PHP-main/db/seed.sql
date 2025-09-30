-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: trabalho
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `aluno`
--
-- ORDER BY:  `id`

LOCK TABLES `aluno` WRITE;
/*!40000 ALTER TABLE `aluno` DISABLE KEYS */;
INSERT INTO `aluno` VALUES (7,2025001,NULL),(8,2025002,NULL),(9,2025003,NULL);
/*!40000 ALTER TABLE `aluno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `bairro`
--
-- ORDER BY:  `id`

LOCK TABLES `bairro` WRITE;
/*!40000 ALTER TABLE `bairro` DISABLE KEYS */;
INSERT INTO `bairro` VALUES (1,'Centro',1),(2,'Jardim Paulista',1),(3,'Copacabana',2);
/*!40000 ALTER TABLE `bairro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cidade`
--
-- ORDER BY:  `id`

LOCK TABLES `cidade` WRITE;
/*!40000 ALTER TABLE `cidade` DISABLE KEYS */;
INSERT INTO `cidade` VALUES (1,'Ribeirao Preto',1),(2,'Rio de Janeiro',2);
/*!40000 ALTER TABLE `cidade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `curso`
--
-- ORDER BY:  `id`

LOCK TABLES `curso` WRITE;
/*!40000 ALTER TABLE `curso` DISABLE KEYS */;
INSERT INTO `curso` VALUES (1,'Direito'),(2,'Medicina'),(3,'Engenharia de Software');
/*!40000 ALTER TABLE `curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `disciplina`
--
-- ORDER BY:  `id`

LOCK TABLES `disciplina` WRITE;
/*!40000 ALTER TABLE `disciplina` DISABLE KEYS */;
INSERT INTO `disciplina` VALUES (2,'Programacao Web II',13),(3,'Leis trabalhistas',21),(4,'Anatomia',25);
/*!40000 ALTER TABLE `disciplina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `endereco`
--
-- ORDER BY:  `id`

LOCK TABLES `endereco` WRITE;
/*!40000 ALTER TABLE `endereco` DISABLE KEYS */;
INSERT INTO `endereco` VALUES (1,'Rua General Osorio, 120','Ap 101','14010000',1),(2,'Av. Independencia, 850','Casa','14020000',2),(3,'Av. Atlantica, 1702','Cobertura','22021000',3);
/*!40000 ALTER TABLE `endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fatura`
--
-- ORDER BY:  `id`

LOCK TABLES `fatura` WRITE;
/*!40000 ALTER TABLE `fatura` DISABLE KEYS */;
INSERT INTO `fatura` VALUES (1,'FAT-0001',1200.50,'2025-10-10',0,'Mensalidade',23),(2,'FAT-0002',800.00,'2025-10-15',0,'Material',23),(3,'FAT-0003',500.00,'2025-10-20',1,'Estorno',24);
/*!40000 ALTER TABLE `fatura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fisica`
--
-- ORDER BY:  `id`

LOCK TABLES `fisica` WRITE;
/*!40000 ALTER TABLE `fisica` DISABLE KEYS */;
INSERT INTO `fisica` VALUES (10,'F','Feminino','Pardo'),(12,'M','Masculino','Branco'),(13,'F','Outro','NÆo Informar');
/*!40000 ALTER TABLE `fisica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `funcionario`
--
-- ORDER BY:  `id`

LOCK TABLES `funcionario` WRITE;
/*!40000 ALTER TABLE `funcionario` DISABLE KEYS */;
INSERT INTO `funcionario` VALUES (20,'2023-01-02',3500.00),(21,'2022-11-10',4200.50),(22,'2021-05-20',5100.00);
/*!40000 ALTER TABLE `funcionario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `gerente`
--
-- ORDER BY:  `id`

LOCK TABLES `gerente` WRITE;
/*!40000 ALTER TABLE `gerente` DISABLE KEYS */;
INSERT INTO `gerente` VALUES (23),(24);
/*!40000 ALTER TABLE `gerente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `juridica`
--
-- ORDER BY:  `id`

LOCK TABLES `juridica` WRITE;
/*!40000 ALTER TABLE `juridica` DISABLE KEYS */;
INSERT INTO `juridica` VALUES (14,'12345678000190',123456789012,123456789012,'2015-03-10','Unaerp',6201),(15,'11223344000155',987654321000,987654321000,'2018-07-22','The Spot',7020),(16,'99887766000111',5424123141431231,543210987654,'2020-01-15','SubWay',4711);
/*!40000 ALTER TABLE `juridica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `pagamento`
--
-- ORDER BY:  `id`

LOCK TABLES `pagamento` WRITE;
/*!40000 ALTER TABLE `pagamento` DISABLE KEYS */;
INSERT INTO `pagamento` VALUES (1,1200.50,'2025-10-08',1),(2,800.00,'2025-10-13',2),(3,500.00,'2025-10-19',3);
/*!40000 ALTER TABLE `pagamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `pessoa`
--
-- ORDER BY:  `id`

LOCK TABLES `pessoa` WRITE;
/*!40000 ALTER TABLE `pessoa` DISABLE KEYS */;
INSERT INTO `pessoa` VALUES (2,'Joao Silva','123456789','987654321'),(3,'Arthur','(41) 2781-0798','(16)99323-8914'),(4,'Luciano','(77) 3772-7789','11975863571'),(5,'Robertinho','(96) 3827-1642','(19) 99125-5562'),(6,'Cristiano Ronaldo','(43) 3278-2452','(19) 99125-5562'),(7,'Daniel','1133334444','11911112222'),(8,'Cleiton','1135556666','11922223333'),(9,'Felipe','1137778888','11933334444'),(10,'Gabriela','1144445555','11955556666'),(11,'Gabriela','1144445555','11955556666'),(12,'Henrique','1146667777','11966667777'),(13,'Julia','1148889999','11977778888'),(14,'Unaerp Education','1132100000','11990000001'),(15,'The Spot Residence','1132200000','11990000002'),(16,'SubWay Comercio','1132300000','11990000003'),(17,'Samuel','1151111111','11911110000'),(18,'Carlos','1152222222','11922220000'),(19,'Lucas','1153333333','11933330000'),(20,'Andreia','1161111111','11944440000'),(21,'Thais','1162222222','11955550000'),(22,'Rafa','1163333333','11966660000'),(23,'Gerente Teste','1130001000','11990000001'),(24,'Gerente Vital','1130002000','11990000002');
/*!40000 ALTER TABLE `pessoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `professor`
--
-- ORDER BY:  `id`

LOCK TABLES `professor` WRITE;
/*!40000 ALTER TABLE `professor` DISABLE KEYS */;
INSERT INTO `professor` VALUES (17,'2022-02-01','Direito'),(18,'2021-03-15','Medicina'),(19,'2020-08-10','Engenharia de Software');
/*!40000 ALTER TABLE `professor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `turma`
--
-- ORDER BY:  `id`

LOCK TABLES `turma` WRITE;
/*!40000 ALTER TABLE `turma` DISABLE KEYS */;
INSERT INTO `turma` VALUES (4,'2025-10-01 08:00:00',60,3),(5,'2025-10-02 14:00:00',80,3),(6,'2025-10-03 19:00:00',40,3);
/*!40000 ALTER TABLE `turma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `uf`
--
-- ORDER BY:  `id`

LOCK TABLES `uf` WRITE;
/*!40000 ALTER TABLE `uf` DISABLE KEYS */;
INSERT INTO `uf` VALUES (1,'SP','Brasil'),(2,'RJ','Brasil');
/*!40000 ALTER TABLE `uf` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-30  1:13:06
