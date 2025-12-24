-- =====================================================
-- CYBER CELL - 23 TABLE SCHEMA (21 SPECIFIC THANAS)
-- =====================================================

CREATE DATABASE cybercell_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cybercell_db;

-- 1. LOGIN_CREDENTIALS (Shared for all users)
CREATE TABLE login_credentials (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('ADMIN','CYBER_USER','CEIR_USER') NOT NULL,
    full_name VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- CYBER COMPLAINTS - 21 SPECIFIC THANAS
-- =====================================================

-- BASE CYBER TABLE (all thanas copy this structure)
CREATE TABLE kotwali_cyber (
    sno INT AUTO_INCREMENT PRIMARY KEY,
    complaint_number VARCHAR(100),
    applicant_name VARCHAR(255),
    acknowledgement_number VARCHAR(100),
    nature_of_fraud VARCHAR(255),
    incident_date DATE,
    complaint_date DATE NOT NULL,
    total_fraud DECIMAL(15,2) DEFAULT 0,
    hold_date DATE,
    hold_amount DECIMAL(15,2) DEFAULT 0,
    refund_amount DECIMAL(15,2) DEFAULT 0,
    fraud_mobile_number VARCHAR(15),
    fraud_imei_number VARCHAR(15),
    block_or_unblock ENUM('BLOCK','UNBLOCK') DEFAULT 'UNBLOCK',
    digital_arrest INT DEFAULT 0,
    digital_amount DECIMAL(15,2) DEFAULT 0,
    mobile_number VARCHAR(15),
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES login_credentials(user_id)
);

-- COPY STRUCTURE FOR ALL 20 REMAINING CYBER THANAS
CREATE TABLE industrial_area_cyber LIKE kotwali_cyber;
CREATE TABLE bank_note_press_cyber LIKE kotwali_cyber;
CREATE TABLE civil_line_cyber LIKE kotwali_cyber;
CREATE TABLE nahar_darwaja_cyber LIKE kotwali_cyber;
CREATE TABLE vijayganj_mandi_cyber LIKE kotwali_cyber;
CREATE TABLE sonkatch_cyber LIKE kotwali_cyber;
CREATE TABLE pipalrawan_cyber LIKE kotwali_cyber;
CREATE TABLE bhaurasa_cyber LIKE kotwali_cyber;
CREATE TABLE tonkkhurd_cyber LIKE kotwali_cyber;
CREATE TABLE bagli_cyber LIKE kotwali_cyber;
CREATE TABLE hatpiplya_cyber LIKE kotwali_cyber;
CREATE TABLE barotha_cyber LIKE kotwali_cyber;
CREATE TABLE udai_nagar_cyber LIKE kotwali_cyber;
CREATE TABLE khategaon_cyber LIKE kotwali_cyber;
CREATE TABLE kannod_cyber LIKE kotwali_cyber;
CREATE TABLE kantaphod_cyber LIKE kotwali_cyber;
CREATE TABLE nemawar_cyber LIKE kotwali_cyber;
CREATE TABLE harangaon_cyber LIKE kotwali_cyber;
CREATE TABLE satwas_cyber LIKE kotwali_cyber;
CREATE TABLE kamlapur_cyber LIKE kotwali_cyber;

-- =====================================================
-- CEIR FORMS - 21 SPECIFIC THANAS (CEIR_Thana_Name)
-- =====================================================

CREATE TABLE ceir_kotwali (
    ceir_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    father_name VARCHAR(255),
    address TEXT,
    date_of_complaint DATE NOT NULL,
    mobile_number VARCHAR(15),
    imei VARCHAR(15) NOT NULL,
    lost_found ENUM('LOST','FOUND') NOT NULL,
    block_unblock ENUM('BLOCK','UNBLOCK') NOT NULL,
    pdf_attach VARCHAR(500),
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES login_credentials(user_id)
);

-- COPY STRUCTURE FOR ALL 20 REMAINING CEIR THANAS
CREATE TABLE ceir_industrial_area LIKE ceir_kotwali;
CREATE TABLE ceir_bank_note_press LIKE ceir_kotwali;
CREATE TABLE ceir_civil_line LIKE ceir_kotwali;
CREATE TABLE ceir_nahar_darwaja LIKE ceir_kotwali;
CREATE TABLE ceir_vijayganj_mandi LIKE ceir_kotwali;
CREATE TABLE ceir_sonkatch LIKE ceir_kotwali;
CREATE TABLE ceir_pipalrawan LIKE ceir_kotwali;
CREATE TABLE ceir_bhaurasa LIKE ceir_kotwali;
CREATE TABLE ceir_tonkkhurd LIKE ceir_kotwali;
CREATE TABLE ceir_bagli LIKE ceir_kotwali;
CREATE TABLE ceir_hatpiplya LIKE ceir_kotwali;
CREATE TABLE ceir_barotha LIKE ceir_kotwali;
CREATE TABLE ceir_udai_nagar LIKE ceir_kotwali;
CREATE TABLE ceir_khategaon LIKE ceir_kotwali;
CREATE TABLE ceir_kannod LIKE ceir_kotwali;
CREATE TABLE ceir_kantaphod LIKE ceir_kotwali;
CREATE TABLE ceir_nemawar LIKE ceir_kotwali;
CREATE TABLE ceir_harangaon LIKE ceir_kotwali;
CREATE TABLE ceir_satwas LIKE ceir_kotwali;
CREATE TABLE ceir_kamlapur LIKE ceir_kotwali;

-- =====================================================
-- SAMPLE USERS (for all thanas)
-- =====================================================
INSERT INTO login_credentials (email, password_hash, role, full_name) VALUES
('admin@cybercell.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', 'Cyber Cell Admin'),
('kotwali.officer@cyber.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CYBER_USER', 'Kotwali Cyber Officer'),
('kotwali.ceir@cyber.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CEIR_USER', 'Kotwali CEIR Officer');
-- Password for all: 'password' (change in production!)
