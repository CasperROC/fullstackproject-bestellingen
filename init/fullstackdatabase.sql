-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema zingusDB
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema zingusDB
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `zingusDB` ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`product` (
  `Id` INT NOT NULL AUTO_INCREMENT,
  `Naam` VARCHAR(45) NULL,
  `Typey` VARCHAR(45) NULL,
  `Fabriek` VARCHAR(45) NULL,
  `Prijs` DECIMAL(6,2) NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`locatie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`locatie` (
  `Id` INT NOT NULL AUTO_INCREMENT,
  `Naam` VARCHAR(45) NULL,
  PRIMARY KEY (`Id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`locatie_has_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`locatie_has_product` (
  `Aantal` INT NULL,
  `WaardeInkoop` DECIMAL(6,2) NULL,
  `WaardeVerkoop` DECIMAL(6,2) NULL,
  `locatie_Id1` INT NOT NULL,
  `product_Id1` INT NOT NULL,
  PRIMARY KEY (`locatie_Id1`, `product_Id1`),
  INDEX `fk_locatie_has_product_locatie1_idx` (`locatie_Id1` ASC) VISIBLE,
  INDEX `fk_locatie_has_product_product1_idx` (`product_Id1` ASC) VISIBLE,
  CONSTRAINT `fk_locatie_has_product_locatie1`
    FOREIGN KEY (`locatie_Id1`)
    REFERENCES `mydb`.`locatie` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_locatie_has_product_product1`
    FOREIGN KEY (`product_Id1`)
    REFERENCES `mydb`.`product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`bestelling`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`bestelling` (
  `Id` INT NOT NULL AUTO_INCREMENT,
  `bestellingsdatum` DATE NULL DEFAULT CURRENT_DATE,
  `aankomstdatumverwacht` DATE NULL DEFAULT CURRENT_DATE,
  `ontvangen` TINYINT NULL DEFAULT 0,
  `aantalbesteld` INT NOT NULL,
  `locatieID_besteld` INT NOT NULL,
  `productID_besteld` INT NOT NULL,
  PRIMARY KEY (`Id`, `locatieID_besteld`, `productID_besteld`),
  INDEX `fk_bestelling_locatie_idx` (`locatieID_besteld` ASC) VISIBLE,
  INDEX `fk_bestelling_product1_idx` (`productID_besteld` ASC) VISIBLE,
  CONSTRAINT `fk_bestelling_locatie`
    FOREIGN KEY (`locatieID_besteld`)
    REFERENCES `mydb`.`locatie` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bestelling_product1`
    FOREIGN KEY (`productID_besteld`)
    REFERENCES `mydb`.`product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE)
ENGINE = InnoDB;

USE `zingusDB` ;

-- -----------------------------------------------------
-- Table `zingusDB`.`zorblo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zingusDB`.`zorblo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(50) NOT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `age` INT(3) NOT NULL,
  `hotdogseaten` INT(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
