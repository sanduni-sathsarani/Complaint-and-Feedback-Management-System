CREATE DATABASE IF NOT EXISTS foodcare CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE foodcare;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255),
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE complaints (
    complaint_id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_number VARCHAR(30) NOT NULL UNIQUE,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(150),
    customer_phone VARCHAR(30),
    order_number VARCHAR(50),
    category_id INT NOT NULL,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    branch VARCHAR(120),
    priority ENUM('Low','Medium','High') DEFAULT 'Medium',
    status ENUM('Submitted','Under Review','Assigned','Investigating','Response Sent','Resolved','Closed','Reopened') DEFAULT 'Submitted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE complaint_assignments (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    staff_id INT NOT NULL,
    assigned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(complaint_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES users(user_id)
);

CREATE TABLE complaint_responses (
    response_id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(complaint_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE complaint_status_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    note VARCHAR(500),
    changed_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(complaint_id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NULL,
    customer_name VARCHAR(120),
    customer_email VARCHAR(150),
    rating TINYINT NOT NULL,
    food_rating TINYINT NULL,
    service_rating TINYINT NULL,
    cleanliness_rating TINYINT NULL,
    value_rating TINYINT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(complaint_id) ON DELETE SET NULL
);

CREATE TABLE attachments (
    attachment_id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(complaint_id) ON DELETE CASCADE
);

INSERT INTO categories(category_name,description) VALUES
('Food Quality','Food temperature, taste, freshness or quality'),
('Customer Service','Staff behaviour and service quality'),
('Order Issue','Missing, incorrect or delayed order'),
('Cleanliness','Restaurant or dining area cleanliness'),
('Billing','Payment, bill or pricing issue'),
('Other','Other restaurant-related complaint');

-- Default passwords:
-- Admin: Admin@123
-- Staff: Staff@123
-- Hashes generated with PHP password_hash().
INSERT INTO users(name,email,password,role) VALUES
('FoodCare Administrator','admin@foodcare.local','$2y$12$sJoANQswwntWcwTbq.30FOfx1/evwGKpLVQV1kipCtfYh0iOcQQJW','admin'),
('Restaurant Staff','staff@foodcare.local','$2y$12$JV8IfFbfjWcZpCtZN8iThuP3Wc11gTsDKSgTgTH1zhkcuc/UX7VEO','staff');
