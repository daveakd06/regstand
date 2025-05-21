CREATE TABLE IF NOT EXISTS stands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    stand_code VARCHAR(50) NOT NULL,
    category VARCHAR(50) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
