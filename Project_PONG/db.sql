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
    score INT,
    maxscore INT,
    gamestart DATETIME DEFAULT CURRENT_TIMESTAMP,
    gameend TIMESTAMP NULL,
    user1_id INT,
    user2_id INT,
    FOREIGN KEY (user1_id) REFERENCES Users(id),
    FOREIGN KEY (user2_id) REFERENCES Users(id)
) ENGINE=InnoDB;