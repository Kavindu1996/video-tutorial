<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php

//validation 1 start
  
   //didn't delete fill names
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }
    

    if (isset($_POST['delete_btn_set'])) {
        $moisturizer_id = $_POST['delete_moisturizer_id'];
        //$moisturizer_image = $_POST['delete_moisturizer_image'];
        // getting the user information
        // $user_id = mysqli_real_escape_string($shampooConnection, $_GET['user_id']);
        
        //deleting the user
        $query = "UPDATE moisturizer SET is_deleted = 1 WHERE moisturizer_id = {$moisturizer_id} LIMIT 1";
        //$query = "DELETE FROM moisturizer WHERE id='$shampoo_id'"; 
        $result = mysqli_query($connection, $query);

        if ($result) {
            // user deleted
            //unlink("../upload/moisturizing/".$moisturizer_image);
            header('Location: stockReportMoisturizer.php');
        }
    }else {
        header('Location: stockReportMoisturizer.php');
    }

   ?>