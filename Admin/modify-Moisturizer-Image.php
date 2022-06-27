<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>

<?php
   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }


    $errors        = array();
    $moisturizer_id       = '';
    $moisturizer_image    = '';
 

    if (isset($_GET['moisturizer_id'])) {
        // getting the staff information from the database
        $moisturizer_id = mysqli_real_escape_string($connection, $_GET['moisturizer_id']);
        $query = "SELECT * FROM moisturizer WHERE moisturizer_id = {$moisturizer_id} LIMIT 1";

        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result         = mysqli_fetch_assoc($result_set);
                $moisturizer_image     = $result['moisturizer_image'];
            }else {
                header('Location: modify-stockReportMoisturizer.php?moisturizer_not_found');
            }
        }else {
            header('Location: modify-stockReportMoisturizer.php?query_failed');
        }
    }


    if (isset($_POST['submit'])) {
        $moisturizer_id       = $_POST['moisturizer_id'];
        $moisturizer_image    = $_FILES['moisturizer_image']['name'];
        $moisturizer_image_tmp = $_FILES['moisturizer_image']['tmp_name'];
        $employee_id        = $_SESSION['admin_id'];


        //moisturizer image validation
        $query = "SELECT * FROM moisturizer WHERE moisturizer_image = '{$moisturizer_image}' AND moisturizer_id !={$moisturizer_id} AND is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'moisturizer image already exists';
            }
        }



        //validation image
        $allowed_extention = array('gif', 'png', 'jpg', 'jpeg', '');
        $file_extention = pathinfo($moisturizer_image, PATHINFO_EXTENSION);
        $fileDimension = getimagesize($moisturizer_image_tmp);
        if (isset($file_extention) && ($fileDimension)){
        $img_width = $fileDimension[0];
        $img_height = $fileDimension[1];
        //if(in_array($file_extention, $allowed_extention)){
        //check image size
            if ($_FILES['moisturizer_image']['size']< 1000000) {
                //check image width and height
                if ($img_width <= "2000" && $img_height <= "2500") {

                    if (empty($errors)) {
                        $query = "UPDATE moisturizer SET moisturizer_image = '{$moisturizer_image}', employee_id = '{$employee_id}' WHERE moisturizer_id = {$moisturizer_id} LIMIT 1";
                        $result = mysqli_query($connection, $query);

                        if ($result) {
                        //upload image
                                move_uploaded_file($moisturizer_image_tmp, "../upload/moisturizing/" . $moisturizer_image);
                                header('Location: stockReportMoisturizer.php?moisturizer_modified=true');                                 
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
    <link rel="stylesheet" href="CSS/modify-moisturizer-image-style1.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">Modify Moisturizer / Modify Image</div>
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

        <form action="modify-Moisturizer-Image.php" method="post" class="userform" enctype="multipart/form-data">
        <input type="hidden" name="moisturizer_id" value="<?php echo $moisturizer_id; ?>">

            <div class="imagemodify">
                <input id="inputImage" type="file"  accept ="image/" onchange="loadfile(event)"  name="moisturizer_image"  required>
                <img   id="preimage" width="100px" height="100px" <?php echo 'value="'. $moisturizer_image. '"'; ?>>
            </div>

            <div class="button">
                <button type="submit" class = "submit" name="submit" >Submit</button>
                <button type="button" class="back"  onclick="window.open('stockReportMoisturizer.php','_top');">Back</button>
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