-- timetable_db.sql
CREATE DATABASE IF NOT EXISTS timetable_db;
USE timetable_db;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS courses (
  course_id INT AUTO_INCREMENT PRIMARY KEY,
  semester INT NOT NULL,
  course_name VARCHAR(100),
  teacher VARCHAR(100),
  room VARCHAR(50),
  day VARCHAR(20),
  start_time TIME,
  end_time TIME
);

-- Insert default admin (username: admin, password: 12345)
INSERT INTO admins (username, password) VALUES ('admin', '12345');
