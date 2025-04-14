create database db_batch8_capstone;

use db_batch8_capstone;

-- Table: Users
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    NAME VARCHAR (100) NOT NULL,
    email VARCHAR (100) UNIQUE NOT NULL,
    password VARCHAR (255) NOT NULL,
    role ENUM('admin','customer') DEFAULT 'customer'
);