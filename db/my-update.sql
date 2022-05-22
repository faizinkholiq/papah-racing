-- 20 April 2022
ALTER TABLE `barang` CHANGE `kategori` `kategori` SET('MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','PISTON','KOPLING') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;

-- 28 April 2022
ALTER TABLE `barang` ADD `deskripsi` TEXT NULL AFTER `kategori`;

CREATE TABLE `papah_racing`.`kontak` ( `id` INT NOT NULL AUTO_INCREMENT , `keterangan` VARCHAR(100) NULL , `kontak` VARCHAR(100) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `papah_racing`.`socmed` ( `id` INT NOT NULL AUTO_INCREMENT , `keterangan` VARCHAR(100) NULL , `tipe` ENUM('Instagram','Twitter','Youtube','Facebook') NULL , `link` VARCHAR(255) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

INSERT INTO `socmed` (`id`, `keterangan`, `tipe`, `link`) VALUES ('1', 'Papah Racing Speedshop', 'Instagram', 'https://www.instagram.com/papahracingspeedshop/'), ('2', 'Knalpot Racing Speedshop', 'Instagram', 'https://www.instagram.com/knalpot_racing.com_speedshop/'), ('3', 'Knalpot Racing Speedshop', 'Youtube', 'https://www.youtube.com/channel/UCOKpjvUiNM-em4j7-_4XxlA/');

INSERT INTO `kontak` (`id`, `keterangan`, `kontak`) VALUES (NULL, 'Order via website', 'Order via website'), (NULL, 'Order via instagram', '082124118766'), (NULL, 'Info expedisi partai', '081385595070'), (NULL, 'Info resi dan pengiriman', '081385595013 / 081385595085'), (NULL, 'Manager', '081385595027'), (NULL, 'Founder', '087877481465');

-- 3 Mei 2022
ALTER TABLE `barang` CHANGE `kategori` `kategori` SET('MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','PISTON','KOPLING','GEARBOX','MEMBRAN','INSTAKE MANIPOL','BUSI','VARIASI','SPECIAL DISKON') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

-- 7 Mei 2022
ALTER TABLE `barang` CHANGE `kategori` `kategori` SET('MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','PISTON','KOPLING','GEARBOX','MEMBRAN','INTAKE MANIPOL','BUSI','VARIASI','PAKING (GASKET)','SPECIAL DISKON') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

-- 12 Mei 2022
ALTER TABLE `barang` ADD `tipe_pelanggan` ENUM('DISTRIBUTOR') NULL DEFAULT NULL AFTER `kategori`;

ALTER TABLE `barang` CHANGE `kategori` `kategori` SET('MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','PISTON','KOPLING','GEARBOX','MEMBRAN','INTAKE MANIPOL','BUSI','VARIASI','PAKING (GASKET)','BEARING','SPECIAL DISKON') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

-- 16 Mei 2022
CREATE TABLE `papah_racing`.`foto_barang` ( `id_barang` INT NOT NULL , `name` VARCHAR(255) NOT NULL ) ENGINE = InnoDB;

ALTER TABLE `foto_barang` ADD UNIQUE( `id_barang`);

ALTER TABLE `barang` CHANGE `kualitas` `kualitas` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;

ALTER TABLE `barang` CHANGE `tipe_pelanggan` `tipe_pelanggan` ENUM('DISTRIBUTOR','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

ALTER TABLE `barang` ADD `berat` VARCHAR(50) NULL AFTER `tipe_pelanggan`;

-- 21 Mei 2022
CREATE TABLE `papah_racing`.`banner` ( `id` INT NOT NULL AUTO_INCREMENT , `photo` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `banner` (`id`, `photo`, `order_no`) VALUES (NULL, 's1.jpeg', 1), (NULL, 's2.jpeg', 2), (NULL, 's3.jpeg', 3), (NULL, 's4.jpeg', 4), (NULL, 's5.jpeg', 5);
ALTER TABLE `banner` ADD `order_no` INT NOT NULL AFTER `photo`;