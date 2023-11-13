<?php
require_once 'config/connection.php';

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

    $task_id = $_GET['task_id'];
    $query = "SELECT * FROM tasks WHERE task_id ='$task_id'";
    $sql = mysqli_query($connection, $query);

    $errors = array();

        if (isset($_POST['updateForm'])) {
            $task_id = $_POST['task_id'];
            $title = $_POST['title'];
            $task_description = $_POST['task_description'];
            $due_date = $_POST['due_date'];
            $priority = $_POST['priority'];
            $completed = isset($_POST['completed']) ? 1 : 0;

        if (count($errors) == 0) {
            $updateQuery = "UPDATE tasks SET title = '$title', task_description = '$task_description', due_date = '$due_date', priority = '$priority', completed = '$completed' WHERE task_id = '$task_id'";
            mysqli_query($connection, $updateQuery);
            header('location: create.php');
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
        <form action="edit.php?task_id=<?php echo $task_id; ?>" method="POST">

            <?php while($row = mysqli_fetch_array($sql)): ?>
                <input type="hidden" name="task_id" value="<?php echo $row['task_id']; ?>">
                <input type="text" name="title" value="<?php echo $row['title']; ?>"> <br><br>
                <input type="text" name="task_description" value="<?php echo $row['task_description']; ?>"><br><br>
                <input type="text" name="due_date" value="<?php echo $row['due_date']; ?>"><br><br>
                <input type="text" name="priority" value="<?php echo $row['priority']; ?>"> <br><br>

                <label for="completed">Status:</label>
                <select name="completed">
                <option value="0" <?php echo $row['completed'] == 0 ? 'selected' : ''; ?>>Not Completed</option>
                <option value="1" <?php echo $row['completed'] == 1 ? 'selected' : ''; ?>>Completed</option>
                </select><br><br>

                <input type="submit" name="updateForm" value="Update"><br><br>
        <?php endwhile; ?>
    </form>
</body>
</html>
