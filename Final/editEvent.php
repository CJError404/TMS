<?php
require_once 'config/connection.php';

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

$errors = array();

if (!$connection) {
    $errors[] = mysqli_connect_error();
}

$event_id = $_GET['event_id'];
$query = "SELECT * FROM events_ WHERE event_id = ?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    if (mysqli_stmt_execute($stmt)) {
        $sql = mysqli_stmt_get_result($stmt);
    } else {
        $errors[] = mysqli_error($connection);
    }
} else {
    $errors[] = mysqli_error($connection);
}

if (isset($_POST['updateForm'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $event_description = $_POST['event_description'];
    $date_time = $_POST['date_time'];

    if (count($errors) == 0) {
        $updateLogs = "UPDATE event_logs SET end_time = ? WHERE event_id = ?";
        $stmtLogs = mysqli_prepare($connection, $updateLogs);
        if ($stmtLogs) {
            $updateLogs = "UPDATE event_logs SET end_time = NOW() WHERE event_id = ?";
            $stmtLogs = mysqli_prepare($connection, $updateLogs);

        if ($stmtLogs) {
            mysqli_stmt_bind_param($stmtLogs, "i", $event_id);
            if (!mysqli_stmt_execute($stmtLogs)) {
                $errors[] = mysqli_error($connection);
                }
        } 
        else {
            $errors[] = mysqli_error($connection);
        }       

        $updateQuery = "UPDATE events_ SET title = ?, event_description = ?, date_time = ? WHERE event_id = ?";
        $stmt = mysqli_prepare($connection, $updateQuery);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssi", $title, $event_description, $date_time, $event_id);
            if (!mysqli_stmt_execute($stmt)) {
                $errors[] = mysqli_error($connection);
            }
        } else {
            $errors[] = mysqli_error($connection);
        }

        if (count($errors) == 0) {
            header('location: createEvent.php');
            exit();
        }
    }
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Event</title>
</head>
<body>
<?php if (count($errors) > 0): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form action="editEvent.php?event_id=<?php echo $event_id; ?>" method="POST">
    <?php while($row = mysqli_fetch_array($sql)): ?>
        <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
        <input type="text" name="title" value="<?php echo $row['title']; ?>"> <br><br>
        <input type="text" name="event_description" value="<?php echo $row['event_description']; ?>"><br><br>
        <input type="text" name="date_time" value="<?php echo $row['date_time']; ?>"><br><br>
        <input type="submit" name="updateForm" value="Update"><br><br>
    <?php endwhile; ?>
</form>
</body>
</html>
