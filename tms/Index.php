<?php
    $page_title = 'Dashboard';
    include('includes/index_header.html');
    

    if (isset($_POST['Logout'])) {
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        header('location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Time Management System - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        #dashboard {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 55vh;
        }

        .dashboard-box {
            flex: 0 1 200px;
            margin: 0 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .dashboard-box:hover {
            background-color: #f9f9f9;
        }

        .dashboard-box a {
            text-decoration: none;
            color: #333;
        }

        .dashboard-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div id="dashboard">
    <div class="dashboard-box">
        <a href="create_task.php">
            <div class="dashboard-icon">&#x1F4DD;</div>
            <h3>Task Management</h3>
        </a>
    </div>

    <div class="dashboard-box">
        <a href="create_event.php">
            <div class="dashboard-icon">&#x1F4C5;</div>
            <h3>Event Scheduling</h3>
        </a>
    </div>

    <div class="dashboard-box"> 
        <a href="admin_Dashboard.php">
            <div class="dashboard-icon">&#x1F4C9;</div>
            <h3>Admin Dashboard</h3>
        </a>
    </div>

<?php include('includes/footer.html'); ?>
</body>
</html>
