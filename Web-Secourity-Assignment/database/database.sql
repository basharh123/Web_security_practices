CREATE DATABASE IF NOT EXISTS web_security_db;
USE web_security_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- إضافة مستخدم تجريبي (كلمة المرور: Admin123!)
INSERT INTO users (username, email, password_hash, full_name) 
VALUES ('admin', 'admin@example.com', '$2y$10$YourHashHere', 'مدير النظام');
