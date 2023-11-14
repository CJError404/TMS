<?php
$page_title = 'Admin Dashboard';
include('includes/header.html');
require 'config/connection.php';
error_reporting(E_ERROR | E_PARSE);

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

// Fetch task management data
$task_query = mysqli_query($connection, "SELECT * FROM tasks ORDER BY priority");
$task_count = mysqli_num_rows($task_query);

// Fetch event scheduling data
$event_query = mysqli_query($connection, "SELECT * FROM events_");
$event_count = mysqli_num_rows($event_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #134074;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        #content {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1, h2 {
            color: #fff;
        }

        p {
            color: #555;
        }

        .analytics-section {
            margin-top: 30px;
        }

        .analytics-card {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .analytics-title {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .analytics-value {
            font-size: 1.5em;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="content">
    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <div class="analytics-section">
        <div class="analytics-card">
            <div class="analytics-title">Task Management Analytics</div>
            <p>Total tasks: <span class="analytics-value"><?php echo $task_count; ?></span></p>
        </div>
    </div>

    <div class="analytics-section">
        <div class="analytics-card">
            <div class="analytics-title">Event Scheduling Analytics</div>
            <p>Total events: <span class="analytics-value"><?php echo $event_count; ?></span></p>
        </div>
    </div>
</div>
</body>
</html>