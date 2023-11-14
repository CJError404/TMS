<?php
require_once 'config/connection.php';

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

$errors = array();

$task_id = $_GET['task_id'];
$query = "SELECT * FROM tasks WHERE task_id ='$task_id'";
$sql = mysqli_query($connection, $query);

if (!$sql) {
    $errors[] = mysqli_error($connection);
}

if (isset($_POST['updateForm'])) {
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $task_description = $_POST['task_description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $completed = isset($_POST['completed']) ? 1 : 0;

    if (count($errors) == 0) {
        if ($completed) {
            $updateLogs = "UPDATE tasks_logs SET end_time = NOW() WHERE task_id = '$task_id'";
            if (!mysqli_query($connection, $updateLogs)) {
                $errors[] = mysqli_error($connection);
            }
        }
        
        $updateQuery = "UPDATE tasks SET title = ?, task_description = ?, due_date = ?, priority = ?, completed = ? WHERE task_id = ?";
        $stmt = mysqli_prepare($connection, $updateQuery);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssii", $title, $task_description, $due_date, $priority, $completed, $task_id);
            if (!mysqli_stmt_execute($stmt)) {
                $errors[] = mysqli_error($connection);
            }
        } else {
            $errors[] = mysqli_stmt_error($stmt);
        }

        if (count($errors) == 0) {
            header('location: create_task.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Task</title>
</head>
<body>
        <form action="edit_task.php?task_id=<?php echo $task_id; ?>" method="POST">

            <?php while($row = mysqli_fetch_array($sql)): ?>
                <input type="hidden" name="task_id" value="<?php echo $row['task_id']; ?>">
                <input type="text" name="title" value="<?php echo $row['title']; ?>"> <br><br>
                <input type="text" name="task_description" value="<?php echo $row['task_description']; ?>"><br><br>
                <input type="text" name="due_date" value="<?php echo $row['due_date']; ?>"><br><br>
                <select name="priority">
                    <option value="1" <?php echo $row['priority'] == 1 ? 'selected' : ''; ?>>High priority</option>
                    <option value="2" <?php echo $row['priority'] == 2 ? 'selected' : ''; ?>>Medium priority</option>
                    <option value="3" <?php echo $row['priority'] == 3 ? 'selected' : ''; ?>>Low priority</option>
                </select><br><br>

                <label for="completed">Status:</label>
                <select name="completed">
                <option value="0" <?php echo $row['completed'] == 0 ? 'selected' : ''; ?>>In Progress</option>
                <option value="1" <?php echo $row['completed'] == 1 ? 'selected' : ''; ?>>Completed</option>
                </select><br><br>

                <input type="submit" name="updateForm" value="Update"><br><br>
        <?php endwhile; ?>
    </form>
</body>
</html>