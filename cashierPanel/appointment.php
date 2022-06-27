<?php session_start(); ?>
<?php include 'cashierNavbar.php';?>
<?php require_once('../database/inc/connection.php')?>

<?php
  
    //Checking if a valid user is logged in
   if (!isset($_SESSION['cashier_id'])) {
    header('Location: ../mainLogin.php');
    }

    $errors             = array();
    $user_id             = '';
    $first_name         = '';
    $last_name          = '';
    $phone_number       = '';
    $email              = '';
    $address            = '';
    $appointment_date   = '';
    $service            = '';
    $role               = '';
    $staff_id           = '';



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
    $max_len_fields = array('first_name' => 50, 'last_name' => 100, 'phone_number' => 11, 'email' => 100, 'address' => 200);

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

    
    //email validation
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $query = "SELECT * FROM customer WHERE email = '{$email}' LIMIT 1";

    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $errors[] = 'Email address already exists';
        }
    }

    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }


    //Phone Number validation
    $phone_number = mysqli_real_escape_string($connection, $_POST['phone_number']);
    $query = "SELECT * FROM customer WHERE phone_number = '{$phone_number}' LIMIT 1";

    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $errors[] = 'Phone Number already exists';
        }
    }

    if (empty($errors)) {
        $first_name         = mysqli_real_escape_string($connection, $_POST['first_name']);
        $last_name          = mysqli_real_escape_string($connection, $_POST['last_name']);
        $address            = mysqli_real_escape_string($connection, $_POST['address']);
        $service            = mysqli_real_escape_string($connection, $_POST['service']);
       
        //insert data to customer table
        $query = "INSERT INTO customer (first_name, last_name, phone_number, email, address, employee_id, is_deleted) VALUES ('{$first_name}', '{$last_name}', '{$phone_number}', '{$email}', '{$address}', '{$employee_id}', 0 )";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header('Location: onlineCustomers.php');
        }else{
            $errors[] = 'Failed to add the new record ';
        }
        //insert data to appointment table
        $query1 = "INSERT INTO appointment (appointment_date, service, staff_id, role, amount, phone_number, employee_id, is_deleted) VALUES ('{$appointment_date}', '{$service}', '{$staff_id}', '{$role}', '{$amount}', '{$phone_number}', '{$employee_id}', 0 )";
        $result1 = mysqli_query($connection, $query1);

        if ($result1) {
            header('Location: onlineCustomers.php');
        }else{
            $errors[] = 'Failed to add the new record';
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
    <link rel="stylesheet" href="CSS/appointmentStyle14.css">
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
        <!--validation display -->
        <?php
        
            if (!empty($errors)) {
                echo '<div class="errmsg">';
                foreach ($errors as $error) {
                    echo '- ' . $error . '</br>';
                }
                echo '</div>';
            }
        
        ?>

        <button type="button" id='addNew2' name="submit" onclick="window.open('serviceDetails.php','_top');">
            <span class ="button_Edit_text">Service Details</span>
        </button>

        <form action="appointment.php" method="post" class="userform">
            <div class="user-details">

                <div class="box"> 
                    <div class="input_box_first">
                        <div class="firstLeft">
                            <div class="labelColumnFirstLeft">
                                <label>First Name</label>
                            </div>
                            <div class="inputColumnFirstLeft">
                                <input id="inputFirstName" type="text" name="first_name" placeholder="First Name" <?php echo 'value="' . $first_name . '"'; ?>>
                            </div>
                        </div>

                        <div class="firstRight">
                            <div class="labelColumnFirstRight">
                                <label>Last Name</label>
                            </div>
                            <div class="inputColumnFirstRight">
                                <input id="inputLastName" type="text" name="last_name" placeholder="Last Name" <?php echo 'value="' . $last_name . '"'; ?> >
                        
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
                                <input id="inputPhoneNumber" type="text" name="phone_number" placeholder="Phone Number" <?php echo 'value="' . $phone_number . '"'; ?> >         
                            </div>
                        </div>

                        <div class="secondRight">
                            <div class="labelColumnSecondRight">
                                <label>Email</label>
                            </div>
                            <div class="inputColumnSecondRight">                        
                                <input id="inputEmail" type="email" name="email" placeholder="Email" <?php echo 'value="' . $email . '"'; ?>>

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
                                <input id="inputAddress" type="text" name="address" placeholder="Address" <?php echo 'value="' . $address . '"'; ?> >                                                        
                            </div>
                        
                        </div>
                        <div class="thirdRight">
                            <div class="labelColumnThirdRight">
                                <label>Appointment Date</label>
                            </div>
                            <div class="inputColumnThirdRight">
                                <input id="inputAppointmentDate"  type="date" name="appointment_date" placeholder="" <?php echo 'value="' . $appointment_date . '"'; ?> >        
                    
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
                            $query = "SELECT * FROM staff WHERE status='online' AND role_id = '4'";
                            $result2 = mysqli_query($connection, $query);

                            if (mysqli_num_rows($result2) > 0) 
                            {
                        ?>
                                <div class="forthRight">
                                    <div class="labelColumnForthRight">
                                        <label>Stylist</label>
                                    </div>
                                    <div class="inputColumnForthRight">  
                                        <select id="inputRole" name="staff_id" class="inputColumnTwo" required>
                                            <option value="">Select Stylist</option>
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
                                <select id="inputRole" name="role" class="inputColumnTwo" >
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