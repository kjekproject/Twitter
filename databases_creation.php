<?php

/*
CREATE DATABASE twitter_db
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT,
    email varchar(255) NOT NULL,
    userName varchar(255) NOT NULL,
    password varchar(60) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE (email)
);

CREATE TABLE tweets (
    id int NOT NULL AUTO_INCREMENT,
    userId int NOT NULL,
    text varchar(180) NOT NULL,
    creationDate datetime NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(userId) REFERENCES users(id)
    ON DELETE CASCADE
    );
 
CREATE TABLE comments (
    id int NOT NULL AUTO_INCREMENT,
    userId int NOT NULL,
    tweetId int NOT NULL,
    text varchar(60) NOT NULL,
    creationDate datetime NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(userId) REFERENCES users(id),
    FOREIGN KEY(tweetId) REFERENCES tweets(id)
    ON DELETE CASCADE
    );
 
CREATE TABLE messages (
    id int NOT NULL AUTO_INCREMENT,
    authorId int NOT NULL,
    recipientId int NOT NULL,
    text varchar(1000) NOT NULL,
    creationDate datetime NOT NULL,
    status int NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(authorId) REFERENCES users(id),
    FOREIGN KEY(recipientId) REFERENCES users(id)
    ON DELETE CASCADE
    );
 */
?>
