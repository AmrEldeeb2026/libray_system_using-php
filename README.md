# The Reading Room — Library Management System

## Table of Contents
1. Project Overview
2. System Architecture & Features
3. Database Structure & Design
4. Authentication & Security Layer
5. Installation & Setup Guide
6. Credentials & Access Management
7. File Structure & Responsibilities
8. Technical Specifications

---

## 1. Project Overview

The Reading Room is a full-stack, web-based Library Management System developed using Native PHP (PDO), MySQL, HTML5, CSS3, and JavaScript. 

The application is structured into two main layers:
* Public Interface: Allows library visitors to browse cataloged books, search through titles or authors in real-time, and submit borrow requests without requiring registration.
* Administrative Dashboard: A secure, authenticated control panel for library administrators to manage books cataloging, update availability statuses, execute CRUD operations, and process borrow records.

---

## 2. System Architecture & Features

### Public Functionality
* Dynamic Catalog Browsing: Displays all cataloged items with live availability indicators.
* Real-Time Client-Side Search: Instant filtering by book title or author using custom JavaScript DOM operations.
* Simplified Borrowing Flow: A streamlined request form capturing borrower contact info and loan dates, automatically handling inventory state transitions.

### Administrator Functionality
* Secure Authentication Engine: Protected route validation ensuring unauthorized users cannot access management interfaces.
* Real-Time Dashboard Ledger: Aggregated metrics tracking total books, available items, and active loans.
* Complete Book CRUD Operations: Comprehensive options to create, read, update, and delete book entries.
* State Management: One-click status toggling between "available" and "borrowed" states.
* Borrow History & Return Tracker: Centralized transaction log allowing admins to review borrower details and process returned items.

---

## 3. Database Structure & Design

The application utilizes a relational MySQL database named `library_db` structured around three core tables:

### Users Table (`users`)
Stores administrator accounts and security credentials.
* `id` (INT, Primary Key, Auto Increment)
* `username` (VARCHAR 100, Not Null)
* `email` (VARCHAR 150, Unique, Not Null)
* `password` (VARCHAR 255, Not Null) - Stores Bcrypt hashed passwords
* `created_at` (TIMESTAMP, Default Current Timestamp)

### Books Table (`books`)
Stores information about the physical books available in the system.
* `id` (INT, Primary Key, Auto Increment)
* `title` (VARCHAR 255, Not Null)
* `author` (VARCHAR 255, Not Null)
* `isbn` (VARCHAR 50, Not Null)
* `status` (ENUM('available', 'borrowed'), Default 'available')
* `created_at` (TIMESTAMP, Default Current Timestamp)

### Borrow Records Table (`borrow_records`)
Tracks transaction records linking borrowers with specific books.
* `id` (INT, Primary Key, Auto Increment)
* `book_id` (INT, Foreign Key referencing `books.id` ON DELETE CASCADE)
* `borrower_name` (VARCHAR 150, Not Null)
* `borrower_phone` (VARCHAR 20, Not Null)
* `borrow_date` (DATE, Not Null)
* `return_date` (DATE, Nullable)
* `status` (ENUM('borrowed', 'returned'), Default 'borrowed')
* `created_at` (TIMESTAMP, Default Current Timestamp)

---

## 4. Authentication & Security Layer

* Bcrypt Hashing Mechanism: Passwords are encrypted using PHP native `password_hash()` with the `PASSWORD_DEFAULT` (Bcrypt) algorithm, securing credentials against reverse engineering.
* Password Verification: Authentication requests utilize `password_verify()` to compare raw user inputs against secure hashes.
* Session Control: Session states (`$_SESSION['admin_logged_in']`) are initialized upon valid authentication. Access control blocks are implemented on top of protected endpoints to prevent unauthorized URL direct navigation.
* SQL Injection Prevention: All database queries interacting with user input utilize Prepared Statements via PHP Data Objects (PDO).

---

## 5. Installation & Setup Guide

### System Prerequisites
* Web Server (Apache or Nginx)
* PHP 7.4 or higher
* MySQL Database Engine
* Server Environment (XAMPP, Laragon, or WampServer)

### Local Environment Configuration
1. Clone or extract the project files into your local server root directory:
   * XAMPP: `C:/xampp/htdocs/library_db`
   * Laragon: `C:/laragon/www/library_db`
2. Start the Apache and MySQL services in your control panel.
3. Access phpMyAdmin (`http://localhost/phpmyadmin/`).
4. Create a new database named `library_db` with `utf8mb4_unicode_ci` collation.
5. Import the `database.sql` file provided in the root directory.
6. Open your browser and navigate to `http://localhost/library_db/`.

---

## 6. Credentials & Access Management

The system features an automatic seeding procedure built into `db.php`. On initial database connection:
* If no user records exist, an administrative user account is automatically populated using environment-native password hashing.

### Default Admin Credentials
* Email: `admin@library.com`
* Password: `admin123`

---

## 7. File Structure & Responsibilities

* `database.sql`: SQL migration script containing table definitions, relations, and initial sample data.
* `db.php`: Database connection handler using PDO along with automated administrator initialization code.
* `index.php`: Main catalog page displaying books, search bar, and loan status tags.
* `borrow.php`: Form page for public users to process borrowing requests.
* `login.php`: Secure authentication portal for administrative users.
* `admin.php`: Administrative overview panel displaying system statistics and full catalog operations.
* `add-book.php`: Interface for adding new titles to the catalog.
* `edit-book.php`: Interface for updating book attributes and states.
* `borrow-history.php`: Comprehensive log tracking active loans and processing returned books.
* `header.php`: Global HTML head declarations, navigation links, and session checks.
* `footer.php`: Global HTML footer template and script inclusions.
* `style.css`: Custom layout styles and components.
* `script.js`: Interactive frontend search logic.

---

## 8. Technical Specifications

* Backend Language: PHP (Object-Oriented via PDO)
* Database System: MySQL
* Markup & Styling: HTML5 / Custom CSS3 (Flexbox & Grid Layouts)
* Client-Side Scripting: JavaScript (ES6 Syntax)
* Program Context: Full-Stack Web Development Course Project - National Telecommunication Institute (NTI)
