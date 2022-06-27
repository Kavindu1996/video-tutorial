<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php

    //Checking if a valid user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
        }

    $user_id = '';
    $today = date("Y-m-d");
    //getting the data from the database
    $query = "SELECT COUNT(appointment.appointment_id) AS customer_count, SUM(appointment.amount) AS total, staff.first_name As staff_name FROM appointment INNER JOIN staff ON appointment.staff_id = staff.id WHERE appointment_date = '$today' GROUP BY staff_id;";
    $customerMembers = mysqli_query($connection, $query);
    $total = 0;
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> 
    <link rel="stylesheet" href="CSS/incomeStyle2.css">
    
</head>
<body>
    <main class="container">
        <div class="sub-section">
            <div class="headding">
                <span class ="button_Next_Text">Today Income</span>

                <div class="today">
                    <?php
                        echo "Date: $today"  ;
                    ?>
                </div>
            </div>
        </div>
       
        <div class="body">
            <table class="masterlist">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>No of customers</th>
                        <th>total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //Data is displayed after successful query execution
                        if ($customerMembers) {
                            while ($customer = mysqli_fetch_assoc($customerMembers)) {
                                $total =  $total + $customer['total'];
                                ?>

                                    <tr>
                                        <td><?php echo $customer['staff_name']; ?></td>
                                        <td><?php echo $customer['customer_count']; ?></td>
                                        <td><?php echo $customer['total']; ?></td>
                                
                                    </tr>
                                <?php
                                
                            }
                        }else {
                            ?>

                            <tr>
                                <td>echo "Database query failed.";</td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
                <tfoot>
                    <th colspan = 2>Total</th> 
                    <th><?php echo $total ?></th>
                </tfoot>
            </table>
        </div>
    </main>
    <script>
        // table row style
        //https://www.w3schools.com/cssref/sel_nth-child.asp
        var odd = document.querySelectorAll('tr:nth-child(odd)');
        var even = document.querySelectorAll('tr:nth-child(even)');
        for(var i of odd) {
            i.style.backgroundColor = '#f2f2f2';
        }

        for(var i of even) {
            i.style.backgroundColor = 'white';
        }
    </script>
</body>
</html>
<?php mysqli_close($connection); ?>