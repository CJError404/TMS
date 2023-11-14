<?php
$page_title = 'Event Scheduling';
include('includes/header.html');
require_once 'config/connection.php';
error_reporting(E_ERROR | E_PARSE);

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

$errors = array();

if (!$connection) {
    $errors['db_error'] = "Database connection error: " . mysqli_connect_error();
} else {
    $display_all = "SELECT * FROM events_";
    $query = mysqli_query($connection, $display_all);

    if (!$query) {
        $errors['db_error'] = "Query execution failed: " . mysqli_error($connection);
    }
}

if (isset($_POST['submitFormBtn'])){
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $event_description = $_POST['event_description'];
    $start_date_time = $_POST['start_date_time'];
    $end_date_time = $_POST['end_date_time'];

    if (count($errors) == 0){
        $insertQuery = "INSERT INTO events_ (title, event_description, start_date_time, end_date_time) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insertQuery);
        $stmt ->bind_param('ssss', $title, $event_description, $start_date_time, $end_date_time);



        if ($stmt->execute()){
            $new_event_id = $stmt->insert_id;

            $InsertLogsQuery = "INSERT INTO event_logs (event_id, start_date_time, end_date_time) VALUES (?, ?, ?)";
            $stmtLogs = $connection->prepare($InsertLogsQuery);
            $stmtLogs->bind_param('iss', $new_event_id, $start_date_time, $end_date_time);
            $stmtLogs->execute();
            header('location:create_event.php');
        }   else{
            $errors['db_error'] = "Database Error!";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Scheduling System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #13315C;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .errors {
            color: #e74c3c;
            list-style: none;
            padding: 10px;
            background-color: #f2dede;
            border: 1px solid #e74c3c;
            border-radius: 5px;
            margin-top: 10px;
        }

        .success-message {
            color: #2ecc71;
            list-style: none;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-top: 10px;
        }

        form input[type="text"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        form textarea[name="event_description"] {
            resize: vertical; 
            font-family: inherit;
        }

        form input[type="datetime-local"] {
            width: 30%;
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
<form action="create_event.php" method="POST">
    <?php if (count($errors) > 0): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <input type="text" name="title" placeholder="Add title" required><br>
    <textarea name="event_description" placeholder="Type details for this new event" required></textarea><br>
    <label for="start_date_time"> Start Date & Time:</label><br>
    <input type="datetime-local" name="start_date_time" required><br><br>
    <label for="end_date_time"> End Date & Time:</label><br>
    <input type="datetime-local" name="end_date_time" required><br><br>
    <input type="submit" name="submitFormBtn" value="Schedule"><br>
</form>

<?php if (isset($_GET['success'])): ?>
    <div class="success-message">Event scheduled successfully!</div>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th> Title </th>
        <th> Description </th>
        <th> Start </th>
        <th> End </th>
        <th colspan="2"> Actions </th>
    </tr>
    </thead>
    <?php while($row = mysqli_fetch_array($query)): ?>
        <tbody>
        <tr>
            <td><?php echo($row['title']); ?></td>
            <td><?php echo($row['event_description']); ?></td>
            <td><?php echo($row['start_date_time']); ?></td>
            <td><?php echo($row['end_date_time']); ?></td>
            <td><a href="delete_event.php?event_id=<?php echo $row['event_id']; ?>">Cancel</a></td>
            <td><a href="edit_event.php?event_id=<?php echo $row['event_id']; ?>">Edit</a></td>
        </tr>
        </tbody>
    <?php endwhile; ?>
</table>

</body>
</html>