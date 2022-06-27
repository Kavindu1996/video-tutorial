<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php

    //Checking if a valid user is logged in
    if (!isset($_SESSION['cashier_id'])) {
        header('Location: ../mainLogin.php');
    }
    

$errors             = array();
$user_id            = '';
$service1           = '';
$service2           = '';
$service3           = '';
$amount1            = '';
$amount2            = '';
$amount3            = '';



//get the information from the database
if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
    $query = "SELECT * FROM appointment INNER JOIN customer ON appointment.phone_number = customer.phone_number WHERE appointment_id = {$user_id} LIMIT 1;";
    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $result         = mysqli_fetch_assoc($result_set);
            $service1      = $result['service1'];
            $service2      = $result['service2'];
            $service3       = $result['service3'];
            $amount1         = $result['amount1'];
            $amount2        = $result['amount2'];
            $amount3        = $result['amount3'];

        }else {
            header('Location: customerDetails.php?user_not_found');
        }
    }else {
        header('Location: customerDetails.php?query_failed');
    }
}



if (isset($_POST['submit'])) {

    $user_id            = $_POST['user_id'];
    $service1           = $_POST['service1'];
    $service2           = $_POST['service2'];
    $service3           = $_POST['service3'];
    $amount1            = $_POST['amount1'];
    $amount2           = $_POST['amount2'];
    $amount3           = $_POST['amount3'];
    $employee_id        = $_SESSION['cashier_id'];




    if (empty($errors)) {
        $service = mysqli_real_escape_string($connection, $_POST['service']);
        $query = "INSERT INTO add_new_service (service_name,amount, employee_id, is_deleted) VALUES ('{$service_name}', '{$amount}', '{$employee_id}', 0 )";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header('Location: search Details.php?appointment_modified=true');
        }else{
            $errors[] = 'Failed to modify the record';
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Add Service</title>
    <link rel="stylesheet" href="CSS/serviceStyle.css">
</head>

<body>
    <div class="container">
        <div class="title">
            <div class="routing">Appointment / Add Service</div>
            <b>Add Service</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Add Service</b>
        </div>
        <div class="mobilerouting">Appointment / Add Service</div>

        <!-- edit customer personal details -->
        <?php


            //validation display
            if (!empty($errors)) {
                echo '<div class="errmsg">';
                foreach ($errors as $error) {
                    echo '- ' . $error . '</br>';
                }
                echo '</div>';
            }
        
        ?>


        <form action="serviceAdd.php" method="post" class="userform">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <div class="user-details">
                <div class="box"> 
                    <div class="input_box_first">
                        <div class="firstLeft">
                            <div class="labelColumnFirstLeft">
                                <label>Service 1</label>
                            </div>
                            <div class="inputColumnFirstLeft">
                                <input id="inputFirstName" type="text" name="service1" placeholder="service 1" <?php echo 'value="' . $service1 . '"'; ?> >
                            </div>
                        </div>
                        <div class="firstRight">
                            <div class="labelColumnFirstRight">
                                <label>Amount 1<label>
                            </div>
                            <div class="inputColumnFirstRight">
                                <input id="amount1" type="text" name="amount1" placeholder="amount 1" <?php echo 'value="' . $amount1. '"'; ?> >
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box"> 
                    <div class="input_box_second">
                        <div class="secondLeft">
                            <div class="labelColumnSecondLeft">
                                <label>Service 2</label>
                            </div>
                            <div class="inputColumnSecondLeft">
                                <input id="service2" type="text" name="service2" placeholder="service 2" <?php echo 'value="' . $service2 . '"'; ?> >         
                            </div>
                        </div>

                        <div class="secondRight">
                            <div class="labelColumnSecondRight">
                                <label>Amount 2</label>
                            </div>
                            <div class="inputColumnSecondRight">                        
                                <input id="inputEmail" type="text" name="amount2" placeholder="amount 2" <?php echo 'value="' . $amount2 . '"'; ?> >
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box">
                    <div class="input_box_third">
                        <div class="thirdLeft">
                            <div class="labelColumnThirdLeft">
                                <label>Service 3</label>
                            </div>
                            <div class="inputColumnThirdLeft">
                                <input id="inputAddress" type="text" name="service3" placeholder="service 3" <?php echo 'value="' . $service3 . '"'; ?> >                                                        
                            </div>
                        </div>
                        <div class="thirdRight">
                            <div class="labelColumnThirdRight">
                                <label>Amount3</label>
                            </div>
                            <div class="inputColumnThirdRight">
                                <input id="inputAppointmentDate"  type="text" name="amount3" placeholder="amount 3" <?php echo 'value="' . $amount3 . '"'; ?> >        
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                    <div class="button">
                    <button id = "submit" type="submit" name="submit">Submit</button>
                    <button type="button" class="back"  onclick="window.open('appointment.php','_top');">Back</button>
                </div>  
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection); ?>