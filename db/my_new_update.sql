-- 2022-12-07
ALTER TABLE `penjualan` ADD `tipe_bayar` ENUM('Cash', 'Transfer') NULL AFTER `status`;

ALTER TABLE `penjualan` ADD `daily` BOOLEAN NULL AFTER `updated`;

UPDATE `penjualan` SET daily = false WHERE DATE(tanggal) = CURRENT_DATE;