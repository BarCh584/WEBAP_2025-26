DROP DATABASE IF EXISTS Ponggame;

CREATE DATABASE Ponggame;
USE Ponggame;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE Games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    score VARCHAR(255),
    maxscore INT,
    gamestart DATETIME DEFAULT CURRENT_TIMESTAMP,
    gameend TIMESTAMP NULL,
    user1 VARCHAR(255) NOT NULL,
    user2 VARCHAR(255) NULL,
    status ENUM('waiting', 'ongoing', 'finished') DEFAULT 'waiting',
    FOREIGN KEY (user1) REFERENCES Users(username),
    FOREIGN KEY (user2) REFERENCES Users(username)
) ENGINE=InnoDB;