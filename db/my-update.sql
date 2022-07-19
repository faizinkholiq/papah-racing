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
CREATE TABLE `papah_racing`.`banner` ( `id` INT NOT NULL AUTO_INCREMENT , `photo` VARCHAR(100) NOT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `banner` ADD `order_no` INT NOT NULL AFTER `photo`;
INSERT INTO `banner` (`id`, `photo`, `order_no`) VALUES (NULL, 's1.jpeg', 1), (NULL, 's2.jpeg', 2), (NULL, 's3.jpeg', 3), (NULL, 's4.jpeg', 4), (NULL, 's5.jpeg', 5);

-- 26 Juni 2022
CREATE TABLE `merk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `merk` (`id`, `name`) VALUES
(1, 'ABC'),
(2, 'ABRT'),
(3, 'ACCOSSATO'),
(4, 'ADNOC'),
(5, 'AHAU'),
(6, 'AHM'),
(7, 'AHRS'),
(8, 'AITECH'),
(9, 'ALTECO'),
(10, 'APPLE'),
(11, 'ARROW'),
(12, 'ARS'),
(13, 'ART'),
(14, 'ASDS'),
(15, 'B-PRO'),
(16, 'BARUW'),
(17, 'BOSCH'),
(18, 'BOYESEN'),
(19, 'BREMBO'),
(20, 'CARDINAL'),
(21, 'CASTROL'),
(22, 'CMS'),
(23, 'CRG'),
(24, 'DAYTONA'),
(25, 'DENSO'),
(26, 'DG PERFORMANCE'),
(27, 'DKT'),
(28, 'DOMINO '),
(29, 'DRAGY'),
(30, 'ELF'),
(31, 'ES.2'),
(32, 'EXCEL TAKASAGO'),
(33, 'EXCEL TAKASAHO'),
(34, 'EXTREME '),
(35, 'FAG'),
(36, 'FAITO'),
(37, 'FIM '),
(38, 'FOREDOM'),
(39, 'GALFER'),
(40, 'GARYSON'),
(41, 'GAZY'),
(42, 'GFORCE'),
(43, 'GRIP-ON'),
(44, 'HONDA'),
(45, 'HRC'),
(46, 'IBC '),
(47, 'IKK'),
(48, 'IPONE'),
(49, 'IRC'),
(50, 'JFK '),
(51, 'KABEL SETAN'),
(52, 'KAWAHARA '),
(53, 'KAWASAKI'),
(54, 'KEIHIN'),
(55, 'KNALPOT RACING MERCHANDISE'),
(56, 'KNALPOT RACING MERCHANDISEA'),
(57, 'KNALPOT RACING PRODUK'),
(58, 'KTC'),
(59, 'KTC KYTACO '),
(60, 'KTM'),
(61, 'KYB '),
(62, 'LECTRON'),
(63, 'LHK'),
(64, 'MAGIC BOY'),
(65, 'MAGICBOY'),
(66, 'MAGURA'),
(67, 'MAXIMA'),
(68, 'MCS'),
(69, 'MIKUNI'),
(70, 'MOTION PRO'),
(71, 'MOTO1'),
(72, 'MOTOPLAT'),
(73, 'MOTOTASSINARI'),
(74, 'MOTUL'),
(75, 'NAKANISHI'),
(76, 'NEWAY'),
(77, 'NGK'),
(78, 'QSTARZ'),
(79, 'QTT'),
(80, 'RACEN'),
(81, 'RIK'),
(82, 'SHARK'),
(83, 'SHIJIRO'),
(84, 'SKF '),
(85, 'SMARTCARB'),
(86, 'SPR'),
(87, 'SPS'),
(88, 'SSS'),
(89, 'SUDCO '),
(90, 'SUZUKI '),
(91, 'SWEDIA'),
(92, 'SYS'),
(93, 'TAMA'),
(94, 'THAN ANT'),
(95, 'TOP'),
(96, 'TY'),
(97, 'UMA'),
(98, 'UN;ABEL'),
(99, 'UNABEL '),
(100, 'UNLABEL'),
(101, 'VND'),
(102, 'VRG'),
(103, 'WANO.INC'),
(104, 'YAMAHA'),
(105, 'YOSHIMURA'),
(106, 'YYPANG');

-- 28 Juni 2022
ALTER TABLE `kontak` ADD `letak` ENUM('footer','order') NULL AFTER `kontak`, ADD `aktif` BOOLEAN NOT NULL DEFAULT FALSE AFTER `letak`;

-- 4 Juli 2022
ALTER TABLE `user` ADD `aktif` BOOLEAN NOT NULL DEFAULT FALSE AFTER `last_login`;
UPDATE `user` SET aktif = 1 WHERE DATEDIFF(NOW(), last_login ) <= 30;

-- 19 Juli 2022
ALTER TABLE `barang` ADD `deleted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `updated`;