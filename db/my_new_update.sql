-- 2022-12-07
ALTER TABLE `penjualan` ADD `tipe_bayar` ENUM('Cash', 'Transfer') NULL AFTER `status`;

ALTER TABLE `penjualan` ADD `daily` BOOLEAN NULL AFTER `updated`;

UPDATE `penjualan` SET daily = false WHERE DATE(tanggal) = CURRENT_DATE;

-- 2022-12-13
ALTER TABLE `penjualan` CHANGE `tipe_bayar` `tipe_bayar` ENUM('Cash','Transfer','MarketPlace') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

-- 2023-01-02
CREATE TABLE `market_place` (`id` INT NOT NULL AUTO_INCREMENT , `tipe` ENUM('Blibli','Bukalapak','Lazada','Shopee','Tokopedia') NOT NULL , `link` VARCHAR(100) NOT NULL , `keterangan` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `market_place` ADD `order_no` INT NULL AFTER `keterangan`;

ALTER TABLE `kontak` ADD `order_no` INT NULL AFTER `aktif`;

-- 2023-01-10
CREATE TABLE `barang_temp` (`id` INT NOT NULL AUTO_INCREMENT , `barcode` VARCHAR(100) NOT NULL , `nama` VARCHAR(100) NOT NULL , `merk` VARCHAR(50) NOT NULL , `stok` INT(11) NOT NULL , `modal` INT(15) NOT NULL , `distributor` INT(15) NOT NULL , `reseller` INT(15) NOT NULL , `bengkel` INT(15) NOT NULL , `admin` INT(15) NOT NULL , `het` INT(15) NOT NULL , `kondisi` VARCHAR(50) NOT NULL , `kualitas` VARCHAR(50) NULL , `kategori` ENUM('MESIN','OLI','SASIS','PENGAPIAN','ALAT PORTING','APPAREL','KARBURATOR','KNALPOT','PISTON','KOPLING','GEARBOX','MEMBRAN','INTAKE MANIPOL','BUSI','VARIASI','PAKING (GASKET)','BEARING','SPECIAL DISKON') NULL , `tipe_pelanggan` ENUM('DISTRIBUTOR','') NULL , `berat` VARCHAR(50) NULL , `deskripsi` TEXT NULL , `tambahan` VARCHAR(50) NOT NULL , `id_barang` INT NULL , `action` ENUM('create','update','delete') NOT NULL , `updated_by` INT(4) NOT NULL , `update_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `papah_racing`.`foto_barang_temp` (`id` INT NOT NULL , `name` VARCHAR(255) NOT NULL ) ENGINE = InnoDB;

-- 2023-01-13
ALTER TABLE `barang_temp` ADD `status` ENUM('Pending','Decline','Approved') NOT NULL AFTER `action`;

ALTER TABLE `barang_temp` CHANGE `update_at` `updated_at` DATETIME NOT NULL;