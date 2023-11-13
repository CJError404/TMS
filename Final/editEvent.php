<?php
require_once 'config/connection.php';

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);
$event_id = $_GET['event_id'];
$query = "SELECT * FROM events WHERE event_id ='$event_id'";
$sql = mysqli_query($connection, $query);

$errors = array();

if (isset($_POST['updateForm'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $event_description = $_POST['event_description'];
    $event_datetime = $_POST['date_time'];

    if (count($errors) == 0) {
        $updateLogs = "UPDATE event_logs SET end_time = $event_datetime";
        if (!mysqli_query($connection, $updateLogs);){
            $errors[] = mysqli_error($connection);
        }

        $updateQuery = "UPDATE events SET title = '$title', event_description = '$event_description', date_time = '$event_datetime',  WHERE event_id = '$event_id'";
        $stmt = mysqli_query($connection, $updateQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $title, $event_description, $event_datetime);
            if (!mysqli_stmt_execute($stmt)){
                $errors[] = mysqli_error($connection);
            }
        }
        else {
            $errors[] = mysqli_stmt_error($stmt);
        }
        if (count($errors) == 0){
        header('location: createEvent.php');
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
    <title>Edit Event</title>
</head>
<body>
<form action="editEvent.php?task_id=<?php echo $task_id; ?>" method="POST">
    <?php while($row = mysqli_fetch_array($sql)): ?>
        <!-- Keep task_id as a hidden input field -->
        <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
        <input type="text" name="title" value="<?php echo $row['title']; ?>"> <br><br>
        <input type="text" name="event_description" value="<?php echo $row['event_description']; ?>"><br><br>
        <input type="text" name="date_time" value="<?php echo $row['date_time']; ?>"><br><br>
        <input type="submit" name="updateForm" value="Update"><br><br>
    <?php endwhile; ?>
</form>
</body>
</html>
