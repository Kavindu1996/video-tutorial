<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>
<?php

    //checking if a user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
    }
    
$errors = array();
$user_id            = '';
$first_name         = '';
$last_name          = '';
$phone_number       = '';
$email              = '';
$address            = '';
$appointment_date   = '';
$service            = '';
$role               = '';
$payment_method     = '';
$amount             = '';



if (isset($_GET['user_id'])) {
   //get the information from the database
    $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
    $query = "SELECT * FROM appointment INNER JOIN customer ON appointment.phone_number = customer.phone_number WHERE appointment_id = {$user_id} LIMIT 1;";

    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $result = mysqli_fetch_assoc($result_set);
            $first_name         = $result['first_name'];
            $last_name          = $result['last_name'];
            $phone_number       = $result['phone_number'];
            $email              = $result['email'];
            $address            = $result['address'];
            $appointment_date   = $result['appointment_date'];
            $amount             = $result['amount'];
            $service            = $result['service'];
            $role               = $result['role'];
            $payment_method     = $result['payment_method'];
        }else {
            header('Location: onlineCustomers.php?user_not_found');
        }
    }else {
        header('Location: onlineCustomers.php?query_failed');
    }
}


if (isset($_POST['submit'])) {

    $user_id          = $_POST['user_id'];
    $first_name       = $_POST['first_name'];
    $last_name        = $_POST['last_name'];
    $phone_number     = $_POST['phone_number'];
    $email            = $_POST['email'];
    $address          = $_POST['address'];
    $appointment_date = $_POST['appointment_date'];
    $service          = $_POST['service'];
    $amount           = $_POST['amount'];
    $role             = $_POST['role'];
    $payment_method   = $_POST['payment_method'];
    $employee_id      = $_SESSION['admin_id'];

    $req_field = array('user_id', 'payment_method', 'amount', 'role');

    foreach ($req_field as $field) {
        if (empty(trim($_POST[$field]))){
            $errors[] = $field . ' is required';
        }
    }


    //checking max length
    $max_len_fields = array('amount' =>255);

    foreach ($max_len_fields as $field => $max_len) {
        if (strlen(trim($_POST[$field])) > $max_len){
            $errors[] = $field . ' must be less than ' . $max_len . ' characters';
        }
    }
    

    if (empty($errors)) {
        $amount = mysqli_real_escape_string($connection, $_POST['amount']);
        $query = "UPDATE appointment SET   payment_method = '{$payment_method}', amount = '{$amount}', role = '{$role}', employee_id = '{$employee_id}' WHERE appointment_id = {$user_id} LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header('Location: appointment.php?appointment_modified=true');
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
    <title>Payment</title>
    <link rel="stylesheet" href="CSS/finishStyle.css">
</head>

<body>
    <div class="container">
        <div class="title">
            <div class="routing">Online Customers / Payment</div>
            <b>Payment</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Payment</b>
        </div>
        <div class="mobilerouting">Online Customers / Payment</div>

       <!--validation display-->

        <?php
        
            if (!empty($errors)) {
                echo '<div class="errmsg">';
                foreach ($errors as $error) {
                    echo '- ' . $error . '</br>';
                }
                echo '</div>';
            }
        
        ?>

        <form action="finish.php" method="post" class="userform">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <div class="user-details">

                <div class="box"> 
                    <div class="input_box_first">
                        <div class="firstLeft">
                            <div class="labelColumnFirstLeft">
                                <label>First Name</label>
                            </div>
                            <div class="inputColumnFirstLeft">
                                <input id="inputFirstName" type="text" name="first_name" placeholder="First Name" <?php echo 'value="' . $first_name . '"'; ?> disabled>
                                <input id="inputFirstName" type="hidden" name="first_name" placeholder="First Name" <?php echo 'value="' . $first_name . '"'; ?> >
                            </div>
                        </div>
                        <div class="firstRight">
                            <div class="labelColumnFirstRight">
                                <label>Last Name</label>
                            </div>
                            <div class="inputColumnFirstRight">
                                <input id="inputLastName" type="text" name="last_name" placeholder="Last Name" <?php echo 'value="' . $last_name . '"'; ?> disabled>
                                <input id="inputLastName" type="hidden" name="last_name" placeholder="Last Name" <?php echo 'value="' . $last_name . '"'; ?> >
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box"> 
                    <div class="input_box_second">
                        <div class="secondLeft">
                            <div class="labelColumnSecondLeft">
                                <label>Phone Number</label>
                            </div>
                            <div class="inputColumnSecondLeft">
                                <input id="inputPhoneNumber" type="text" name="phone_number" placeholder="Phone Number" <?php echo 'value="' . $phone_number . '"'; ?> disabled> 
                                <input id="inputPhoneNumber" type="hidden" name="phone_number" placeholder="Phone Number" <?php echo 'value="' . $phone_number . '"'; ?> >          
                            </div>
                        </div>

                        <div class="secondRight">
                            <div class="labelColumnSecondRight">
                                <label>Email</label>
                            </div>
                            <div class="inputColumnSecondRight">                        
                                <input id="inputEmail" type="email" name="email" placeholder="Email" <?php echo 'value="' . $email . '"'; ?> disabled>
                                <input id="inputEmail" type="hidden" name="email" placeholder="Email" <?php echo 'value="' . $email . '"'; ?> >
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box">
                    <div class="input_box_third">
                        <div class="thirdLeft">
                            <div class="labelColumnThirdLeft">
                                <label>Address</label>
                            </div>
                            <div class="inputColumnThirdLeft">
                                <input id="inputAddress" type="text" name="address" placeholder="Address" <?php echo 'value="' . $address . '"'; ?> disabled>     
                                <input id="inputAddress" type="hidden" name="address" placeholder="Address" <?php echo 'value="' . $address . '"'; ?> >                                                   
                            </div>
                        
                        </div>
                        <div class="thirdRight">
                            <div class="labelColumnThirdRight">
                                <label>Appointment Date</label>
                            </div>
                            <div class="inputColumnThirdRight">
                                <input id="inputAppointmentDate"  type="date" name="appointment_date" placeholder="" <?php echo 'value="' . $appointment_date . '"'; ?> disabled>        
                                <input id="inputAppointmentDate"  type="hidden" name="appointment_date" placeholder="" <?php echo 'value="' . $appointment_date . '"'; ?> > 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box"> 
                    <div class="input_box_forth">
                        <div class="forthLeft">
                            <div class="labelColumnForthLeft">
                                <label>payment method</label>
                            </div>
                            <div class="inputColumnForthLeft">
                                <select id="inputPayment" name="payment_method" >
                                    <option value=""> Select Payment Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                            </select>  
                            </div>
                        </div>

                        <div class="forthRight">
                            <div class="labelColumnForthRight">
                                <label>Services</label>
                            </div>
                            <div class="inputColumnForthRight">
                                <input  type="text" name="service" placeholder="Service" <?php echo 'value="' . $service . '"'; ?> disabled>         
                                <input  type="hidden" name="service" placeholder="Service" <?php echo 'value="' . $service . '"'; ?> > 
                            </div>
                        </div>
                    </div>    
                </div>
                <div class="box"> 
                    <div class="input_box_fifth">
                        <div class="fifthLeft">
                            <div class="labelColumnFifthLeft">
                                <label>Amount</label>
                            </div>
                            <div class="inputColumnFifthLeft">
                            <input  type="text" name="amount" placeholder="Amount" <?php echo 'value="' . $amount . '"'; ?>>            
                            </div>
                        </div>

                        <div class="fifthRight">
                            <div class="labelColumnFifthRight">
                                <label>Update</label>
                            </div>
                            <div class="inputColumnFifthRight">
                            <select id="inputRole" name="role" class="inputColumnTwo">
                                    <option value=""> Select Role</option>
                                    <option value="pending">Pending</option>
                                    <option value="On Going">On Going</option>
                                    <option value="finish">Finish</option>
                            </select>         
                            </div>
                        </div>
                    </div>
                </div> 

                </div>
                    <div class="button">
                    <button id = "submit" type="submit" name="submit" >finish</button>
                    <button type="button" class="back"  onclick="window.open('onlineCustomers.php','_top');">Back</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection); ?>