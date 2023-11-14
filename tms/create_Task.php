<?php
$page_title = 'Task Management';
include('includes/header.html');
require 'config/connection.php';
error_reporting(E_ERROR | E_PARSE);

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

$display_all = "SELECT * FROM tasks ORDER BY priority";
$query = mysqli_query($connection, $display_all);

$errors = array();

if (isset($_POST['submitFormBtn'])){
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $task_description = $_POST['task_description'];
    $due_date = $_POST['due_date'];
    $priority = (int)$_POST['priority'];
    $completed = (int)$_POST['completed']; 

    // validation if necessary
    if (count($errors) == 0){
        $insertQuery = "INSERT INTO tasks (title, task_description, due_date, priority, completed) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($insertQuery);
        $stmt->bind_param('sssii', $title, $task_description, $due_date, $priority, $completed);

        if ($stmt->execute()){
            // Insert successful, now start time tracking
            $new_task_id = $stmt->insert_id; // Get the ID of the newly inserted task

            // Start time tracking for the newly created task
            $startTrackingQuery = "INSERT INTO tasks_logs (task_id, start_time) VALUES (?, NOW())";
            $stmtStart = $connection->prepare($startTrackingQuery);
            $stmtStart->bind_param('i', $new_task_id);
            $stmtStart->execute();
            header('location: create_task.php');
        } else {
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
    <title>Task Management System</title>
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

        form textarea[name="task_description"] {
            resize: vertical; 
            font-family: inherit;
        }

        form input[type="datetime-local"] {
            width: 30%;
            box-sizing: border-box;
        }

        form select[name="priority"] {
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

<div id="content">
    <?php if (count($errors) > 0): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="create_task.php" method="POST">
        <input type="text" name="title" id="title" placeholder="Add task" required><br>
        <textarea name="task_description" placeholder="Type details for this new task" required></textarea><br>
        <label for="due_date">Due Date:</label><br>
        <input type="datetime-local" name="due_date" id="due_date" required><br><br>
        <select name="priority" id="priority" required>
            <option value="1">High priority</option>
            <option value="2">Medium priority</option>
            <option value="3">Low priority</option>
        </select><br><br>
        <input type="submit" name="submitFormBtn" value="Create task">
    </form>

    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Deadline</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_array($query)): ?>
            <tr>
                <td><?php echo($row['title']); ?></td>
                <td><?php echo($row['task_description']); ?></td>
                <td><?php echo($row['due_date']); ?></td>
                <td><?php echo($row['priority']); ?></td>
                <td><?php echo $row['completed'] ? 'Completed' : 'In Progress'; ?></td>
                <td>
                    <a href="delete_task.php?task_id=<?php echo $row['task_id']; ?>">Delete</a>
                    <a href="edit_task.php?task_id=<?php echo $row['task_id']; ?>">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>