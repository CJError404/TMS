<?php
$page_title = 'Event Scheduling';
include('includes/header.html');
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
            background-color: #2c3e50;
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

        /* Adjust the input box for event description */
        form input[name="event_description"] {
            width: 100%;
            height: 100px; /* You can adjust the height as needed */
            box-sizing: border-box;
            text-align: left; /* Align the text to the top left */
            vertical-align: top; /* Align the input box to the top */
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

    <input type="text" name="title" placeholder="Add title" required><br><br>
    <input type="text" name="event_description" placeholder="Type details for this new event" required><br><br>
    <input type="datetime-local" name="date_time" required><br><br>
    <input type="submit" name="submitFormBtn" value="Schedule"><br><br>
</form>

<?php if (isset($_GET['success'])): ?>
    <div class="success-message">Event scheduled successfully!</div>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th> Event ID </th>
        <th> Title </th>
        <th> Description </th>
        <th> Date and Time </th>
        <th colspan="2"> Actions </th>
    </tr>
    </thead>
    <?php while($row = mysqli_fetch_array($query)): ?>
        <tbody>
        <tr>
            <td><?php echo($row['event_id']); ?></td>
            <td><?php echo($row['title']); ?></td>
            <td><?php echo($row['event_description']); ?></td>
            <td><?php echo($row['date_time']); ?></td>
            <td><a href="delete_event.php?event_id=<?php echo $row['event_id']; ?>">Cancel</a></td>
            <td><a href="edit_event.php?event_id=<?php echo $row['event_id']; ?>">Edit</a></td>
        </tr>
        </tbody>
    <?php endwhile; ?>
</table>

</body>
</html>