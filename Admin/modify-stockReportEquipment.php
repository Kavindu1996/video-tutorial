<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>
<?php

  
   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }


    $errors = array();
    $equipment_id    = '';
    $equipment_name  = '';
    $warranty        = '';
    $equipment_price = '';
    $equipment_image = '';

    if (isset($_GET['equipment_id'])) {
        // getting the equipment information from the database
        $equipment_id = mysqli_real_escape_string($connection, $_GET['equipment_id']);
        $query = "SELECT * FROM equipment WHERE equipment_id = {$equipment_id} LIMIT 1";

        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result             = mysqli_fetch_assoc($result_set);
                $equipment_name     = $result['equipment_name'];
                $warranty           = $result['warranty'];
                $equipment_price    = $result['equipment_price'];
                $equipment_image    = $result['equipment_image'];
            }else {
                header('Location: stockReportEquipment.php?Equipment_not_found');
            }
        }else {
            header('Location: stockReportEquipment.php?query_failed');
        }
    }

    if (isset($_POST['submit'])) {
        $equipment_id       = $_POST['equipment_id'];
        $equipment_name     = $_POST['equipment_name'];
        $warranty           = $_POST['warranty'];
        $equipment_price    = $_POST['equipment_price'];
        $equipment_image    = $_FILES['equipment_image']['name'];
        $equipment_image_tmp = $_FILES['equipment_image']['tmp_name'];
        $employee_id        = $_SESSION['admin_id'];

        //checking max length
        $max_len_fields = array('equipment_name' => 100, 'equipment_price' => 100);

        foreach ($max_len_fields as $field => $max_len) {
            if (strlen(trim($_POST[$field])) > $max_len){
                $errors[] = $field . ' must be less than ' . $max_len . ' characters';
            }
        }

            //equipment name validation
            $query = "SELECT * FROM equipment WHERE equipment_name = '{$equipment_name}' AND equipment_id !={$equipment_id} AND is_deleted = '0' LIMIT 1";
            $result_set = mysqli_query($connection, $query);
    
            if ($result_set) {
                if (mysqli_num_rows($result_set) == 1) {
                    $errors[] = 'Equipment Name already exists';
                }
            }

            // check if name only contains letters and whitespace
            //regex-regular expression
            if (!preg_match("/^[a-zA-Z-' ]*$/",$equipment_name,)) {
                $errors[] = "Name Only letters allowed";
            }

            //image validation
            $query = "SELECT * FROM equipment WHERE equipment_image = '{$equipment_image}' AND equipment_id !={$equipment_id} AND is_deleted = '0' LIMIT 1";
            $result_set = mysqli_query($connection, $query);

            if ($result_set) {
                if (mysqli_num_rows($result_set) == 1) {
                    $errors[] = 'Equipment image already exists';
                }
            }
            

            $allowed_extention = array('gif', 'png', 'jpg', 'jpeg', '');
            $file_extention = pathinfo($equipment_image, PATHINFO_EXTENSION);
            $fileDimension = getimagesize($equipment_image_tmp);
            if (isset($file_extention) && ($fileDimension)){
            $img_width = $fileDimension[0];
            $img_height = $fileDimension[1];

                //check image size
                if ($_FILES['equipment_image']['size']< 100000) {
                    //check image width and height
                        if ($img_width <= "650" && $img_height <= "700") {
                        

                            //if query execute correctly
                            if (empty($errors)) {
                                $equipment_price= mysqli_real_escape_string($connection, $_POST['equipment_price']);
                                $query = "UPDATE equipment SET  equipment_name = '{$equipment_name}', warranty = '{$warranty}', equipment_price = '{$equipment_price}', equipment_image = '{$equipment_image}', employee_id = '{$employee_id}' WHERE equipment_id = {$equipment_id} LIMIT 1";
                                $result = mysqli_query($connection, $query);

                                    if ($result) {
                                            move_uploaded_file($equipment_image_tmp, "../upload/equipment/" . $equipment_image);                                        
                                            header('Location: stockReportEquipment.php?equipment_modified=true');
                                    }else{
                                        $errors[] = 'Failed to modify the record';
                                    }
                            }

                    }else {
                        $errors[] = 'image height & width not valid';
                        }
                }else {
                    $errors[] = 'image size not valid';
                    }
           }else{
                $errors[] = 'Failed to add image jpeg';
           }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Equipment</title>
    <link rel="stylesheet" href="CSS/modify-stockReportEquipment21.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">Equipment / Modify Equipment</div>
            <b>Modify Equipment</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Modify Equipment</b>
        </div>
        <div class="mobilerouting">Equipment / Modify Equipment</div>

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
        <form action="modify-stockReportEquipment.php" method="post" class="userform" enctype="multipart/form-data">
        <input type="hidden" name="equipment_id" value="<?php echo $equipment_id; ?>">    
            <div class="user-details">
                <div class="box"> 
                    <div class="input_box_first">
                        <div class="firstLeft">
                            <div class="labelColumnFirstLeft">
                                <label>Equipment Name</label>
                            </div>
                            <div class="inputColumnFirstLeft">
                                <input id="inputFirstName" type="text" name="equipment_name" placeholder="Equipment Name" <?php echo 'value="' . $equipment_name . '"'; ?> required>                      
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box"> 
                    <div class="input_box_second">
                        <div class="secondLeft">
                            <div class="labelColumnSecondLeft">
                                <label>warranty Date</label>
                            </div>
                            <div class="inputColumnSecondLeft">
                                <input id="inputWarrantyDate" type="date" name="warranty" placeholder="warranty Data" <?php echo 'value="' . $warranty . '"'; ?> required>                      
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="box"> 
                    <div class="input_box_third">
                        <div class="thirdLeft">
                            <div class="labelColumnThirdLeft">
                                <label>Equipment Price</label>
                            </div>
                            <div class="inputColumnThirdLeft">
                                <input id="inputPhoneNumber" type="text" name="equipment_price" placeholder="Equipment Price" <?php echo 'value="' . $equipment_price . '"'; ?> required>                      
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box"> 
                    <div class="input_box_forth">
                        <div class="forthLeft">
                            <div class="labelColumnForthLeft">
                                <label>Image</label>
                            </div>
                            <div class="inputColumnForthLeft">
                                <input id="inputImage" type="file"  accept ="image/" onchange="loadfile(event)"  name="equipment_image"  >
                                <img   id="preimage" width="100px" height="100px" <?php echo 'value="'. $equipment_image. '"'; ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="button">
                    <button id = "submit" type="submit" name="submit">Update</button>
                    <button type="button" class="back"  onclick="window.open('stockReportEquipment.php','_top');">Back</button>
                </div>
            </div>
        </form> 
    </div>
<script>
    function loadfile(event) {
        var output=document.getElementById('preimage');
        output.src=URL.createObjectURL(event.target.files[0]);
    };
</script>
</body>
</html>
<?php mysqli_close($connection); ?>