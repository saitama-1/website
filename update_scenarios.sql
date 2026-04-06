-- 1. Tạo bảng scenario
CREATE TABLE IF NOT EXISTS `scenario` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ten` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `mo_ta` TEXT,
    `thu_tu` INT DEFAULT 0,
    `hien_thi` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Thêm cột scenario_id vào bảng series
ALTER TABLE `series` ADD COLUMN `scenario_id` INT NULL AFTER `ho_san_pham_id`;
ALTER TABLE `series` ADD CONSTRAINT `series_scenario_fk` FOREIGN KEY (`scenario_id`) REFERENCES `scenario`(`id`) ON DELETE SET NULL;
ALTER TABLE `series` ADD INDEX `idx_series_scenario` (`scenario_id`);

-- 3. Chèn dữ liệu Scenario
INSERT INTO `scenario` (`ten`, `slug`, `thu_tu`) VALUES 
('SMB', 'smb', 1),
('Enterprise', 'enterprise', 2),
('Data Center', 'data-center', 3),
('Industrial', 'industrial', 4);

-- 4. Ánh xạ Series vào Scenario (Dựa trên prompt.txt)

-- SMB
UPDATE `series` SET `scenario_id` = (SELECT id FROM `scenario` WHERE slug = 'smb') 
WHERE `ten` IN (
    'Cisco Business 350 Series Managed Switches',
    'Cisco Business 220 Series Switches',
    'Cisco Business 110 Series Unmanaged Switches'
);

-- Industrial
UPDATE `series` SET `scenario_id` = (SELECT id FROM `scenario` WHERE slug = 'industrial') 
WHERE `ten` IN (
    'Catalyst IE9300 Rugged Series',
    'Cisco Catalyst ESS9300 Embedded Series Switches',
    'Cisco Catalyst IE3400 Heavy Duty Series',
    'Cisco Catalyst IE3400 Rugged Series',
    'Cisco Catalyst IE3300 Rugged Series',
    'Cisco Catalyst IE3200 Rugged Series',
    'Cisco Catalyst IE3100 Heavy Duty Series',
    'Cisco Catalyst IE3100 Rugged Series',
    'Cisco Embedded Services 3300 Series Switches',
    'Cisco IE3500 Heavy Duty Series',
    'Cisco IE3500 Rugged Series',
    'Cisco Industrial Ethernet 4010 Series Switches',
    'Cisco Industrial Ethernet 2000U Series Switches',
    'Cisco Industrial Ethernet 1000 Series Switches'
);

-- Data Center
UPDATE `series` SET `scenario_id` = (SELECT id FROM `scenario` WHERE slug = 'data-center') 
WHERE `ten` IN (
    'Cisco 6000 Series Switches',
    'Cisco N9300 Series Smart Switches',
    'Cisco Nexus 9000 Series Switches',
    'Cisco Nexus 7000 Series Switches',
    'Cisco Nexus 3550 Series',
    'Cisco Nexus 3000 Series Switches'
);

-- Enterprise
UPDATE `series` SET `scenario_id` = (SELECT id FROM `scenario` WHERE slug = 'enterprise') 
WHERE `ten` IN (
    'Cisco C9350 Series Smart Switches',
    'Cisco Catalyst 9400 Series Switches - modular access',
    'Cisco Catalyst 9300 Series Switches',
    'Cisco Catalyst 9200 Series Switches',
    'Cisco Catalyst 1300 Series Switches',
    'Cisco Catalyst 1200 Series Managed Switches',
    'Cisco Meraki Cloud Managed Switches'
);
