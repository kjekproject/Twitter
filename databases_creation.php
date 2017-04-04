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
    user_id int NOT NULL,
    text varchar(180) NOT NULL,
    creation_date datetime NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
    ON DELETE CASCADE
    );
 
CREATE TABLE comments (
    id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    tweet_id int NOT NULL,
    text varchar(60) NOT NULL,
    creation_date datetime NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(tweet_id) REFERENCES tweets(id)
    ON DELETE CASCADE
    );
 
CREATE TABLE messages (
    id int NOT NULL AUTO_INCREMENT,
    author_id int NOT NULL,
    recipient_id int NOT NULL,
    text varchar(1000) NOT NULL,
    creation_date datetime NOT NULL,
    status int NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(author_id) REFERENCES users(id),
    FOREIGN KEY(recipient_id) REFERENCES users(id)
    ON DELETE CASCADE
    );
 */
?>
