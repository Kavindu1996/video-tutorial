<?php session_start(); ?>
<?php include 'cashierNavbar.php';?>
<?php require_once('../database/inc/connection.php')?>
<?php

    //Checking if a valid user is logged in
    if (!isset($_SESSION['cashier_id'])) {
        header('Location: ../mainLogin.php');
    }
    

$errors             = array();
$user_id            = '';
$first_name         = '';
$last_name          = '';
$phone_number       = '';
$email              = '';
$address            = '';
$appointment_date   = '';
$service            = '';
$role               = '';


if (isset($_GET['user_id'])) {
    // getting the user information
    $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
    $query = "SELECT * FROM appointment INNER JOIN customer ON appointment.phone_number = customer.phone_number WHERE appointment_id = {$user_id} LIMIT 1;";

    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $result = mysqli_fetch_assoc($result_set);
            $first_name             = $result['first_name'];
            $last_name              = $result['last_name'];
            $phone_number           = $result['phone_number'];
            $email                  = $result['email'];
            $address                = $result['address'];
            $appointment_date       = $result['appointment_date'];
            $service                = $result['service'];
            $staff_id               = $result['staff_id'];
            $role                   = $result['role'];
        }else {
            header('Location: onlineCustomers.php?user_not_found');
        }
    }else {
        header('Location: onlineCustomers.php?query_failed');
    }
}


if (isset($_POST['submit'])) {
    $user_id            = $_POST['user_id'];
    $appointment_date   = $_POST['appointment_date'];
    $service            = $_POST['service'];
    $role               = $_POST['role'];
    $phone_number       = $_POST['phone_number'];
    $staff_id           = $_POST['staff_id'];
    $employee_id        = $_SESSION['cashier_id'];


    //checking max length
    $max_len_fields = array('service' =>1000);

    foreach ($max_len_fields as $field => $max_len) {
        if (strlen(trim($_POST[$field])) > $max_len){
            $errors[] = $field . ' must be less than ' . $max_len . ' characters';
        }
    }

    if (empty($errors)) {
        $service = mysqli_real_escape_string($connection, $_POST['service']);
        $query = "UPDATE appointment SET appointment_date = '{$appointment_date}', service = '{$service}', staff_id = '{$staff_id}', role = '{$role}', phone_number ='{$phone_number}', employee_id = '{$employee_id}' WHERE appointment_id = {$user_id} LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header('Location:  onlineCustomers.php?appointment_modified=true');
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
    <title>Modify Appointment</title>
    <link rel="stylesheet" href="CSS/editAppointmentStyle5.css">
</head>

<body>
    <div class="container">
        <div class="title">
            <div class="routing">Online Customers / Modify Appointment</div>
            <b>Modify Appointment</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Modify Appointment</b>
        </div>
        <div class="mobilerouting">Online Customers / Modify Appointment</div>
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
        <form action="editCustomerDetails.php" method="post" class="userform">
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
                        
                            </div>
                        </div>
                        <div class="firstRight">
                            <div class="labelColumnFirstRight">
                                <label>Last Name</label>
                            </div>
                            <div class="inputColumnFirstRight">
                                <input id="inputLastName" type="text" name="last_name" placeholder="Last Name" <?php echo 'value="' . $last_name . '"'; ?> disabled>
                        
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
                            </div>
                        
                        </div>
                        <div class="thirdRight">
                            <div class="labelColumnThirdRight">
                                <label>Appointment Date</label>
                            </div>
                            <div class="inputColumnThirdRight">
                                <input id="inputAppointmentDate"  type="date" name="appointment_date" placeholder="" <?php echo 'value="' . $appointment_date . '"'; ?> required>        
                    
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box"> 
                    <div class="input_box_forth">
                        <div class="forthLeft">
                            <div class="labelColumnForthLeft">
                                <label>Services</label>
                            </div>
                            <div class="inputColumnForthLeft">
                                <input  type="text" name="service" placeholder="Service" <?php echo 'value="' . $service . '"'; ?> required>      
    
                            </div>
                        </div>
 

                        <?php
                            $connection = mysqli_connect('localhost', 'root', '', 'user_db');
                            $query = "SELECT * FROM staff WHERE status='online' AND role_id = '4'";
                            $result2 = mysqli_query($connection, $query);

                            if (mysqli_num_rows($result2) > 0) 
                            {
                                ?>
                                
                                    <div class="forthRight">
                                        <div class="labelColumnForthRight">
                                            <label>Stylish</label>
                                        </div>
                                        <div class="inputColumnForthRight">  
                                            <select id="inputRole" name="staff_id" class="inputColumnTwo" required>
                                                <option value="">Select Stylish</option>
                                                <?php
                                                foreach($result2 as $row)
                                                {
                                                ?>                                              
                                                <option value="<?php echo $row['id'];?>"><?php echo $row['first_name'];?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php
                            }      
                            else{
                            ?>
                                <div class="forthRight">
                                    <div class="labelColumnForthRight">
                                        <label>Stylish</label>
                                    </div>
                                <div class="inputColumnForthRight">  
                                    <select id="inputRole" name="staff_id" class="inputColumnTwo" >
                                        <option value="">Select Stylish</option>                                            
                                        <option value=""></option>
                                        }
                                    </select>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                    </div>    
                </div>

                <div class="box"> 
                        <div class="fifthLeft">
                            <div class="labelColumnFifthLeft">
                                <label>Update</label>
                            </div>
                            <div class="inputColumnFifthLeft">         
                                <select id="inputRole" name="role" class="inputColumnTwo" required>
                                    <option value=""> Select Role</option>
                                    <option value="pending">Pending</option>
                                    <option value="On Going">On Going</option>
                            </select> 
                            </div>
                        </div>
                    </div>    
                </div>
        
                </div>
                    <div class="button">
                    <button id = "submit" type="submit" name="submit">Submit</button>
                    <button type="button" class="back"  onclick="window.open('onlineCustomers.php','_top');">Back</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection); ?>