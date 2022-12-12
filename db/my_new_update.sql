-- 2022-12-07
ALTER TABLE `penjualan` ADD `tipe_bayar` ENUM('Cash', 'Transfer') NULL AFTER `status`;

ALTER TABLE `penjualan` ADD `daily` BOOLEAN NULL AFTER `updated`;

UPDATE `penjualan` SET daily = false WHERE DATE(tanggal) = CURRENT_DATE;

ALTER TABLE `penjualan` CHANGE `tipe_bayar` `tipe_bayar` ENUM('Cash','Transfer','MarketPlace') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;