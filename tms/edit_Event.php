<?php
require_once 'config/connection.php';
$page_title = 'Edit Event';
include('includes/index_header.html');

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

$errors = [];

if (!$connection) {
    $errors[] = "Connection error: " . mysqli_connect_error();
}

if (isset($_POST['updateForm'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $event_description = $_POST['event_description'];
    $start_date_time = $_POST['start_date_time'];
    $end_date_time = $_POST['end_date_time'];

    $updateLogs = "UPDATE event_logs SET start_date_time = ?, end_date_time = ? WHERE event_id = ?";
    $stmtLogs = mysqli_prepare($connection, $updateLogs);
    
    if ($stmtLogs) {
        mysqli_stmt_bind_param($stmtLogs, "ssi", $start_date_time, $end_date_time, $event_id);
        if (!mysqli_stmt_execute($stmtLogs)) {
            $errors[] = "Logs update error: " . mysqli_stmt_error($stmtLogs);
        }
    } else {
        $errors[] = "Logs statement preparation error: " . mysqli_error($connection);
    }

    $updateQuery = "UPDATE events_ SET title = ?, event_description = ?, start_date_time = ?, end_date_time = ? WHERE event_id = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssi", $title, $event_description, $start_date_time, $end_date_time, $event_id);
        if (mysqli_stmt_execute($stmt)) {
            header('location: create_event.php');
            exit();
        } else {
            $errors[] = "Event update error: " . mysqli_stmt_error($stmt);
        }
    } else {
        $errors[] = "Event statement preparation error: " . mysqli_error($connection);
    }
}

$event_id = $_GET['event_id'];
$query = "SELECT * FROM events_ WHERE event_id = ?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
    } else {
        $errors[] = "Fetching event details error: " . mysqli_stmt_error($stmt);
    }
} else {
    $errors[] = "Statement preparation error: " . mysqli_error($connection);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Event</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        form input[type="text"],
        form input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
    </style>
</head>
<body>
<?php if (count($errors) > 0): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form action="edit_event.php?event_id=<?php echo $event_id; ?>" method="POST">
    <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
    <label for="Title"> Title:</label><br>
    <input type="text" name="title" value="<?php echo $row['title']; ?>"> <br><br>
    <label for="start_date_time"> Start Date & Time:</label><br>
    <input type="datetime-local" name="start_date_time" value="<?php echo $row['start_date_time']; ?>"><br><br>
    <label for="end_date_time"> End Date & Time:</label><br>
    <input type="datetime-local" name="end_date_time" value="<?php echo $row['end_date_time']; ?>"><br><br>
    <label for="event_description"> Description: </label><br>
    <input type="text" name="event_description" value="<?php echo $row['event_description']; ?>"><br><br>
    <input type="submit" name="updateForm" value="Update"><br><br>
</form>
</body>
</html>