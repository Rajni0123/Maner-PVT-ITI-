-- Staff salary module (run once on existing installs)
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS staff (
  id INT AUTO_INCREMENT PRIMARY KEY,
  employee_code VARCHAR(50) NULL UNIQUE,
  name VARCHAR(191) NOT NULL,
  designation VARCHAR(191) NOT NULL,
  department VARCHAR(191) NULL,
  date_of_joining DATE NULL,
  bank_name VARCHAR(191) NULL,
  account_number VARCHAR(50) NULL,
  pan_number VARCHAR(20) NULL,
  pf_number VARCHAR(50) NULL,
  mobile VARCHAR(20) NULL,
  email VARCHAR(191) NULL,
  address TEXT NULL,
  basic_salary DECIMAL(12,2) DEFAULT 0,
  hra DECIMAL(12,2) DEFAULT 0,
  da DECIMAL(12,2) DEFAULT 0,
  other_allowances DECIMAL(12,2) DEFAULT 0,
  pf_deduction DECIMAL(12,2) DEFAULT 0,
  esi_deduction DECIMAL(12,2) DEFAULT 0,
  tax_deduction DECIMAL(12,2) DEFAULT 0,
  other_deductions DECIMAL(12,2) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS staff_salary_slips (
  id INT AUTO_INCREMENT PRIMARY KEY,
  staff_id INT NOT NULL,
  slip_month TINYINT NOT NULL,
  slip_year INT NOT NULL,
  working_days INT DEFAULT 30,
  paid_days INT DEFAULT 30,
  basic_salary DECIMAL(12,2) DEFAULT 0,
  hra DECIMAL(12,2) DEFAULT 0,
  da DECIMAL(12,2) DEFAULT 0,
  other_allowances DECIMAL(12,2) DEFAULT 0,
  pf_deduction DECIMAL(12,2) DEFAULT 0,
  esi_deduction DECIMAL(12,2) DEFAULT 0,
  tax_deduction DECIMAL(12,2) DEFAULT 0,
  other_deductions DECIMAL(12,2) DEFAULT 0,
  gross_pay DECIMAL(12,2) DEFAULT 0,
  total_deductions DECIMAL(12,2) DEFAULT 0,
  net_pay DECIMAL(12,2) DEFAULT 0,
  slip_number VARCHAR(50) NULL,
  notes TEXT NULL,
  generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE,
  UNIQUE KEY uniq_staff_month (staff_id, slip_month, slip_year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
