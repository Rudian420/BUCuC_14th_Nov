

CREATE TABLE `members` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(255) NOT NULL ,
    `university_id` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `gsuite_email` VARCHAR(100) NULL,
    `department` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `semester` VARCHAR(20) NOT NULL,
    `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
    `date_of_birth` DATE NOT NULL,
    `facebook_url` VARCHAR(255) NULL,
    `firstPriority` VARCHAR(255) NOT NULL DEFAULT '1st Not Selected',
    `secondPriority` VARCHAR(255) NOT NULL DEFAULT '2nd Not Selected',
    `membership_status` ENUM('Old_member', 'New_member') NOT NULL DEFAULT 'New_member',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)


CREATE TABLE IF NOT EXISTS signup_status (
    id INT PRIMARY KEY DEFAULT 1,
    is_enabled TINYINT(1) NOT NULL DEFAULT 1,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by VARCHAR(100) DEFAULT NULL
)


CREATE TABLE IF NOT EXISTS `venuInfo`(
    `venue_id` INT AUTO_INCREMENT PRIMARY KEY,
    `venue_name` VARCHAR(255) NOT NULL,
    `venue_location` VARCHAR(255) NOT NULL,
    `venue_dateTime` DATETIME NOT NULL,
    `venue_startingTime` VARCHAR(10) NOT NULL,
    `venue_endingTime` VARCHAR(10) NOT NULL
)

ALTER TABLE `venuInfo` ADD COLUMN `venu_ampm` VARCHAR(2) NOT NULL DEFAULT 'PM';