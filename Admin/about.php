<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
        }

    $errors                 = array();
    $salon_id               = '';
    $salon_name             = '';
    $last_name              = '';
    $phone_number           = '';
    $address                = '';
    $email                  = '';
    $manager_first_name     = '';
    $manager_last_name      = '';
    $manager_email          = '';
    $manager_phone_number   = '';
    $cashier_first_name     = '';
    $cashier_last_name      = '';
    $cashier_phone_number   = '';
    $cashier_email          = '';


        //getting the salon information from the database
        $query = "SELECT * FROM salon WHERE salon_id = '1' ";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            //Data is displayed after successful query execution
            if (mysqli_num_rows($result_set) == 1) {
                $result             = mysqli_fetch_assoc($result_set);
                $salon_name         = $result['salon_name'];
                $salon_phone_number = $result['phone_number'];
                $salon_address      = $result['address'];
                $salon_email        = $result['email'];              
            }else {
                header('Location: dashboard.php?user_not_found');
            }
        }else {
            header('Location: dashboard.php?query_failed');
        }


        //getting the manager information from the database
        //$query = "SELECT * FROM staff INNER JOIN role ON staff.role_id = role.role_id WHERE role_name = 'manager' AND is_deleted = 0 LIMIT 1";
        $query = "SELECT * FROM staff WHERE role_id = '3' AND is_deleted = 0 LIMIT 1 ";
        $manager_result_set = mysqli_query($connection, $query);

        if ($manager_result_set) {
            //Data is displayed after successful query execution
            if (mysqli_num_rows($manager_result_set) == 1) {
                $result                 = mysqli_fetch_assoc($manager_result_set);
                $manager_first_name     = $result['first_name'];
                $manager_last_name      = $result['last_name'];
                $manager_email          = $result['email'];
                $manager_phone_number   = $result['phone_number'];
                
            }else {
                header('Location: settings.php?settings_not_found');
            }
        }else {
            header('Location: settings.php?query_failed');
        }


         //getting the cashier information from the database
        //$query = "SELECT * FROM staff INNER JOIN role ON staff.role_id = role.role_id WHERE role_name = 'cashier' AND is_deleted = 0 LIMIT 1";
        $query = "SELECT * FROM staff WHERE role_id = '2' AND is_deleted = 0 LIMIT 1 ";
        $cashier_result_set = mysqli_query($connection, $query);

        if ($cashier_result_set) {
            if (mysqli_num_rows($cashier_result_set) == 1) {
                //Data is displayed after successful query execution
                $result                 = mysqli_fetch_assoc($cashier_result_set);
                $cashier_first_name     = $result['first_name'];
                $cashier_last_name      = $result['last_name'];
                $cashier_phone_number   = $result['phone_number'];
                $cashier_email          = $result['email'];
            }else {
                header('Location: settings.php?settings_not_found');
            }
        }else {
            header('Location: settings.php?query_failed');
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="CSS/aboutStyle13.css">
</head>
<body>
<div class="container">
    <div class="sub-section">
        <div class="headding">
            <span class ="button_Next_Text">About</span>
        </div>
    </div>

    <div class="information">
        <h2>Salon Information</h2>
            <hr class='mainLine'>
        <table class="table">
            <tr>
                <th>Salon Name  :   </th>
                <td><?php echo '' . $salon_name . ''; ?></td>
            </tr>
            <tr>
                <th>Phone Number       :</th>
                <td><?php echo '' . $salon_phone_number . ''; ?></td>
            </tr>
            <tr>
                <th>Email       :</th>
                <td><?php echo '' . $salon_email . ''; ?></td>
            </tr>
            <tr>
                <th>Address    :</th>
                <td><?php echo '' . $salon_address . ''; ?></td>
            </tr>
        </table>

        <h2>Manager Information</h2>
            <hr class='mainLine'>
        <table class="table">
            <tr>
                <th>First Name    :</th>
                <td><?php echo '' . $manager_first_name . ''; ?></td>
            </tr>
            <tr>
                <th>Last Name      :</th>
                <td><?php echo '' . $manager_last_name . ''; ?></td>
            </tr>
            <tr>
                <th>Email       :</th>
                <td><?php echo '' . $manager_email . ''; ?></td>
            </tr>
            <tr>
                <th>Phone Number     : </th>
                <td><?php echo '' . $manager_phone_number . ''; ?></td>
            </tr>
        </table>

        <h2>Cashier Information</h2>
            <hr class='mainLine'>
        <table class="table">
            <tr>
                <th>First Name    :</th>
                <td><?php echo '' . $cashier_first_name . ''; ?></td>
            </tr>
            <tr>
                <th>Last Name      :</th>
                <td><?php echo '' . $cashier_last_name . ''; ?></td>
            </tr>
            <tr>
                <th>Email       :</th>
                <td><?php echo '' . $cashier_email . ''; ?></td>
            </tr>
            <tr>
                <th>Phone Number     : </th>
                <td><?php echo '' . $cashier_phone_number . ''; ?></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
<?php mysqli_close($connection); ?>