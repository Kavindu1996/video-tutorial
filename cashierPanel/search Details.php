<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'cashierSidebar.php';?>

<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['cashier_id'])) {
        header('Location: ../mainLogin.php');
    }

    //getting the staff records from the database
    $search = '';
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($connection, $_GET['search']);
        $query = "SELECT * FROM appointment INNER JOIN customer ON appointment.phone_number = customer.phone_number WHERE (customer.phone_number LIKE '{$search}%') AND appointment.is_deleted = '0' GROUP BY email;";
    }
    else {
        $query = "SELECT * FROM appointment INNER JOIN customer ON appointment.phone_number = customer.phone_number AND appointment.is_deleted = '0' GROUP BY email;";
    }
    $customerMembers = mysqli_query($connection, $query);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Appointment</title>
    <link rel="stylesheet" href="CSS/search Details Style.css">
</head>
<body>
<main class="container">
    <div class="sub-section">
        <div class="headding">
            <span class ="button_Next_Text">Appointment</span>
        </div>
        <button type="button" id="addNew" name="submit" onclick="window.open('appointment.php','_top');">
            <span>Add New</span>
        </button>

        <div class="search">
            <form action="search Details.php" method="get">
                <label>Search:</label>
                <input type="text" name="search" class="searchBox" value="<?php echo $search; ?>" autofocus>
            </form>
        </div>
    </div>

    <div class="body">
        <table class="masterlist">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Tel</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Data is displayed after successful query execution
                    if ($customerMembers) {
                        while($customer = mysqli_fetch_assoc($customerMembers)) {

                            ?>
                            <tr>
                                <td data-label="First Name"><?php echo $customer['first_name'];?></td>
                                <td data-label="Last Name"><?php echo $customer['last_name']; ?></td>
                                <td data-label="Address"><?php echo $customer['address'];?></td>
                                <td data-label="Email"><?php echo $customer['email']; ?></td>
                                <td data-label="Tel"><?php echo $customer['phone_number'];?></td>
                                <td>
                                    <!-- Add Appointment -->
                                    <button type="button" id='addNew4' name="submit" onclick="window.open('editAppointmentDetails.php?user_id=<?php echo $customer['appointment_id'];?>','_top');">                                     
                                    <span class ="button_text">Add Appointment</span>
                                    </button> 
                                </td>
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