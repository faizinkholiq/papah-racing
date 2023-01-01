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