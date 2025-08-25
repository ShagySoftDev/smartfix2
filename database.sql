CREATE DATABASE IF NOT EXISTS smartfix;
USE smartfix;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('ceo','engineer') NOT NULL
);

-- Insert CEO (username: ceo, password: Abubakar@00)
INSERT IGNORE INTO users (username,password,role) VALUES ('ceo','Abubakar@00','ceo');

-- Repairs
CREATE TABLE IF NOT EXISTS repairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer VARCHAR(100),
    phone VARCHAR(20),
    device VARCHAR(100),
    model VARCHAR(100),
    imei VARCHAR(50),
    issue TEXT,
    cond_text TEXT,
    cost DECIMAL(10,2),
    status ENUM('Received','In Progress','Completed') DEFAULT 'Received',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Complaints
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    engineer VARCHAR(100),
    complaint TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inventory
CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    part_name VARCHAR(100),
    compatible_with VARCHAR(100),
    stock INT DEFAULT 0,
    price DECIMAL(10,2) DEFAULT 0.00
);

-- Invoices (basic; ties to repairs)
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repair_id INT,
    subtotal DECIMAL(10,2),
    tax DECIMAL(10,2),
    total DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repair_id) REFERENCES repairs(id) ON DELETE SET NULL
);
