<?php
      require 'config/connection.php';
      $connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

      $event_id = $_GET['event_id'];

      $deleteLogs = "DELETE FROM event_logs WHERE event_id = '$event_id";
      $deleteEvent = "DELETE FROM events_ WHERE event_id ='$event_id'";
      $resultLogs = mysqli_query($connection, $deleteLogs)
      $resultEvent = mysqli_query($connection, $deleteEvent);

      if ($resultEvent && $resultLogs) 
        {
           header('location: create_event.php');
           exit();
        }
      else{
         echo "Error Deleting Event or Event log" . mysqli_error($connection);
      }
?>