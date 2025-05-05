<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "librow";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if ($conn->query($sql) !== TRUE) {
    echo "<script>alert('Error creating database: " . $conn->error . "');</script>";
}

// Select the database
$conn->select_db($dbname);

// Create the tables if they don't exist
$sql = "
CREATE TABLE IF NOT EXISTS `accounts` (
  `Id` int(5) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ConPassword` varchar(255) NOT NULL,
  `totp_secret` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `adminaccounts` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `totp_secret` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `books` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `bookstock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `returnpage` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `number` bigint(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip` int(5) NOT NULL,
  `booknumber` varchar(100) NOT NULL,
  `addReq` varchar(500) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `attempt_time` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

if ($conn->multi_query($sql) === TRUE) {
    do {
        if ($conn->more_results()) {
            $conn->next_result();
        }
    } while ($conn->more_results());
} else {
    echo "<script>alert('Error creating tables: " . $conn->error . "');</script>";
}

// Check if the email column exists in login_attempts, and add it if it doesn't
$checkColumnQuery = "SHOW COLUMNS FROM `login_attempts` LIKE 'email'";
$result = $conn->query($checkColumnQuery);
if ($result->num_rows == 0) {
    $alterQuery = "ALTER TABLE `login_attempts` ADD COLUMN `email` VARCHAR(100) DEFAULT NULL";
    if ($conn->query($alterQuery) !== TRUE) {
        echo "<script>alert('Error adding email column to login_attempts: " . $conn->error . "');</script>";
    }
}

// Keep connection open for reuse
?>