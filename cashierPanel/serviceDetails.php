<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'cashierNavbar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['cashier_id'])) {
        header('Location: ../mainLogin.php');
    }

    //getting the service records from the database
    $query = "SELECT * FROM service WHERE is_deleted = 0";
    $serviceType = mysqli_query($connection, $query);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Service</title>
    <link rel="stylesheet" href="CSS/serviceDetailsStyle.css">
</head>
<body>
    <main class="container">
        <div class="title">
            <div class="routing">Add Appointment / Service Details</div>
            <b>Service Details</b>
        </div>


        <div class="body">
            <table class="masterlist">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Service Charge</th>
                        <th>Duration</th>
                        <th>Service Category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //Data is displayed after successful query execution
                        if ($serviceType) {
                            //The records in the staffMembers is assigned to the service variable as an associative array
                            while ($service = mysqli_fetch_assoc($serviceType)) {

                    ?>
                            <tr>
                            <td data-label="Name"><?php echo $service['name'];?></td>
                            <td data-label="Description"><?php echo $service['description']; ?></td>
                            <td data-label="Service Charge"><?php echo $service['service_charge'];?></td>
                            <td data-label="Duration"><?php echo $service['duration']; ?></td>
                            <td data-label="Service Category"><?php echo $service['service_category'];?></td>
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
            </table>
        </div>
    </main>
<script>
    // table row style
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