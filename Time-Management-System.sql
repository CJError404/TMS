-- Create Database
Create database Time_management_system;

-- Create tables
Create table tasks (
Task_Id int auto_increment primary key,
Task_Title varchar(255) not null,
Task_Description text,
Task_Due_Date varchar(255),
Task_Priority int,
Task_Completed boolean default 0
);

Create table Users(
User_Id int auto_increment primary key,
User_Username varchar(255) not null,
User_Password varchar(255) not null
);

Create table Events(
Event_Id int auto_increment primary key,
Event_Title varchar(100) not null,
Event_Description text,
Event_Date date,
Event_Time time
);