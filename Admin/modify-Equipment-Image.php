<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>

<?php
   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }


    $errors        = array();
    $equipment_id       = '';
    $equipment_image    = '';
 

    if (isset($_GET['equipment_id'])) {
        // getting the staff information from the database
        $equipment_id = mysqli_real_escape_string($connection, $_GET['equipment_id']);
        $query = "SELECT * FROM equipment WHERE equipment_id = {$equipment_id} LIMIT 1";

        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result         = mysqli_fetch_assoc($result_set);
                $equipment_image     = $result['equipment_image'];
            }else {
                header('Location: modify-stockReportEquipment.php?Equipment_not_found');
            }
        }else {
            header('Location: modify-stockReportEquipment.php?query_failed');
        }
    }


    if (isset($_POST['submit'])) {
        $equipment_id       = $_POST['equipment_id'];
        $equipment_image    = $_FILES['equipment_image']['name'];
        $equipment_image_tmp = $_FILES['equipment_image']['tmp_name'];
        $employee_id        = $_SESSION['admin_id'];


        //equipment image validation
        $query = "SELECT * FROM equipment WHERE equipment_image = '{$equipment_image}' AND equipment_id !={$equipment_id} AND is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'equipment image already exists';
            }
        }



        //validation image
        $allowed_extention = array('gif', 'png', 'jpg', 'jpeg', '');
        $file_extention = pathinfo($equipment_image, PATHINFO_EXTENSION);
        $fileDimension = getimagesize($equipment_image_tmp);
        if (isset($file_extention) && ($fileDimension)){
        $img_width = $fileDimension[0];
        $img_height = $fileDimension[1];
        //if(in_array($file_extention, $allowed_extention)){
        //check image size
            if ($_FILES['equipment_image']['size']< 1000000) {
                //check image width and height
                if ($img_width <= "2000" && $img_height <= "2500") {

                    if (empty($errors)) {
                        $query = "UPDATE equipment SET equipment_image = '{$equipment_image}', employee_id = '{$employee_id}' WHERE equipment_id = {$equipment_id} LIMIT 1";
                        $result = mysqli_query($connection, $query);

                        if ($result) {
                        //upload image
                                move_uploaded_file($equipment_image_tmp, "../upload/equipment/" . $equipment_image);
                                header('Location: stockReportEquipment.php?equipment_modified=true');                                 
                        }else{
                            $errors[] = 'Failed to modify the record';
                        }
                    
                }
                }else {
                    $errors[] = 'image width and height not valid';
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
    <title>Modify Image</title>
    <link rel="stylesheet" href="CSS/modify-equipment-image-style1.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">Modify Equipment / Modify Image</div>
            <b>Modify Image</b>
        </div>

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

        <form action="modify-Equipment-Image.php" method="post" class="userform" enctype="multipart/form-data">
        <input type="hidden" name="equipment_id" value="<?php echo $equipment_id; ?>">

            <div class="imagemodify">
                <input id="inputImage" type="file"  accept ="image/" onchange="loadfile(event)"  name="equipment_image"  required>
                <img   id="preimage" width="100px" height="100px" <?php echo 'value="'. $equipment_image. '"'; ?>>
            </div>

            <div class="button">
                <button type="submit" class = "submit" name="submit" >Submit</button>
                <button type="button" class="back"  onclick="window.open('stockReportEquipment.php','_top');">Back</button>
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