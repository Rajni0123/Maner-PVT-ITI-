-- Maner Pvt ITI - MySQL Schema
-- Run via install.php or phpMyAdmin

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(191) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(191) NULL,
  phone VARCHAR(50) NULL,
  avatar VARCHAR(255) NULL,
  role VARCHAR(50) DEFAULT 'admin',
  permissions TEXT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  father_name VARCHAR(191) NOT NULL,
  mother_name VARCHAR(191) NULL,
  mobile VARCHAR(20) NOT NULL,
  email VARCHAR(191) NULL,
  trade VARCHAR(100) NOT NULL,
  qualification VARCHAR(255) NOT NULL,
  category VARCHAR(50) NOT NULL,
  documents TEXT NOT NULL,
  status VARCHAR(50) DEFAULT 'Pending',
  uidai_number VARCHAR(20) NULL,
  village_town_city VARCHAR(191) NULL,
  nearby VARCHAR(191) NULL,
  police_station VARCHAR(191) NULL,
  post_office VARCHAR(191) NULL,
  district VARCHAR(191) NULL,
  pincode VARCHAR(10) NULL,
  block VARCHAR(191) NULL,
  state VARCHAR(191) NULL,
  pwd_category VARCHAR(100) NULL,
  pwd_claim VARCHAR(10) DEFAULT 'No',
  class_10th_school VARCHAR(255) NULL,
  class_10th_marks_obtained VARCHAR(20) NULL,
  class_10th_total_marks VARCHAR(20) NULL,
  class_10th_percentage VARCHAR(20) NULL,
  class_10th_subject VARCHAR(191) NULL,
  class_12th_school VARCHAR(255) NULL,
  class_12th_marks_obtained VARCHAR(20) NULL,
  class_12th_total_marks VARCHAR(20) NULL,
  class_12th_percentage VARCHAR(20) NULL,
  class_12th_subject VARCHAR(191) NULL,
  session VARCHAR(50) NULL,
  shift VARCHAR(50) NULL,
  dob VARCHAR(20) NULL,
  gender VARCHAR(20) NULL,
  student_credit_card VARCHAR(10) DEFAULT 'No',
  student_credit_card_details TEXT NULL,
  registration_type VARCHAR(100) DEFAULT 'Regular',
  state_registration VARCHAR(100) NULL,
  central_registration VARCHAR(100) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admission_id INT NULL,
  student_name VARCHAR(191) NOT NULL,
  father_name VARCHAR(191) NULL,
  mother_name VARCHAR(191) NULL,
  mobile VARCHAR(20) NOT NULL,
  email VARCHAR(191) NULL,
  trade VARCHAR(100) NOT NULL,
  enrollment_number VARCHAR(100) NULL UNIQUE,
  admission_date DATE NULL,
  qualification VARCHAR(255) NULL,
  category VARCHAR(50) NULL,
  address TEXT NULL,
  photo VARCHAR(255) NULL,
  status VARCHAR(50) DEFAULT 'Active',
  academic_year VARCHAR(50) NULL,
  uidai_number VARCHAR(20) NULL,
  village_town_city VARCHAR(191) NULL,
  nearby VARCHAR(191) NULL,
  police_station VARCHAR(191) NULL,
  post_office VARCHAR(191) NULL,
  district VARCHAR(191) NULL,
  pincode VARCHAR(10) NULL,
  block VARCHAR(191) NULL,
  state VARCHAR(191) NULL,
  pwd_category VARCHAR(100) NULL,
  pwd_claim VARCHAR(10) NULL,
  class_10th_school VARCHAR(255) NULL,
  class_10th_marks_obtained VARCHAR(20) NULL,
  class_10th_total_marks VARCHAR(20) NULL,
  class_10th_percentage VARCHAR(20) NULL,
  class_10th_subject VARCHAR(191) NULL,
  class_12th_school VARCHAR(255) NULL,
  class_12th_marks_obtained VARCHAR(20) NULL,
  class_12th_total_marks VARCHAR(20) NULL,
  class_12th_percentage VARCHAR(20) NULL,
  class_12th_subject VARCHAR(191) NULL,
  session VARCHAR(50) NULL,
  shift VARCHAR(50) NULL,
  mis_iti_code VARCHAR(50) DEFAULT 'PR10001156',
  declaration_agreed TINYINT(1) DEFAULT 0,
  dob VARCHAR(20) NULL,
  gender VARCHAR(20) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  pdf VARCHAR(255) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  trade VARCHAR(100) NOT NULL,
  year VARCHAR(20) NOT NULL,
  pdf VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_name VARCHAR(50) NOT NULL UNIQUE,
  start_year INT NOT NULL,
  end_year INT NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  email VARCHAR(191) NOT NULL,
  phone VARCHAR(50) NOT NULL,
  message TEXT NOT NULL,
  inquiry_type VARCHAR(100) DEFAULT 'Admission Inquiry',
  trade_interest VARCHAR(100) NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS site_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT NOT NULL,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS menus (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  url VARCHAR(255) NOT NULL,
  icon VARCHAR(50) NULL,
  parent_id INT NULL,
  order_index INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(100) NOT NULL UNIQUE,
  description TEXT NULL,
  parent_id INT NULL,
  order_index INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS hero_section (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255) NULL,
  description TEXT NULL,
  background_image VARCHAR(255) NULL,
  cta_text VARCHAR(100) NULL,
  cta_link VARCHAR(255) NULL,
  cta2_text VARCHAR(100) NULL,
  cta2_link VARCHAR(255) NULL,
  is_active TINYINT(1) DEFAULT 1,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS student_fees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admission_id INT NULL,
  student_name VARCHAR(191) NOT NULL,
  father_name VARCHAR(191) NULL,
  mobile VARCHAR(20) NULL,
  trade VARCHAR(100) NOT NULL,
  fee_type VARCHAR(100) NOT NULL,
  total_amount DECIMAL(12,2) NULL,
  amount DECIMAL(12,2) NOT NULL,
  paid_amount DECIMAL(12,2) DEFAULT 0,
  due_date DATE NULL,
  status VARCHAR(50) DEFAULT 'Pending',
  payment_date DATE NULL,
  payment_method VARCHAR(50) NULL,
  receipt_number VARCHAR(50) NULL,
  invoice_number VARCHAR(50) NULL,
  notes TEXT NULL,
  installment_enabled TINYINT(1) DEFAULT 0,
  total_installments INT DEFAULT 1,
  academic_year VARCHAR(50) NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS fee_installments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fee_id INT NOT NULL,
  installment_number INT NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  due_date DATE NULL,
  paid_amount DECIMAL(12,2) DEFAULT 0,
  payment_date DATE NULL,
  payment_method VARCHAR(50) NULL,
  receipt_number VARCHAR(50) NULL,
  status VARCHAR(50) DEFAULT 'Pending',
  notes TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (fee_id) REFERENCES student_fees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS flash_news (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  link VARCHAR(255) NULL,
  is_active TINYINT(1) DEFAULT 1,
  order_index INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS header_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  phone VARCHAR(50) NULL,
  email VARCHAR(191) NULL,
  student_portal_link VARCHAR(255) NULL,
  student_portal_text VARCHAR(100) NULL,
  ncvt_mis_link VARCHAR(255) NULL,
  ncvt_mis_text VARCHAR(100) NULL,
  staff_email_link VARCHAR(255) NULL,
  staff_email_text VARCHAR(100) NULL,
  logo_text VARCHAR(191) NULL,
  tagline VARCHAR(255) NULL,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS footer_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  about_text TEXT NULL,
  facebook_link VARCHAR(255) NULL,
  twitter_link VARCHAR(255) NULL,
  linkedin_link VARCHAR(255) NULL,
  youtube_link VARCHAR(255) NULL,
  address TEXT NULL,
  phone VARCHAR(50) NULL,
  email VARCHAR(191) NULL,
  copyright_text VARCHAR(255) NULL,
  privacy_link VARCHAR(255) NULL,
  terms_link VARCHAR(255) NULL,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS footer_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(191) NOT NULL,
  url VARCHAR(255) NOT NULL,
  category VARCHAR(50) DEFAULT 'quick_links',
  order_index INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS trades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(100) NOT NULL UNIQUE,
  category VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  image VARCHAR(255) NULL,
  syllabus_pdf VARCHAR(255) NULL,
  prospectus_pdf VARCHAR(255) NULL,
  duration VARCHAR(100) NOT NULL,
  eligibility TEXT NOT NULL,
  seats VARCHAR(50) NOT NULL,
  syllabus_json TEXT NULL,
  careers_json TEXT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS about_page (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hero_title VARCHAR(255) NOT NULL,
  hero_subtitle VARCHAR(255) NULL,
  hero_description TEXT NULL,
  hero_image VARCHAR(255) NULL,
  about_title VARCHAR(255) NULL,
  about_description TEXT NULL,
  about_image VARCHAR(255) NULL,
  mission_title VARCHAR(255) NULL,
  mission_description TEXT NULL,
  vision_title VARCHAR(255) NULL,
  vision_description TEXT NULL,
  principal_name VARCHAR(191) NULL,
  principal_message TEXT NULL,
  principal_image VARCHAR(255) NULL,
  stats_json TEXT NULL,
  values_json TEXT NULL,
  features_json TEXT NULL,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admission_process (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hero_title VARCHAR(255) NOT NULL,
  hero_subtitle VARCHAR(255) NULL,
  hero_description TEXT NULL,
  eligibility_title VARCHAR(255) NULL,
  eligibility_criteria_json TEXT NULL,
  steps_title VARCHAR(255) NULL,
  steps_json TEXT NULL,
  dates_title VARCHAR(255) NULL,
  important_dates_json TEXT NULL,
  documents_title VARCHAR(255) NULL,
  required_documents_json TEXT NULL,
  cta_title VARCHAR(255) NULL,
  cta_description TEXT NULL,
  cta_button_text VARCHAR(100) NULL,
  cta_button_link VARCHAR(255) NULL,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS faculty (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(191) NOT NULL,
  designation VARCHAR(191) NOT NULL,
  department VARCHAR(191) NOT NULL,
  qualification VARCHAR(255) NULL,
  experience VARCHAR(100) NULL,
  image VARCHAR(255) NULL,
  email VARCHAR(191) NULL,
  phone VARCHAR(50) NULL,
  bio TEXT NULL,
  specialization VARCHAR(255) NULL,
  is_principal TINYINT(1) DEFAULT 0,
  display_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
