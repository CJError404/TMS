-- create the database for the TMS
CREATE DATABASE time_management_system;

-- create the tasks table to store information about individual tasks
CREATE TABLE tasks (
task_id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
description TEXT,
due_date DATE,
priority INT,
completed BOOLEAN DEFAULT 0
);

-- create the events table to store information about scheduled events
CREATE TABLE events (
event_id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
description TEXT,
date_time DATETIME
);

-- create the users table to store information about system users (admin or regular users)
CREATE TABLE users (
user_id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL
);