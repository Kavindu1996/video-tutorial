<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>
<?php

  
   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }
        $errors = array();
        $moisturizer_name       = '';
        $expired_date           = '';
        $moisturizer_price      = '';
        $moisturizer_image      = '';
    

    if (isset($_POST['submit'])) { 
        $moisturizer_name    = $_POST['moisturizer_name'];
        $expired_date        = $_POST['expired_date'];
        $moisturizer_price   = $_POST['moisturizer_price'];
        $moisturizer_image   = $_FILES['moisturizer_image']['name'];
        $moisturizer_image_tmp = $_FILES['moisturizer_image']['tmp_name'];
        $employee_id         = $_SESSION['admin_id'];
        
        // checking max length
        $max_len_fields = array('moisturizer_name' => 100, 'moisturizer_price' => 100);

        foreach ($max_len_fields as $field => $max_len) {
            if (strlen(trim($_POST[$field])) > $max_len){
                $errors[] = $field . ' must be less than ' . $max_len . ' characters';
            }
        }

        //moisturizer name validation
        $nic = mysqli_real_escape_string($connection, $_POST['moisturizer_name']);
        $query = "SELECT * FROM moisturizer WHERE moisturizer_name = '{$moisturizer_name}' && is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'Moisturizing Name already exists';
            }
        }

        //image validation
        $query = "SELECT * FROM moisturizer WHERE moisturizer_image = '{$moisturizer_image}' AND is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'moisturizer image already exists';
            }
        }
        
        $allowed_extention = array('gif', 'png', 'jpg', 'jpeg');
        $file_extention = pathinfo($moisturizer_image, PATHINFO_EXTENSION);
        $fileDimension = getimagesize($moisturizer_image_tmp);
        if (isset($file_extention) && ($fileDimension)){
        $img_width = $fileDimension[0];
        $img_height = $fileDimension[1];
            //check image size
            if ($_FILES['moisturizer_image']['size']< 200000) {
                    //check image width and height
                    if ($img_width <= "1000" && $img_height <= "1500") {
                            

                            if (empty($errors)) {
                                $moisturizer_price  = mysqli_real_escape_string($connection, $_POST['moisturizer_price']);
                                $query = "INSERT INTO moisturizer (moisturizer_name, expired_date, moisturizer_price, moisturizer_image , employee_id, is_deleted) VALUES ('{$moisturizer_name}', '{$expired_date}', '{$moisturizer_price}', '{$moisturizer_image}', '{$employee_id}',0 )";
                                $result = mysqli_query($connection, $query);
                                //image upload to the folder
                                if ($result) {
                                    move_uploaded_file($moisturizer_image_tmp, "../upload/moisturizing/" . $moisturizer_image);
                                    header('Location: stockReportMoisturizer.php?moisturizer_added=true');
                                }else{
                                    $errors[] = 'Failed to add the new record';
                                }
                            }

                    }else{
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
    <title>Add Moisturizer</title>
    <link rel="stylesheet" href="CSS/moisturizerAddStyle20.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">moisturizing / Add New moisturizing</div>
            <b>Add moisturizing</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Add moisturizing</b>
        </div>
        <div class="mobilerouting">moisturizing / Add New moisturizing</div>

        <!--validation1 display-->
        <?php

            if (!empty($errors)) {
                echo '<div class="errmsg">';
                foreach ($errors as $error) {
                    echo '- ' . $error . '</br>';
                }
                echo '</div>';
            }
            
        ?>
        <form action="moisturizerAdd.php" method="post" class="userform" enctype="multipart/form-data">
            <div class="user-details">
                <div class="box"> 
                    <div class="input_box_first">
                        <div class="firstLeft">
                            <div class="labelColumnFirstLeft">
                                <label>Name</label>
                            </div>
                            <div class="inputColumnFirstLeft">
                                <input id="inputFirstName" type="text" name="moisturizer_name" placeholder="Moisturizer Name" <?php echo 'value="' . $moisturizer_name . '"'; ?> required>                      
                            </div>
                        </div>
                    </div>
                </div> 

                <div class="box"> 
                    <div class="input_box_second">
                        <div class="secondLeft">
                            <div class="labelColumnSecondLeft">
                                <label>Expired Date</label>
                            </div>
                            <div class="inputColumnSecondLeft">
                                <input id="inputExpiredData" type="date" name="expired_date" placeholder="Expired Date" <?php echo 'value="' . $expired_date . '"'; ?> required>                      
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box"> 
                    <div class="input_box_third">
                        <div class="thirdLeft">
                            <div class="labelColumnThirdLeft">
                                <label>Price</label>
                            </div>
                            <div class="inputColumnThirdLeft">
                                <input id="inputPhoneNumber" type="text" name="moisturizer_price" placeholder="Moisturizer Price" <?php echo 'value="' . $moisturizer_price . '"'; ?> required>                      
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
                                <input id="inputImage" type="file" accept="image/" name="moisturizer_image" onchange="loadfile(event)"  <?php echo 'value="' . $moisturizer_image . '"'; ?> required>                      
                                <img  id="preimage" width="100px" height="100px">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="button">
                    <button id = "submit" type="submit" name="submit">Submit</button>
                    <button type="button" class="back"  onclick="window.open('stockReportMoisturizer.php','_top');">Back</button>
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