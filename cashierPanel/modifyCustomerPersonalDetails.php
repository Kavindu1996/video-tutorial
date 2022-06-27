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



//get the information from the database
if (isset($_GET['phone_number'])) {
    $phone_number = mysqli_real_escape_string($connection, $_GET['phone_number']);
    $query = "SELECT * FROM customer WHERE phone_number = {$phone_number} LIMIT 1";
    $result_set = mysqli_query($connection, $query);


    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $result         = mysqli_fetch_assoc($result_set);
            $first_name     = $result['first_name'];
            $last_name      = $result['last_name'];
            $phone_number   = $result['phone_number'];
            $email          = $result['email'];
            $address        = $result['address'];

        }else {
            header('Location: editAppointmentDetails.php?user_not_found');
        }
    }else {
        header('Location: editAppointmentDetails.php?query_failed');
    }
}


if (isset($_POST['submit'])) {

    $first_name         = $_POST['first_name'];
    $last_name          = $_POST['last_name'];
    $phone_number       = $_POST['phone_number'];
    $email              = $_POST['email'];
    $address            = $_POST['address'];
    $appointment_date   = $_POST['appointment_date'];
    $service            = $_POST['service'];
    $role               = $_POST['role'];
    $staff_id           = $_POST['staff_id'];
    $employee_id        = $_SESSION['cashier_id'];


    //checking max length
    $max_len_fields = array( 'first_name' =>50, 'last_name' =>100, 'phone_number' =>11,'email' =>100,'address' =>200, 'service' =>100);
    foreach ($max_len_fields as $field => $max_len) {
        if (strlen(trim($_POST[$field])) > $max_len){
            $errors[] = $field . ' must be less than ' . $max_len . ' characters';
        }
    }

        // check if name only contains letters and whitespace
        //regex-regular expression
        if (!preg_match("/^[a-zA-Z-' ]*$/",$first_name,)) {
            $errors[] = "First Name Only letters allowed";
        }

        if (!preg_match("/^[a-zA-Z-' ]*$/",$last_name,)) {
            $errors[] = "Last Name Only letters allowed";
        }


    //Phone Number validation
    $phone_number = mysqli_real_escape_string($connection, $_POST['phone_number']);
    $query = "SELECT * FROM customerstaff WHERE phone_number = '{$phone_number}' AND phone_number !={$phone_number} AND is_deleted = '0' LIMIT 1";
    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $errors[] = 'Phone Number already exists';
        }
    }

    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }


    //staffConnection database
    if (empty($errors)) {
        $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $address = mysqli_real_escape_string($connection, $_POST['address']);
        $service = mysqli_real_escape_string($connection, $_POST['service']);


        $query = "UPDATE customer SET first_name = '{$first_name}', last_name = '{$last_name}', email = '{$email}', address = '{$address}', phone_number = '{$phone_number}', employee_id = '{$employee_id}' WHERE phone_number = {$phone_number} LIMIT 1";
        $result = mysqli_query($connection, $query);
        if ($result) {
            header('Location:  onlineCustomers.php?appointment_modified=true');
        }else{
            $errors[] = 'Failed to modify the record';
        }

        $query = "INSERT INTO appointment (appointment_date, service, staff_id, role, phone_number, employee_id, is_deleted) VALUES ('{$appointment_date}', '{$service}', '{$staff_id}', '{$role}', '{$phone_number}', '{$employee_id}', 0 )";
        $result = mysqli_query($connection, $query);
        if ($result) {
            header('Location:  onlineCustomers.php?appointment_modified & add=true');
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
    <title>Add Appointment</title>
    <link rel="stylesheet" href="CSS/editAppointmentStyle5.css">
</head>

<body>
    <div class="container">
        <div class="title">
            <div class="routing">Appointment / Add Appointment</div>
            <b>Add Appointment</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Add Appointment</b>
        </div>
        <div class="mobilerouting">Appointment / Add Appointment</div>
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
        <form action="modifyCustomerPersonalDetails.php" method="post" class="userform">
        <input type="hidden" name="phone_number" value="<?php echo $phone_number; ?>">
            <div class="user-details">
                <div class="box"> 
                    <div class="input_box_first">
                        <div class="firstLeft">
                            <div class="labelColumnFirstLeft">
                                <label>First Name</label>
                            </div>
                            <div class="inputColumnFirstLeft">
                                <input id="inputFirstName" type="text" name="first_name" placeholder="First Name" <?php echo 'value="' . $first_name . '"'; ?> required>
                        
                            </div>
                        </div>
                        <div class="firstRight">
                            <div class="labelColumnFirstRight">
                                <label>Last Name</label>
                            </div>
                            <div class="inputColumnFirstRight">
                                <input id="inputLastName" type="text" name="last_name" placeholder="Last Name" <?php echo 'value="' . $last_name . '"'; ?> required>
                        
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
                                <input id="inputPhoneNumber" type="text" name="phone_number" placeholder="Phone Number" <?php echo 'value="' . $phone_number . '"'; ?> required>         
                                <input id="inputPhoneNumber" type="hidden" name="phone_number" placeholder="Phone Number" <?php echo 'value="' . $phone_number . '"'; ?> >         

                            </div>
                        </div>

                        <div class="secondRight">
                            <div class="labelColumnSecondRight">
                                <label>Email</label>
                            </div>
                            <div class="inputColumnSecondRight">                        
                                <input id="inputEmail" type="email" name="email" placeholder="Email" <?php echo 'value="' . $email . '"'; ?> required>

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
                                <input id="inputAddress" type="text" name="address" placeholder="Address" <?php echo 'value="' . $address . '"'; ?> required>                                                        
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
                    <button type="button" class="back"  onclick="window.open('search Details.php','_top');">Back</button>
                </div>  

            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection); ?>