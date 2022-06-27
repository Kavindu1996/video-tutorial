<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>
<?php

  
   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }
    
    $errors        = array();
    $user_id       = '';
    $first_name    = '';
    $last_name     = '';
    $phone_number  = '';
    $email         = '';
    $date_of_birth = '';
    $nic           = '';
    $address       = '';
    $gender        = '';
    $password      = '';
    $role_id       = '';
    $stud_image    = '';
 

    if (isset($_GET['user_id'])) {
        // getting the staff information from the database
        $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
        $query = "SELECT * FROM staff WHERE id = {$user_id} LIMIT 1";

        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result         = mysqli_fetch_assoc($result_set);
                $first_name     = $result['first_name'];
                $last_name      = $result['last_name'];
                $phone_number   = $result['phone_number'];
                $email          = $result['email'];
                $date_of_birth  = $result['date_of_birth'];
                $nic            = $result['nic'];
                $address        = $result['address'];
                $gender         = $result['gender'];
                $role_id        = $result['role_id'];
                $stud_image     = $result['stud_image'];
            }else {
                header('Location: staffList.php?user_not_found');
            }
        }else {
            header('Location: staffList.php?query_failed');
        }
    }

    if (isset($_POST['submit'])) {
        $user_id       = $_POST['user_id'];
        $first_name    = $_POST['first_name'];
        $last_name     = $_POST['last_name'];
        $phone_number  = $_POST['phone_number'];
        $email         = $_POST['email'];
        $date_of_birth = $_POST['date_of_birth'];
        $nic           = $_POST['nic'];
        $address       = $_POST['address'];
        $gender        = $_POST['gender']; 
        $role          = $_POST['role_id'];
        $stud_image    = $_FILES['stud_image']['name'];
        $stud_image_tmp = $_FILES['stud_image']['tmp_name'];
        $employee_id   = $_SESSION['admin_id'];



        // check if name only contains letters and whitespace
        //regex-regular expression
        if (!preg_match("/^[a-zA-Z-' ]*$/",$first_name,)) {
            $errors[] = "First Name Only letters allowed";
        }

        if (!preg_match("/^[a-zA-Z-' ]*$/",$last_name,)) {
            $errors[] = "Last Name Only letters allowed";
        }


        //checking max length
        $max_len_fields = array('first_name' => 100, 'last_name' => 100, 'phone_number' => 10, 'email' => 100, 'nic' =>20, 'address' => 200);

        foreach ($max_len_fields as $field => $max_len) {
            if (strlen(trim($_POST[$field])) > $max_len){
                $errors[] = $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        
        //email validation
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $query = "SELECT * FROM staff WHERE email = '{$email}' AND id !={$user_id} AND is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'Email address already exists';
            }
        }

        //https://www.w3schools.com/php/php_form_url_email.asp
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }


        //NIC validation
        $nic = mysqli_real_escape_string($connection, $_POST['nic']);
        $query = "SELECT * FROM staff WHERE nic = '{$nic}' AND id !={$user_id} AND is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'NIC already exists';
            }
        }


        //Phone Number validation
        $phone_number = mysqli_real_escape_string($connection, $_POST['phone_number']);
        $query = "SELECT * FROM staff WHERE phone_number = '{$phone_number}' AND id !={$user_id} AND is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'Phone Number already exists';
            }
        }


        // //staff image validation
        $query = "SELECT * FROM staff WHERE stud_image = '{$stud_image}' AND id !={$user_id} AND is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'staff image already exists';
            }
        }

        //validation image
        $allowed_extention = array('gif', 'png', 'jpg', 'jpeg', '');
        $file_extention = pathinfo($stud_image, PATHINFO_EXTENSION);
        $fileDimension = getimagesize($stud_image_tmp);
        //print_r($fileDimension);
        if (isset($file_extention) && ($fileDimension)){
        $img_width = $fileDimension[0];
        $img_height = $fileDimension[1];
        //check image size
            if ($_FILES['stud_image']['size']< 200000) {
                //check image width and height
                if($img_width <= "1000" && $img_height <= "1500") {
                        if (empty($errors)) {
                            $first_name      = mysqli_real_escape_string($connection, $_POST['first_name']);
                            $last_name       = mysqli_real_escape_string($connection, $_POST['last_name']);
                            $address         = mysqli_real_escape_string($connection, $_POST['address']);
                            $password        = mysqli_real_escape_string($connection, $_POST['password']);
                            $hashed_password = sha1($password);

                            $query = "UPDATE staff SET first_name = '{$first_name}', last_name = '{$last_name}', phone_number = '{$phone_number}', email = '{$email}', date_of_birth = '{$date_of_birth}', nic = '{$nic}', address = '{$address}', gender = '{$gender}', role_id = '{$role}', stud_image = '{$stud_image}', password = '{$hashed_password}', employee_id = '{$employee_id}' WHERE id = {$user_id} LIMIT 1";
                            $result = mysqli_query($connection, $query);

                                    //if query execute correctly
                                    if ($result) {
                                       //upload image
                                             move_uploaded_file($stud_image_tmp, "../upload/staff/" . $stud_image);
                                             header('Location: staffList.php?staff_modified=true');
                                         //}
                                         
                                    }else{
                                        $errors[] = 'Failed to modify the record';
                                    }
                        
                        }
                }
                else {
                    $errors[] = 'image width and height not valid';
                    }
            }
            else {
                $errors[] = 'image size not valid';
            }

        }
        else{
            $errors[] = 'Failed to add image jpeg';
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Modify Staff</title>
    <link rel="stylesheet" href="CSS/modify-staffProfileStyle27.css">
</head>
<body>
    <div class="container">
            <div class="title">
                <div class="routing">Staff Details / Modify Staff Registration</div>
                <b>Modify Staff Registration</b>
            </div>
            <!-- mobileview -->
                <div class="mobiletitle">
                <b>Modify Staff Registration</b>
                </div>
                <div class="mobilerouting">Staff Details / Modify Staff Registration</div>
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

        <form action="modify-staffProfileAddNew.php" method="post" class="userform" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

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
                            </div>
                        </div>
                        <div class="secondRight">
                            <div class="labelColumnSecondRight">
                                <label>Gender</label>
                            </div>
                            <div class="inputColumnSecondRight">                        
                                <div class="role">
                                    <div class="roleNo1">
                                    <!-- <input type="radio" id="role1" name="gender" value="male" checked="<?php echo  $gender == "male" ? "checked" : "" ?>" required> -->
                                        <input type="radio" id="role1" name="gender" value="male" <?php echo 'value="' . $gender . '"'; ?> required>
                                        <label for="role1">Male</label><br>
                                    </div>
                                    <div class="roleNo2">
                                    <!-- <input type="radio" id="role2" name="gender" value="female" checked="<?php echo  $gender == "female" ? "checked" : "" ?>"  required> -->
                                        <input type="radio" id="role2" name="gender" value="female" <?php echo 'value="' . $gender . '"'; ?> required>
                                        <label for="role2">Female</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box">
                    <div class="input_box_third">
                        <div class="thirdLeft">
                            <div class="labelColumnThirdLeft">
                                <label>DOB</label>
                            </div>
                            <div class="inputColumnThirdLeft">
                                <input id="inputDateOfBirth" type="date" name="date_of_birth" placeholder="" <?php echo 'value="' . $date_of_birth . '"'; ?> required>                                                        
                            </div>
                        </div>
                        <div class="thirdRight">
                            <div class="labelColumnThirdRight">
                                <label>NIC</label>
                            </div>
                            <div class="inputColumnThirdRight">
                                <input id="inpuNic" type="text" name="nic" placeholder="NIC" <?php echo 'value="' . $nic . '"'; ?> required>        
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box"> 
                    <div class="input_box_forth">
                        <div class="forthLeft">
                            <div class="labelColumnForthLeft">
                                <label>Address</label>
                            </div>
                            <div class="inputColumnForthLeft">
                                <input id="inputAddress" type="text" name="address" placeholder="Address" <?php echo 'value="' . $address . '"'; ?> required>        
                            </div>
                        </div>
                        <div class="forthRight">
                            <div class="labelColumnForthRight">
                                <label>Email</label>
                            </div>
                            <div class="inputColumnForthRight">
                                <input id="inputEmail" type="email" name="email" placeholder="Email" <?php echo 'value="' . $email . '"'; ?> required>         
                            </div>
                        </div>
                    </div>    
                </div> 

                <div class="box"> 
                    <div class="input_box_fifth">
                        <div class="fifthLeft">
                            <div class="labelColumnFifthLeft">
                                <label>Image</label>
                            </div>
                            <div class="inputColumnFifthLeft">

                                <input id="inputImage" type="file"  accept ="image/" onchange="loadfile(event)"  name="stud_image"  required>
                                <img   id="preimage" width="100px" height="100px" <?php echo 'value="'. $stud_image. '"'; ?>>
                                <!-- <img id="preimage" width="100px" height="100px" src="<?php echo "../upload/staff/".$stud_image. '"'; ?>"> -->
                            </div>
                        </div>

                        <?php
                            $query = "SELECT * FROM role";
                            $result = mysqli_query($connection, $query);

                            if (mysqli_num_rows($result) > 0) 
                            {
                        ?>
                                
                        <div class="fifthRight">
                            <div class="labelColumnFifthRight">
                                <label>Role</label>
                            </div>
                            <div class="inputColumnFifthRight">  
                                <select id="inputRole" name="role_id" class="inputColumnTwo" required>
                                    <option value="">Select Role</option>
                                    <?php
                                    foreach($result as $row)
                                    {
                                    ?>                                              
                                    <option value="<?php echo $row['role_id'];?>"><?php echo $row['role_name'];?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                            }        
                        ?>
                    </div>
                </div> 

                <div class="box"> 
                    <div class="input_box_sixth">
                        <div class="sixthLeft">
                            <div class="labelColumnSixthLeft">
                                <label>Password</label>
                            </div>
                            <div class="inputColumnSixthLeft">
                                <input id="inputpassword" type="password"   name="password" placeholder="password" <?php echo 'value="' . $password . '"'; ?> required>       
                            </div>
                        </div>
                    </div>    
                </div> 

                <div class="box"> 
                    <div class="seventhLeft">
                        <div class="labelColumnSeventhLeft">
                            <label>Show Password</label>
                        </div>
                        <div class="inputColumnSeventhLeft">
                        <input id="showpassword" type="checkbox" onclick="myFunction()"  name="showpassword"  >
                        </div>
                    </div>
                </div>

                <div class="button">
                    <button id = "submit" type="submit" name="submit">update</button>
                    <button type="button" class="back"  onclick="window.open('staffList.php','_top');">Back</button>
                </div>
            </div>
        </form>
    </div>
<script>
    //https://www.w3schools.com/howto/howto_js_toggle_password.asp
    function myFunction() {
        var password = document.getElementById("inputpassword");
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }
    }

    function loadfile(event) {
        var output=document.getElementById('preimage');
        output.src=URL.createObjectURL(event.target.files[0]);
    };

</script>
</body>
</html>
<?php mysqli_close($connection); ?>