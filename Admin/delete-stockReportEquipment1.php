<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php

//validation 1 start
  
   //didn't delete fill names
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }
    

    if (isset($_POST['delete_btn_set'])) {
        $equipment_id = $_POST['delete_equipment_id'];
        // getting the user information
        
        //deleting the user
         $query = "UPDATE equipment SET is_deleted = 1 WHERE equipment_id = {$equipment_id} LIMIT 1";
        $result = mysqli_query($connection, $query);
    
        if ($result) {
            // user deleted
            header('Location: stockReportEquipment.php');
        }
    }else {
        header('Location: stockReportEquipment.php');
    }

   ?>