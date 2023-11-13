<?php
require_once 'config/connection.php';
error_reporting(E_ERROR | E_PARSE);

$connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

$display_all = "SELECT * FROM events_";
$query = mysqli_query($connection, $display_all);

$errors = array();

if (isset($_POST['submitFormBtn'])){
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $event_description = $_POST['event_description'];
    $date_time = $_POST['date_time'];

    if (count($errors) == 0){
        $insertQuery = "INSERT INTO events_ (title, event_description, date_time) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($insertQuery);
        $stmt ->bind_param('sss', $title, $event_description, $date_time);
        
        if ($stmt->execute()){
            header('location:createEvent.php');
        }   else{
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
    <title>ESS</title>
</head>
<body>
<?php if (count($errors) > 0): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="createEvent.php" method="POST">
    <input type="text" name="title" placeholder="title"><br><br>
    <input type="text" name="event_description" placeholder="description"><br><br>
    <input type="datetime-local" name="date_time"><br><br>
    <input type="submit" name="submitFormBtn" value="Submit"><br><br>
    <hr>
</form>

<table>
    <thead>
    <tr>
        <th> Event ID </th>
        <th> Title </th>
        <th> Description </th>
        <th> Date and Time </th>

    </tr>
    </thead>
    <?php while($row = mysqli_fetch_array($query)): ?>
        <tbody>
        <tr>
            <td><?php echo($row['event_id']); ?></td>
            <td><?php echo($row['title']); ?></td>
            <td><?php echo($row['event_description']); ?></td>
            <td><?php echo($row['date_time']); ?></td>
            <td><a href ="deleteEvent.php?event_id=<?php echo $row['event_id']; ?>">Cancel</td>
            <td><a href="editEvent.php?event_id=<?php echo $row['event_id']; ?>">Edit</a></td>
        </tr>
        </tbody>
    <?php endwhile; ?>
</table>
</body>
</html>
