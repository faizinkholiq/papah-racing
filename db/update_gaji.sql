ALTER TABLE `gaji` ADD `year` INT(4) NULL AFTER `id`, ADD `month` INT(2) NULL AFTER `year`; 
ALTER TABLE `gaji` ADD `process` BOOLEAN NOT NULL DEFAULT FALSE AFTER `jabatan`; 