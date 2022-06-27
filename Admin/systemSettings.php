<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>

<?php

   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
}

    $errors             = array();
    $salon_name         = '';
    $phone_number       = '';
    $email              = '';
    $address            = '';
 

    // getting the salon information
    $query = "SELECT * FROM salon WHERE salon_id = 1 LIMIT 1";
    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        if (mysqli_num_rows($result_set) == 1) {
            $result         = mysqli_fetch_assoc($result_set);
            $salon_name     = $result['salon_name'];
            $phone_number   = $result['phone_number'];
            $address        = $result['address'];
            $email          = $result['email'];
        }else {
            header('Location: settings.php?data_not_found');
        }
    }else {
        header('Location: settings.php?query_failed');
    }

    if (isset($_POST['submit'])) {
        $salon_name     = $_POST['salon_name'];
        $phone_number   = $_POST['phone_number'];
        $address        = $_POST['address']; 
        $email          = $_POST['email'];
        $employee_id   = $_SESSION['admin_id'];


        //checking max length
        $max_len_fields = array('salon_name' => 100, 'phone_number' => 11, 'address' => 200, 'email' => 100);

        foreach ($max_len_fields as $field => $max_len) {
            if (strlen(trim($_POST[$field])) > $max_len){
                $errors[] = $field . ' must be less than ' . $max_len . ' characters';
            }
        }

        //email validation
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $query = "SELECT * FROM salon WHERE email = '{$email}' AND salon_id !=1 LIMIT 1";
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

            if (empty($errors)) {
                $salon_name         = mysqli_real_escape_string($connection, $_POST['salon_name']);
                $phone_number       = mysqli_real_escape_string($connection, $_POST['phone_number']);
                $address            = mysqli_real_escape_string($connection, $_POST['address']);

                $query = "UPDATE salon SET salon_name = '{$salon_name}', phone_number = '{$phone_number}', address = '{$address}', email = '{$email}', employee_id = '{$employee_id}' WHERE salon_id = 1 LIMIT 1";
                $result = mysqli_query($connection, $query);

                    if ($result) {
                        header('Location: settings.php?data_modified');
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
    <title>Salon Settings</title>
    <link rel="stylesheet" href="CSS/settingsStyle22.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">Settings / Salon Settings</div>
            <b>Salon Settings</b>
        </div>
        <div class="mobiletitle">
            <b>Salon Settings</b>
        </div>
        <div class="mobilerouting">Settings / Salon Settings</div>


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
    
        <form action="systemSettings.php" method = "post" class = "userform" enctype = "multipart/form-data">
            <div class="user-details">
                <div class="box"> 
                    <div class="input_box_first">
                        <div class="firstLeft" >
                            <div class="labelColumnFirstLeft">
                                <label>Salon Name</label>
                            </div>
                            <div class="inputColumnFirstLeft" >
                                <input id="inputSalonName" type="text" name="salon_name" placeholder="Salon Name" <?php echo 'value="' . $salon_name . '"'; ?> class ="form-control"  required>              
                            </div>
                        </div>
                        <div class="firstRight">
                            <div class="labelColumnFirstRight">
                                <label>Phone Number</label>
                            </div>
                            <div class="inputColumnFirstRight">
                                <input id="inputPhoneNumber" type="text" name="phone_number" placeholder="Phone Number" <?php echo 'value="' . $phone_number . '"'; ?> required>
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box"> 
                    <div class="input_box_second">
                        <div class="secondLeft">
                            <div class="labelColumnSecondLeft">
                                <label>Address</label>
                            </div>
                            <div class="inputColumnSecondLeft">
                                <input id="inputAddress" type="text" name="address" placeholder="Address" <?php echo 'value="' . $address . '"'; ?> required>  
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

                <div class="button">
                <button id = "submit" type="submit" name="submit" >Submit</button>
                <button type="button" class="back"  onclick="window.open('settings.php','_top');">Back</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection); ?>