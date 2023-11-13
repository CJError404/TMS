-- create the database for the TMS
CREATE DATABASE time_management_system;

-- create the tasks table to store information about individual tasks
CREATE TABLE tasks (
task_id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
task_description TEXT,
due_date DATETIME,
priority INT,
completed BOOLEAN DEFAULT 0
);

-- create the events table to store information about scheduled events
CREATE TABLE events (
event_id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
event_description TEXT,
date_time DATETIME
);

-- create the users table to store information about system users (admin or regular users)
CREATE TABLE users (
user_id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL
);

-- create tables in order to track time spent on tasks and events
CREATE TABLE tasks_logs (
TL_id INT AUTO_INCREMENT PRIMARY KEY,
task_id INT,
start_time DATETIME,
end_time DATETIME,
FOREIGN KEY (task_id) references tasks(task_id)
);

CREATE TABLE event_logs (
EL_id INT AUTO_INCREMENT PRIMARY KEY,
event_id INT,
start_time DATETIME,
end_time DATETIME,
FOREIGN KEY (event_id) references events(event_id)
);
