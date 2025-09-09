-- MySQL Script (fixed version, only mydb)
-- Fixed to add AUTO_INCREMENT for primary keys and move users to mydb

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
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
  PRIMARY KEY (`Id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`locatie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`locatie` (
  `Id` INT NOT NULL AUTO_INCREMENT,
  `Naam` VARCHAR(45) NULL,
  PRIMARY KEY (`Id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`locatie_has_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`locatie_has_product` (
  `locatie_Id` INT NOT NULL,
  `product_Id` INT NOT NULL,
  `Aantal` INT NULL,
  `WaardeInkoop` DECIMAL(6,2) NULL,
  `WaardeVerkoop` DECIMAL(6,2) NULL,
  PRIMARY KEY (`locatie_Id`, `product_Id`),
  INDEX `fk_locatie_has_product_product1_idx` (`product_Id` ASC) VISIBLE,
  INDEX `fk_locatie_has_product_locatie_idx` (`locatie_Id` ASC) VISIBLE,
  CONSTRAINT `fk_locatie_has_product_locatie`
    FOREIGN KEY (`locatie_Id`)
    REFERENCES `mydb`.`locatie` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_locatie_has_product_product1`
    FOREIGN KEY (`product_Id`)
    REFERENCES `mydb`.`product` (`Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`bestelling`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`bestelling` (
  `Id` INT NOT NULL AUTO_INCREMENT,
  `bestellingsdatum` DATE NULL,
  `aankomstdatumverwacht` DATE NULL,
  `ontvangen` TINYINT NULL,
  `locatie_besteld` INT NOT NULL,
  `product_besteld` INT NOT NULL,
  PRIMARY KEY (`Id`, `locatie_besteld`, `product_besteld`),
  INDEX `fk_bestelling_locatie_has_product1_idx` (`locatie_besteld` ASC, `product_besteld` ASC) VISIBLE,
  CONSTRAINT `fk_bestelling_locatie_has_product1`
    FOREIGN KEY (`locatie_besteld` , `product_besteld`)
    REFERENCES `mydb`.`locatie_has_product` (`locatie_Id` , `product_Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE
) ENGINE = InnoDB
AUTO_INCREMENT = 1;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
