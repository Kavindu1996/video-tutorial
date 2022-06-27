<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php
  
   //security
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }
    

    if (isset($_POST['delete_btn_set'])) {
        $user_id = $_POST['delete_id'];

        // query
        $query = "UPDATE staff SET is_deleted = 1 WHERE id = {$user_id} AND role_id != '1' LIMIT 1";
        $result = mysqli_query($connection, $query);
   
        if ($result) {
            // user deleted
            header("Location: staffList.php");
        
    }else {
        header("Location: staffList.php");
    }
}
   ?>