<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php

//validation 1 start
  
   //didn't delete fill names
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
}
  


    if (isset($_POST['delete_btn_set'])) {
        $user_id = $_POST['delete_id'];
        // getting the user information
        
        //deleting the user
        $query = "UPDATE appointment SET is_deleted = 1 WHERE appointment_id = {$user_id} LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result) {
            // user deleted
            header('Location: onlineCustomers.php');
        }
    }else {
        header('Location: onlineCustomers.php');
    }

   ?>