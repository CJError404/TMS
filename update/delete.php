<?php
      require 'config/connection.php';
      $connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

      $task_id = $_GET['task_id'];

      $deleteTask = "DELETE FROM tasks WHERE task_id ='$task_id'";
      $deleteLogs = "DELETE FROM tasks_logs WHERE task_id = '$task_id'";

      $resultTasks = mysqli_query($connection, $deleteTask);
      $resultLogs = mysqli_query($connection, $deleteLogs);
      
      if ($resultTasks && $resultLogs) 
        {
           header('location: create.php');
           exit();
        }
      else{
         echo "Error Deleting task or task log" . mysqli_error($connection);
      }
?>
