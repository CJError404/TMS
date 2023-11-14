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
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TMS</title>
</head>
<body background="">
<?php if (count($errors) > 0): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="create_task.php" method="POST">
    <input type="text" name="title" placeholder="Task"><br><br>
    <input type="text" name="task_description" placeholder="Description"><br><br>
    <input type="datetime-local" name="due_date"><br><br>
    <input type="text" name="priority" placeholder="Priority"><br><br>
    <input type="submit" name="submitFormBtn" value="Create task"><br><br>
    <hr>
</form>

<table>
    <thead>
    <tr>
        <th> ID </th>
        <th> Title </th>
        <th> Description </th>
        <th> Due Date and Time </th>
        <th> Priority </th>

    </tr>
    </thead>
    <?php while($row = mysqli_fetch_array($query)): ?>
        <tbody>
        <tr>
            <td><?php echo($row['task_id']); ?></td>
            <td><?php echo($row['title']); ?></td>
            <td><?php echo($row['task_description']); ?></td>
            <td><?php echo($row['due_date']); ?></td>
            <td><?php echo($row['priority']); ?></td>
            <td><?php echo $row['completed'] ? 'Completed' : 'In Progress'; ?></td>
            <td><a href ="delete_task.php?task_id=<?php echo $row['task_id']; ?>">Delete</td>
            <td><a href="edit_task.php?task_id=<?php echo $row['task_id']; ?>">Edit</a></td>
        </tr>
        </tbody>
    <?php endwhile; ?>
</table>
</body>
</html>