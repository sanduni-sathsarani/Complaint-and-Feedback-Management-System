FOODCARE RESTAURANT
Complaint and Feedback Management System
Academic Project - HTML, CSS, JavaScript, PHP, MySQL

IMPORTANT:
The system does NOT require customer registration or customer login.
Only Admin and Staff members log in.

TECHNOLOGY:
HTML5
CSS3
JavaScript
PHP 8+
MySQL

SETUP USING XAMPP:
1. Install XAMPP.
2. Start Apache and MySQL.
3. Copy the FoodCare_Restaurant folder into:
   C:\xampp\htdocs\
4. Open http://localhost/phpmyadmin/
5. Import:
   database/foodcare.sql
6. Open:
   http://localhost/FoodCare_Restaurant/

DATABASE:
Database name: foodcare
Username: root
Password: empty (default XAMPP setup)
If your MySQL password is different, edit config/database.php.

LOGIN:
Admin
Email: admin@foodcare.local
Password: Admin@123

Staff
Email: staff@foodcare.local
Password: Staff@123

CUSTOMER FLOW:
Home -> Complaint -> Complaint Number -> Track Complaint
Home -> Feedback
No customer account is required.

ADMIN FLOW:
Login -> Dashboard -> View complaints -> Assign staff -> Manage staff/categories -> Feedback -> Reports

STAFF FLOW:
Login -> Dashboard -> View assigned complaints -> Investigate -> Update status -> Send response

SECURITY NOTES:
- PHP sessions protect staff/admin pages.
- Passwords are stored using password_hash().
- Database operations use PDO prepared statements.
- User-entered output is escaped with htmlspecialchars().
- Customer data is not exposed on the public tracking page beyond basic complaint details.

ACADEMIC NOTE:
This is a student project prototype. For real production use, add CSRF protection, stronger file validation, email notifications, audit logging, HTTPS, rate limiting and stronger privacy controls.
