<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
    }


    $notice_id          = '';
    $notice_title      = '';
    $notice_content    = '';

    //getting the information from the database
    if (isset($_GET['notice_id'])) {
        $notice_id = mysqli_real_escape_string($connection, $_GET['notice_id']);
        $query = "SELECT * FROM notice WHERE id = {$notice_id} LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result = mysqli_fetch_assoc($result_set);
                $notice_title   = $result['notice_title'];
                $notice_content = $result['notice_content'];           
            }else {
                header('Location: notice.php?notice_not_found');
            }
        }else {
            header('Location: notice.php?query_failed');
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link rel="stylesheet" href="CSS/detailsStyle14.css">
</head>
<body>
<div class="container">
    <div class="sub-section">
        <div class="headding">
            <span class ="button_Next_Text">Details</span>
        </div>
    </div>

    
    <div class="body">
        <!-- notice_title -->
            <h1><?php echo  " $notice_title" ?></h1>
        <!-- notice_content -->
        <div class="noticeDetails">
            <p><?php echo  " $notice_content" ?></p>
        </div>
    </div>

</div>
</body>
</html>
<?php mysqli_close($connection); ?>