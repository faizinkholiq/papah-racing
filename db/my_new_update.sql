-- 2022-12-07
ALTER TABLE `penjualan` ADD `tipe_bayar` ENUM('Cash', 'Transfer') NULL AFTER `status`;