CREATE TABLE users (
  id BIGINT PRIMARY KEY
);

CREATE TABLE kyc_submissions (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  profile_type ENUM('Agent','Agency','Other') NOT NULL,
  country VARCHAR(100) NOT NULL,
  has_regulatory_cert TINYINT(1) NOT NULL,
  company_name VARCHAR(150) NULL,
  years_experience TINYINT UNSIGNED NOT NULL,
  areas TEXT NULL,
  signatory_name VARCHAR(120) NOT NULL,
  nationality VARCHAR(60) NOT NULL,
  designation VARCHAR(80) NULL,
  telephone VARCHAR(30) NULL,
  mobile VARCHAR(20) NOT NULL,
  email VARCHAR(190) NOT NULL,
  doc_trade_license_path VARCHAR(255) NOT NULL,
  doc_regulatory_cert_path VARCHAR(255) NULL,
  doc_passport_path VARCHAR(255) NOT NULL,
  doc_moa_poa_path VARCHAR(255) NULL,
  doc_proof_address_path VARCHAR(255) NOT NULL,
  status ENUM('PENDING_REVIEW','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING_REVIEW',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_kyc_user FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX (user_id),
  UNIQUE KEY uniq_user_email (user_id, email)
);
