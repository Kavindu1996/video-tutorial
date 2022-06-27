<?php session_start(); ?>
<?php include 'cashierNavbar.php';?>
<?php require_once('../database/inc/connection.php')?>


<?php

//Checking if a valid user is logged in
if (!isset($_SESSION['cashier_id'])) {
    header('Location: ../mainLogin.php');
}
      
    $user_id        = '';
    $first_name     = '';
    $last_name      = '';
    $phone_number   = '';
    $address        = '';
    $email          = '';
    $nic            = '';
    $stud_image     = '';
    $date_of_birth  = '';
    $gender         = '';
    $role_name      = '';
    $status         = '';

    //getting the selected user information from the database
    if (isset($_GET['user_id'])) {
        $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
        $query = "SELECT * FROM staff INNER JOIN role ON staff.role_id = role.role_id WHERE id = {$user_id} LIMIT 1";

        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result         = mysqli_fetch_assoc($result_set);
                $first_name     = $result['first_name'];
                $last_name      = $result['last_name'];
                $phone_number   = $result['phone_number'];
                $address        = $result['address'];
                $email          = $result['email'];
                $nic            = $result['nic'];
                $role_name           = $result['role_name'];
                $stud_image     = $result['stud_image'];
                $date_of_birth  = $result['date_of_birth'];
                $gender         = $result['gender'];
                $status         = $result['status'];
            }else {
                header('Location: staffDetails.php?user_not_found');
            }
        }else {
            header('Location: staffDetails.php?query_failed');
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profile View</title>
    <link rel="stylesheet" href="CSS/staffProfileViewNewStyle8.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">Staff Members / Staff Information</div>
            <b>Staff Information</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Staff Information</b>
        </div>
        <div class="mobilerouting">Staff Members / Staff Information</div>
        
        <!-- full-details -->
        <div class="both">
            <div class="left">
                <div class="image">
                    <img src="<?php echo "../upload/staff/".$stud_image ; ?>" width="100" alt="image">
                </div>                    
            </div>

            <div class="right">
                <div class="name1">
                    <?php echo  " $first_name   $last_name" ?>
                </div>
                <div class="role">
                    <?php echo  "$role_name"?>
                </div>

                <hr>

                <table class="table">
                    <tr>
                        <th>Birthday    </th>
                        <td><?php echo $date_of_birth ; ?></td>
                    </tr>
                    <tr>
                        <th>Gender      </th>
                        <td><?php echo $gender; ?></td>
                    </tr>
                    <tr>
                        <th>Nic      </th>
                        <td><?php echo $nic; ?></td>
                    </tr>
                    <tr>
                        <th>Email       </th>
                        <td><?php echo $email; ?></td>
                    </tr>
                    <tr>
                        <th>PhoneNumber </th>
                        <td><?php echo $phone_number ; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $address ;?></td>
                    </tr>
                    <tr>
                        <th>Status </th>
                        <td><?php echo  $status; ?></td>
                    </tr>
                </table>
                    <button class="close" type="button" name="submit" onclick="window.open('staffDetails.php','_top');">Close</button>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($connection); ?>

