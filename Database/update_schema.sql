-- Update database schema for Gender Distribution in Event Categories
USE `bucuc`;

-- Add event category and gender tracking to members table
ALTER TABLE `members` 
ADD COLUMN `event_category` ENUM('Music', 'Dance', 'Drama', 'Art', 'Poetry') NOT NULL DEFAULT 'Music' AFTER `membership_status`,
ADD COLUMN `gender_tracking` ENUM('Male', 'Female', 'Other') NOT NULL DEFAULT 'Male' AFTER `event_category`;

-- Create a view for gender distribution analytics
CREATE OR REPLACE VIEW `gender_distribution_view` AS
SELECT 
    event_category,
    gender_tracking as gender,
    COUNT(*) as count,
    ROUND((COUNT(*) * 100.0 / SUM(COUNT(*)) OVER (PARTITION BY event_category)), 2) as percentage
FROM members 
WHERE status = 'active'
GROUP BY event_category, gender_tracking
ORDER BY event_category, gender_tracking;

-- Create a summary view for dashboard
CREATE OR REPLACE VIEW `event_gender_summary` AS
SELECT 
    event_category,
    SUM(CASE WHEN gender_tracking = 'Male' THEN 1 ELSE 0 END) as male_count,
    SUM(CASE WHEN gender_tracking = 'Female' THEN 1 ELSE 0 END) as female_count,
    SUM(CASE WHEN gender_tracking = 'Other' THEN 1 ELSE 0 END) as other_count,
    COUNT(*) as total_count
FROM members 
WHERE status = 'active'
GROUP BY event_category
ORDER BY total_count DESC;

-- Insert sample data for testing
UPDATE members SET 
    event_category = 'Music',
    gender_tracking = 'Male'
WHERE email = 'john.doe@bracu.ac.bd';

-- Add more sample data
INSERT INTO `members` (
    `full_name`, `university_id`, `email`, `password`, `department`, 
    `phone`, `semester`, `gender`, `date_of_birth`, `membership_status`, 
    `event_category`, `gender_tracking`, `motivation`
) VALUES 
('Sarah Ahmed', '2021-01-01-002', 'sarah.ahmed@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Computer Science and Engineering', '+8801234567891', '4th', 'Female', '2001-05-15', 'Current Member', 'Dance', 'Female', 'I love dancing and want to showcase my talent.'),
('Rahim Khan', '2021-01-01-003', 'rahim.khan@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Electrical and Electronic Engineering', '+8801234567892', '6th', 'Male', '2000-08-20', 'Current Member', 'Drama', 'Male', 'I want to explore my acting skills.'),
('Fatima Begum', '2021-01-01-004', 'fatima.begum@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'English and Humanities', '+8801234567893', '3rd', 'Female', '2002-03-10', 'Current Member', 'Art', 'Female', 'I am passionate about painting and drawing.'),
('Imran Hossain', '2021-01-01-005', 'imran.hossain@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mathematics and Natural Sciences', '+8801234567894', '5th', 'Male', '2000-12-25', 'Current Member', 'Poetry', 'Male', 'I write poetry and want to share my work.'),
('Aisha Rahman', '2021-01-01-006', 'aisha.rahman@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Business Administration', '+8801234567895', '2nd', 'Female', '2003-07-08', 'Current Member', 'Music', 'Female', 'I play guitar and want to perform.'),
('Zain Ali', '2021-01-01-007', 'zain.ali@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Computer Science and Engineering', '+8801234567896', '4th', 'Male', '2001-09-14', 'Current Member', 'Dance', 'Male', 'I am a hip-hop dancer.'),
('Nadia Islam', '2021-01-01-008', 'nadia.islam@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Economics and Social Sciences', '+8801234567897', '3rd', 'Female', '2002-01-30', 'Current Member', 'Drama', 'Female', 'I love theater and acting.'),
('Omar Farooq', '2021-01-01-009', 'omar.farooq@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Architecture', '+8801234567898', '5th', 'Male', '2000-11-05', 'Current Member', 'Art', 'Male', 'I am interested in digital art and design.'),
('Layla Chowdhury', '2021-01-01-010', 'layla.chowdhury@bracu.ac.bd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'English and Humanities', '+8801234567899', '4th', 'Female', '2001-04-22', 'Current Member', 'Poetry', 'Female', 'I write Bengali poetry and want to recite.');

-- Show the updated structure
DESCRIBE `members`;

-- Show sample data
SELECT `event_category`, `gender_tracking`, COUNT(*) as count 
FROM `members` 
WHERE status = 'active' 
GROUP BY `event_category`, `gender_tracking` 
ORDER BY `event_category`, `gender_tracking`; 