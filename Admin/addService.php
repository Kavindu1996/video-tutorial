<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>

<?php
  
   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }

    $errors             = array();
    $name               = '';
    $description        = '';
    $service_charge     = '';
    $duration           = '';
    $service_category   = '';
    

    if (isset($_POST['submit'])) { 

        $name               = $_POST['name'];
        $description        = $_POST['description'];
        $service_charge     = $_POST['service_charge'];
        $duration           = $_POST['duration'];
        $service_category   = $_POST['service_category'];
        $employee_id        = $_SESSION['admin_id'];

    
    
        //checking max length
        $max_len_fields = array('name' => 50, 'description' => 100, 'service_charge' => 100);

        foreach ($max_len_fields as $field => $max_len) {
            if (strlen(trim($_POST[$field])) > $max_len){
                $errors[] = $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        
        //validation service name
        $email = mysqli_real_escape_string($connection, $_POST['name']);
        $query = "SELECT * FROM service WHERE name = '{$name}' && is_deleted = '0'  LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'Service Name is already exists';
            }
        }

        if (empty($errors)) {
            $description        = mysqli_real_escape_string($connection, $_POST['description']);
            $service_charge     = mysqli_real_escape_string($connection, $_POST['service_charge']);
            $duration           = mysqli_real_escape_string($connection, $_POST['duration ']);

            $query = "INSERT INTO service (name, description, service_charge, duration, service_category, employee_id) VALUES ('{$name}', '{$description}', '{$service_charge}', '{$duration}', '{$service_category}', '{$employee_id}' )";
            $result = mysqli_query($connection, $query);
        

            if ($result) {
                header('Location: service.php?service_added=true');

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <link rel="stylesheet" href="CSS/addServiceStyle7.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">Service / Add New Service</div>
            <b>Add New Services</b>
        </div>

        <!--validation1 display -->
        <?php
        
        if (!empty($errors)) {
            echo '<div class="errmsg">';
            foreach ($errors as $error) {
                echo '- ' . $error . '</br>';
            }
            echo '</div>';
        }
            
        ?>  

        <form action="addService.php" method="post" class="userform" >
            <div class="user-details">
                <div class="box"> 
                    <div class="input_box_first">
                        <div class="firstLeft">
                            <div class="labelColumnFirstLeft">
                                <label>Name</label>
                            </div>
                            <div class="inputColumnFirstLeft">
                                <input id="inputFirstName" type="text" name="name" placeholder="Name" <?php echo 'value="' . $name . '"'; ?> required>
                            </div>
                        </div>
                        <div class="firstRight">
                            <div class="labelColumnFirstRight">
                                <label>Description</label>
                            </div>
                            <div class="inputColumnFirstRight">
                                <input id="inputLastName" type="text" name="description" placeholder="Description" <?php echo 'value="' . $description . '"'; ?> required>
                            </div>
                        </div>
                    </div>
                </div> 


                <div class="box"> 
                    <div class="input_box_second">
                        <div class="secondLeft">
                            <div class="labelColumnSecondLeft">
                                <label>Service Charge</label>
                            </div>
                            <div class="inputColumnSecondLeft">
                                <input id="inputPhoneNumber" type="text" name="service_charge" placeholder="Service Charge" <?php echo 'value="' . $service_charge . '"'; ?> required>  
                            </div>
                        </div>

                        <div class="secondRight">
                            <div class="labelColumnSecondRight">
                                <label>Duration</label>
                            </div>
                            <div class="inputColumnSecondRight">
                                <input id="inputLastName" type="text" name="duration" placeholder="Duration" <?php echo 'value="' . $duration . '"'; ?> required>                         
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box">
                    <div class="input_box_third">
                        <div class="thirdLeft">
                            <div class="labelColumnThirdLeft">
                                <label>Service Category</label>
                            </div>
                            <div class="inputColumnThirdLeft">
                            <select id="inputCategory" name="service_category" required>
                                    <option value=""> Select Category</option>
                                    <option value="hair">Hair</option>
                                    <option value="body">Body</option>
                                </select>     
                            </div>   
                        </div>
                    </div>
                </div>


                <div class="button">
                    <button id = "submit" type="submit" name="submit">Submit</button>
                    <button type="button" class="back"  onclick="window.open('service.php','_top');">Back</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection); ?>