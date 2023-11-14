<?php
require_once 'config/connection.php';
$page_title = 'Edit';
include('includes/index_header.html');

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

$errors = array();

if(isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $query = "SELECT * FROM tasks WHERE task_id ='$task_id'";
    $sql = mysqli_query($connection, $query);

    if (!$sql) {
        $errors[] = mysqli_error($connection);
    }
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
            width: 20%;
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
<form action="edit_task.php?task_id=<?php echo $task_id; ?>" method="POST">
    <?php while($row = mysqli_fetch_array($sql)): ?>
        <input type="hidden" name="task_id" value="<?php echo $row['task_id']; ?>">
        <label for="title">Title</label>
        <input type="text" name="title" value="<?php echo $row['title']; ?>" required><br>
        <label for="task_description">Description</label>
        <textarea name="task_description" required><?php echo $row['task_description']; ?></textarea><br>
        <label for="due_date">Deadline</label>
        <input type="datetime-local" name="due_date" value="<?php echo date('Y-m-d\TH:i', strtotime($row['due_date'])); ?>" required><br><br>
        <label for="priority">Priority</label>
        <select name="priority" required>
            <option value="1" <?php echo $row['priority'] == 1 ? 'selected' : ''; ?>>High priority</option>
            <option value="2" <?php echo $row['priority'] == 2 ? 'selected' : ''; ?>>Medium priority</option>
            <option value="3" <?php echo $row['priority'] == 3 ? 'selected' : ''; ?>>Low priority</option>
        </select><br><br>
        <label for="completed">Status</label>
        <select name="completed" required>
            <option value="0" <?php echo $row['completed'] == 0 ? 'selected' : ''; ?>>In Progress</option>
            <option value="1" <?php echo $row['completed'] == 1 ? 'selected' : ''; ?>>Completed</option>
        </select><br><br>
        <input type="submit" name="updateForm" value="Update"><br><br>
    <?php endwhile; ?>
</form>
</body>
</html>