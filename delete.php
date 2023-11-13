<?php
      require 'config/connection.php';
      $connection = mysqli_connect(DB_HOST, DB_USER, PASSWORD, DB_NAME);

      $task_id = $_GET['task_id'];

      $sql = "DELETE FROM tasks WHERE task_id ='$task_id'";
      $result = mysqli_query($connection, $sql);

      if ($result == TRUE) 
        {
           header('location: create.php');
           exit();
        }
?>