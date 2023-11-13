<?php
      require 'config/connection.php';
      $connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

      $task_id = $_GET['event_id'];

      $deleteLogs = "DELETE FROM events_logs WHERE event_id = "$task_id"";
      $deleteEvent = "DELETE FROM events WHERE event_id ='$event_id'";
      
      $resultLogs = mysqli_query($connection, $deleteLogs);
      $resultEvent = mysqli_query($connection, $deleteEvent);

      if ($resultLogs && $resultEvent) 
        {
           header('location: createEvent.php');
           exit();
        }
      else{
         echo "Error Deleting Event or Event log" . mysqli_error($connection);
      }
?>