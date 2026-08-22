-- Run this once to create the database and table
CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  standard VARCHAR(20) NOT NULL,
  dob DATE NOT NULL,
  age INT NOT NULL,
  father_name VARCHAR(100) NOT NULL,
  father_mobile VARCHAR(10) NOT NULL,
  email VARCHAR(150) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
