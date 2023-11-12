-- create database
CREATE DATABASE time_management_system;

-- create tables
CREATE TABLE tasks (
task_id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
description TEXT,
due_date VARCHAR(255),
priority INT,
completed BOOLEAN DEFAULT 0
);

CREATE TABLE events (
event_id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
description TEXT,
date_time DATETIME
);

CREATE TABLE users (
user_id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL
);
