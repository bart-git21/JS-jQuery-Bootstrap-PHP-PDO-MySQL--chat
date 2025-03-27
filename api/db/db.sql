CREATE DATABASE IF NOT EXISTS sql_queries;
USE sql_queries;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL UNIQUE,
    role TEXT NOT NULL
);
INSERT INTO users (login, password, role) VALUES ('admin', 'admin', 'admin'), ('Item1', '123', 'user'), ('Item2', '456', 'user');
