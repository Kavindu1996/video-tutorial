<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php

//validation 1 start
  
   //didn't delete fill names
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }
    

    if (isset($_POST['delete_btn_set'])) {
        $notice_id = $_POST['delete_id'];
        // getting the user information
        

        $query = "UPDATE notice SET is_deleted = 1 WHERE id = {$notice_id} LIMIT 1";
        $result = mysqli_query($connection, $query);
   
        if ($result) {
            // user deleted
            header("Location: notice.php");
        
    }else {
        header("Location: notice.php");
    }
}
   ?>