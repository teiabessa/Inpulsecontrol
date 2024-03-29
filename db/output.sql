-- MySQL Script generated by MySQL Workbench
-- 02/28/15 22:10:38
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema control
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema control
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `control` DEFAULT CHARACTER SET utf8 ;
USE `control` ;

-- -----------------------------------------------------
-- Table `control`.`Type_Service`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `control`.`Type_Service` (
  `tyse_seq` INT NOT NULL AUTO_INCREMENT COMMENT '0=Foto, 1=design, 2=Micro, 3=Estética Facial, 4=Micropuntura, 5=Alongamente cílios,\n6=Radiofrequência\n',
  `type_parent_seq` INT NOT NULL COMMENT 'Categoriza os tratamentos',
  `tyse_desc` VARCHAR(50) NULL COMMENT 'Descricao do tratamento',
  PRIMARY KEY (`tyse_seq`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `control`.`Client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `control`.`Client` (
  `client_seq` INT(11) NOT NULL AUTO_INCREMENT,
  `client_name` VARCHAR(100) NOT NULL,
  `client_cpf` VARCHAR(50) NULL DEFAULT NULL,
  `client_rg` VARCHAR(30) NULL DEFAULT NULL,
  `client_address` VARCHAR(250) NULL DEFAULT NULL,
  `client_email` VARCHAR(50) NULL DEFAULT NULL,
  `client_telefone_fixo` VARCHAR(30) NULL DEFAULT NULL,
  `client_telefone_cel1` VARCHAR(30) NULL DEFAULT NULL,
  `client_activate` INT(11) NULL DEFAULT NULL,
  `client_data_ultimo_atend` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`client_seq`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `control`.`Profissional`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `control`.`Profissional` (
  `prof_seq` INT(11) NOT NULL AUTO_INCREMENT,
  `prof_name` VARCHAR(100) NOT NULL,
  `prof_type` INT(11) NOT NULL DEFAULT '0' COMMENT '0=Proprietaria,1=registro , 2=MEI, 3=Estagiaria,4=Terceiro',
  `prof_valor_comissao` INT(11) NOT NULL DEFAULT '5',
  PRIMARY KEY (`prof_seq`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Lista de Funcionarios da Clinica';


-- -----------------------------------------------------
-- Table `control`.`Pacote_Servico_Cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `control`.`Pacote_Servico_Cliente` (
  `secl_seq` INT NOT NULL AUTO_INCREMENT,
  `secl_client_seq` INT NOT NULL,
  `secl_prof_seq` INT NULL,
  `secl_valor_vendido` VARCHAR(15) NULL,
  `secl_qtd_sessao_realizada` INT NULL COMMENT 'É atualizado conforme feito as sessoes automaticamente( por triguer)',
  `secl_contrato_seq` INT NULL,
  `secl_status_pagto` INT NULL COMMENT '0=Pago\n1=Parcelado',
  PRIMARY KEY (`secl_seq`),
  CONSTRAINT `fk_secl_client_seq`
    FOREIGN KEY (`secl_client_seq`)
    REFERENCES `control`.`Client` (`client_seq`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `fk_secl_client_seq_idx` ON `control`.`Pacote_Servico_Cliente` (`secl_client_seq` ASC);


-- -----------------------------------------------------
-- Table `control`.`Agenda`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `control`.`Agenda` (
  `agenda_seq` INT(11) NOT NULL,
  `agenda_sala` INT(11) NULL COMMENT '1,2,3,4',
  `agenda_start_time` DATETIME NOT NULL,
  `agenda_end_time` DATETIME NOT NULL,
  `agenda_prof_seq` INT(11) NULL COMMENT 'profissional da sessao',
  `agenda_tyse_seq` INT(11) NULL COMMENT 'foreign key para o procedimento',
  `agenda_secl_seq` INT(11) NULL DEFAULT NULL COMMENT 'foreign key para o pacote contratado nessa sessao',
  `agenda_outro_procedimento` VARCHAR(100) NULL COMMENT 'proedimento da sessao',
  `agenda_concluded` INT(1) NULL DEFAULT NULL COMMENT 'Se foi concluida a sessao',
  `agenda_tipo_pagto` INT(1) NULL DEFAULT NULL COMMENT '1=Cartao, 2=Dinheiro, 3=cheque',
  `agenda_client_seq` INT NULL,
  `agenda_avulso` INT(1) NULL DEFAULT 0 COMMENT '0=avulso, 1=pacote',
  PRIMARY KEY (`agenda_seq`),
  CONSTRAINT `fk_tyse_seq`
    FOREIGN KEY (`agenda_tyse_seq`)
    REFERENCES `control`.`Type_Service` (`tyse_seq`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_client_seq`
    FOREIGN KEY (`agenda_client_seq`)
    REFERENCES `control`.`Client` (`client_seq`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_profissional`
    FOREIGN KEY (`agenda_prof_seq`)
    REFERENCES `control`.`Profissional` (`prof_seq`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_agenda_secl_seq`
    FOREIGN KEY (`agenda_secl_seq`)
    REFERENCES `control`.`Pacote_Servico_Cliente` (`secl_seq`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Quadro de agendamento da clinica';

CREATE INDEX `fk_tyse_seq_idx` ON `control`.`Agenda` (`agenda_tyse_seq` ASC);

CREATE INDEX `fk_client_seq_idx` ON `control`.`Agenda` (`agenda_client_seq` ASC);

CREATE INDEX `fk_profissional_idx` ON `control`.`Agenda` (`agenda_prof_seq` ASC);

CREATE INDEX `fk_agenda_secl_seq_idx` ON `control`.`Agenda` (`agenda_secl_seq` ASC);


-- -----------------------------------------------------
-- Table `control`.`Pacotes_Type_Services`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `control`.`Pacotes_Type_Services` (
  `peqi_secl_seq` INT NOT NULL,
  `peqi_type_seq` INT NOT NULL,
  `peqi_qtd_sessoes` INT NULL DEFAULT 10,
  CONSTRAINT `fk_type_seq`
    FOREIGN KEY (`peqi_type_seq`)
    REFERENCES `control`.`Type_Service` (`tyse_seq`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk1_secl_seq`
    FOREIGN KEY (`peqi_secl_seq`)
    REFERENCES `control`.`Pacote_Servico_Cliente` (`secl_seq`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `fk_type_seq_idx` ON `control`.`Pacotes_Type_Services` (`peqi_type_seq` ASC);

CREATE INDEX `fk1_secl_seq_idx` ON `control`.`Pacotes_Type_Services` (`peqi_secl_seq` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `control`.`Type_Service`
-- -----------------------------------------------------
START TRANSACTION;
USE `control`;
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (0, 0, 'Tratamentos Faciais');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (1, 0, 'Design de Sobrancelhas');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (2, 0, 'Alongamento de Cílios');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (3, 0, 'Micropigmentação');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (4, 0, 'Limpeza de Pele');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (5, 0, 'Hidratação Facial');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (6, 0, 'Radiofrequência Facial');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (7, 7, 'Fotodepilação');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (8, 7, 'Áxila');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (9, 7, 'Buço');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (10, 7, 'Virilha');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (11, 7, '1/2 Perna');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (12, 7, 'Coxa');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (13, 7, 'PA');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (14, 7, 'Rosto');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (15, 7, 'Braço');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (16, 7, 'Mãos');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (17, 7, 'Pés');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (18, 7, 'Linha Alba');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (19, 19, 'Tratamentos Corporais');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (20, 19, 'Massagem Relaxante');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (21, 19, 'Drenagem Linfática');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (22, 19, 'Massagem Modeladora');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (23, 19, 'Radiofrequência');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (24, 19, 'Heccus');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (25, 19, 'Vacumterapia');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (26, 19, 'Sonofocus');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (27, 19, 'Oligoterapia');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (28, 0, 'Peeling de Cristal');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (29, 0, 'Peeling de Diamante');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (30, 0, 'Micropuntura');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (31, 0, 'Peeling Químico');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (32, 0, 'Aplicação de ácido');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (33, 0, 'Vulcanaise');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (34, 0, 'FotoRejuvenecimento');
INSERT INTO `control`.`Type_Service` (`tyse_seq`, `type_parent_seq`, `tyse_desc`) VALUES (, DEFAULT, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `control`.`Client`
-- -----------------------------------------------------
START TRANSACTION;
USE `control`;
INSERT INTO `control`.`Client` (`client_seq`, `client_name`, `client_cpf`, `client_rg`, `client_address`, `client_email`, `client_telefone_fixo`, `client_telefone_cel1`, `client_activate`, `client_data_ultimo_atend`) VALUES (1, 'Elaine Pereira', '878533333', '30333', 'Av. Jk, 123', 'elaine@bol.com.br', '34344-333', '988888', 1, '15/11/2014');
INSERT INTO `control`.`Client` (`client_seq`, `client_name`, `client_cpf`, `client_rg`, `client_address`, `client_email`, `client_telefone_fixo`, `client_telefone_cel1`, `client_activate`, `client_data_ultimo_atend`) VALUES (2, 'Maria Alice Campos', '343434', '44444', 'rua pedro frigi, 344', 'maria@hotmail.com', '3949-1234', '98453-2334', 1, '12/01/2015');
INSERT INTO `control`.`Client` (`client_seq`, `client_name`, `client_cpf`, `client_rg`, `client_address`, `client_email`, `client_telefone_fixo`, `client_telefone_cel1`, `client_activate`, `client_data_ultimo_atend`) VALUES (3, 'Bernadete Costa', '3444', '3453453636', 'Rua rui Nelson', 'bernadete@uol.com.br', '35453-333', '988777', NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `control`.`Profissional`
-- -----------------------------------------------------
START TRANSACTION;
USE `control`;
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (1, 'Alessandra Brandão', 0, 0);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (2, 'Dilian Hills', 1, 10);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (3, 'Maria José', 2, 40);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (4, 'Cristiane Honrório', 2, 40);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (5, 'Thaís', 3, 5);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (6, 'Jéssica', 3, 5);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (7, 'Cláudia', 3, 5);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (8, 'Letícia', 1, 0);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (9, 'Dra Mônica', 4, NULL);
INSERT INTO `control`.`Profissional` (`prof_seq`, `prof_name`, `prof_type`, `prof_valor_comissao`) VALUES (10, 'Izana', 4, 70);

COMMIT;


-- -----------------------------------------------------
-- Data for table `control`.`Agenda`
-- -----------------------------------------------------
START TRANSACTION;
USE `control`;
INSERT INTO `control`.`Agenda` (`agenda_seq`, `agenda_sala`, `agenda_start_time`, `agenda_end_time`, `agenda_prof_seq`, `agenda_tyse_seq`, `agenda_secl_seq`, `agenda_outro_procedimento`, `agenda_concluded`, `agenda_tipo_pagto`, `agenda_client_seq`, `agenda_avulso`) VALUES (0, 1, '02/03/2015 08:00', '02/03/2015 09:00', 2, 1, NULL, NULL, NULL, 2, 1, NULL);
INSERT INTO `control`.`Agenda` (`agenda_seq`, `agenda_sala`, `agenda_start_time`, `agenda_end_time`, `agenda_prof_seq`, `agenda_tyse_seq`, `agenda_secl_seq`, `agenda_outro_procedimento`, `agenda_concluded`, `agenda_tipo_pagto`, `agenda_client_seq`, `agenda_avulso`) VALUES (1, 2, '03/03/2015 10:00', '03/03/2015 11:00', 3, 22, NULL, NULL, NULL, 1, 2, NULL);
INSERT INTO `control`.`Agenda` (`agenda_seq`, `agenda_sala`, `agenda_start_time`, `agenda_end_time`, `agenda_prof_seq`, `agenda_tyse_seq`, `agenda_secl_seq`, `agenda_outro_procedimento`, `agenda_concluded`, `agenda_tipo_pagto`, `agenda_client_seq`, `agenda_avulso`) VALUES (2, 3, '03/03/2015 13:00', '03/03/2015 14:00', 4, 22, NULL, NULL, NULL, NULL, 3, NULL);

COMMIT;

