<?php require_once('../database/inc/connection.php')?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!-- <title>nav bar</title> -->
    <link rel="stylesheet" href="CSS/navbarStyle5.css">
</head>
<body>
    
    <div class="nav-bar">
        <?php
            $query = "SELECT salon_name FROM salon WHERE salon_id = 1";
            $result_set = mysqli_query($connection, $query);
            foreach ($result_set as $row) {
            ?>
        <div class="left_area"><h3><?php echo $row['salon_name']?></h3></div>
        <?php
        }
        ?>

        <div class="right_area">Welcome <?php echo $_SESSION['admin_name'] ?>! 
            <button type="button" class="logout_btn"  onclick="window.open('../mainLogout.php','_top');">
            <span>Logout</span>
            </button>
        </div>
    </div>

</body>
</html>