<?php session_start(); ?>
<?php include 'cashierNavbar.php';?>
<?php require_once('../database/inc/connection.php')?>

<?php

    //Checking if a valid user is logged in
    if (!isset($_SESSION['cashier_id'])) {
        header('Location: ../mainLogin.php');
    }
    
        
        //getting the salon informations from the database
        $query = "SELECT * FROM salon ";
        $result_set = mysqli_query($connection, $query);
        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result         = mysqli_fetch_assoc($result_set);
                $phone_number   = $result['phone_number'];
                $address        = $result['address'];
                $email          = $result['email'];
            }else {
                header('Location: customerDetails.php?bill_not_found');
            }
        }else {
            header('Location: customerDetails.php?query_failed');
        }
    

        
        // getting the user information from the database
        if (isset($_GET['user_id'])) {
            $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
            $query = "SELECT * FROM appointment INNER JOIN customer ON appointment.phone_number = customer.phone_number WHERE appointment_id = {$user_id} LIMIT 1;";      
            $result_set = mysqli_query($connection, $query);
    
            if ($result_set) {
                //Customer data is displayed after successful query execution
                if (mysqli_num_rows($result_set) == 1) {
                    $result             = mysqli_fetch_assoc($result_set);
                    $code               = $result['appointment_id'];
                    $Description        = $result['service'];
                    $Rate               = $result['amount'];
                    $appointment_date   = $result['appointment_date'];
                    $payment_method     = $result['payment_method'];
                    $Amount             = $result['amount'];
                    $first_name         = $result['first_name'];
                    $invoice_number     = $result['appointment_id'];
                }else {
                    header('Location: customerDetails.php?Bill_User_not_found');
                }
            }else {
                header('Location: customerDetails.php?query_failed');
            }
        }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="CSS/billStyle9.css">
    <title>Bill</title>
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">Customer Details / Payment Datails</div>
            <b>Payment Datails</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Payment Datails</b>
        </div>
        <div class="mobilerouting">Customer Details / Payment Datails</div>
        <div class="both">
            <table class="firstRow">
                <tr>
                    <th>Date / Time</th>
                    <td><?php echo ": " .$appointment_date ?></td>
                </tr>
                <tr>
                    <th>Cashier</th>
                    <td> <?php
                    $query = "SELECT staff.first_name FROM staff INNER JOIN appointment ON staff.id = appointment.employee_id WHERE appointment_id = {$user_id} LIMIT 1";
                    $result_set = mysqli_query($connection, $query);
                    if ($result_set) {
                        if (mysqli_num_rows($result_set) == 1) {
                            $result         = mysqli_fetch_assoc($result_set);
                            $cashier_name   = $result['first_name'];
                        }else {
                            header('Location: customerDetails.php?bill_not_found');
                        }
                    }else {
                        header('Location: customerDetails.php?query_failed');
                    }


                    echo ": " .$cashier_name  ?>
                    </td>
                </tr>
            </table>

            <div class="salonDetails">
                <div class="secondrow">
                    <!-- salon Details -->
                    <table class="tableleft">
                        <tr>
                            <th>Address</th>
                            <td> <?php echo ": " .$address ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td> <?php echo ": " .$email ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td> <?php echo ": " .$phone_number ?></td>
                        </tr>
                    </table>

                    <!-- Appointment Details -->
                    <table class="tableright">
                        <tr>
                            <th>Invoice Number </th>
                            <td><?php echo ":" .$invoice_number ?></td>
                        </tr>
                        <tr>
                            <th>Pay Mode </th>
                            <td><?php echo ":" .$payment_method ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- customer name-->
            <table  class = "tableleft2">
                <tr >
                    <th>Customer </th>
                    <td class="firstName"> <?php echo ":" .$first_name ?></td> 
                </tr>
            </table>
        </div>

        <!-- payment details-->
        <div class="body">
            <table class="masterlist">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Code"><?php echo "Code".  $code;?></td>
                        <td data-label="Description"><?php echo $Description;?></td>
                        <td data-label="Rate"><?php echo $Rate; ?></td>
                        <td data-label="Amount"><?php echo $Amount;?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <th colspan = 3>Total</th> 
                    <th><?php 
                    $total = 0;
                    $total =  $total + $Amount;
                    echo $total ?></th>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($connection); ?>