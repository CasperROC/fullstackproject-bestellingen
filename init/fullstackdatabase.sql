-- MySQL/MariaDB Script (fixed version, only mydb)
-- Includes trigger to auto-fill aankomstdatumverwacht

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, 
    SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

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
  INDEX `fk_locatie_has_product_product1_idx` (`product_Id` ASC),
  INDEX `fk_locatie_has_product_locatie_idx` (`locatie_Id` ASC),
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
  `bestellingsdatum` DATE NULL DEFAULT (CURRENT_DATE),
  `aankomstdatumverwacht` DATE NULL,
  `ontvangen` TINYINT NULL DEFAULT 0,
  `locatieID_besteld` INT NOT NULL,
  `productID_besteld` INT NOT NULL,
  PRIMARY KEY (`Id`, `locatieID_besteld`, `productID_besteld`),
  INDEX `fk_bestelling_locatie_has_product1_idx` (`locatieID_besteld` ASC, `productID_besteld` ASC),
  CONSTRAINT `fk_bestelling_locatie_has_product1`
    FOREIGN KEY (`locatieID_besteld`, `productID_besteld`)
    REFERENCES `mydb`.`locatie_has_product` (`locatie_Id`, `product_Id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Trigger for bestelling (MariaDB compatible)
-- -----------------------------------------------------
DELIMITER //

CREATE TRIGGER trg_bestelling_set_aankomst
BEFORE INSERT ON `bestelling`
FOR EACH ROW
BEGIN
  -- If no bestellingsdatum, use today
  IF NEW.bestellingsdatum IS NULL THEN
    SET NEW.bestellingsdatum = CURRENT_DATE();
  END IF;

  -- If no aankomstdatumverwacht, set to +2 days
  IF NEW.aankomstdatumverwacht IS NULL THEN
    SET NEW.aankomstdatumverwacht = DATE_ADD(NEW.bestellingsdatum, INTERVAL 2 DAY);
  END IF;
END;
//

DELIMITER ;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC)
) ENGINE = InnoDB
AUTO_INCREMENT = 1;

-- -----------------------------------------------------
-- Restore settings
-- -----------------------------------------------------
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
