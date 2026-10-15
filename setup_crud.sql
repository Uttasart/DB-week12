-- รันใน phpMyAdmin ฐานข้อมูล s68160218 (ฐานของไฟล์หลัก)
-- ไม่มี DROP และไม่แตะตาราง profile / education เดิม

-- 1) ถ้าเคยสร้างตารางชื่อ Skill (ตัว S ใหญ่) ไว้แล้ว ให้ลบเครื่องหมาย -- หน้าบรรทัดนี้เพื่อเปลี่ยนเป็นตัวเล็ก
-- RENAME TABLE `Skill` TO `skill`;

-- 2) สร้างตารางถ้ายังไม่มี
CREATE TABLE IF NOT EXISTS skill (
  id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  skill_name VARCHAR(120) NOT NULL,
  skill_level VARCHAR(50),
  CONSTRAINT fk_skill_profile FOREIGN KEY (profile_id) REFERENCES profile(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS training (
  id INT AUTO_INCREMENT PRIMARY KEY,
  profile_id INT NOT NULL,
  course_name VARCHAR(200) NOT NULL,
  organization VARCHAR(200),
  training_year YEAR,
  CONSTRAINT fk_training_profile FOREIGN KEY (profile_id) REFERENCES profile(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) ค่า skill_level ที่หน้า CRUD รับได้มี 3 ค่า: Beginner, Intermediate, Advanced
--    ถ้าข้อมูลเดิมใช้คำอื่น ให้ปรับให้ตรง เช่น
-- UPDATE skill SET skill_level = 'Intermediate' WHERE skill_level NOT IN ('Beginner','Intermediate','Advanced');
