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
    $stud_image    = '';
 

    if (isset($_GET['user_id'])) {
        // getting the staff information from the database
        $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
        $query = "SELECT * FROM staff WHERE id = {$user_id} LIMIT 1";

        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result         = mysqli_fetch_assoc($result_set);
                $stud_image     = $result['stud_image'];
            }else {
                header('Location: modify-staffProfileAddNew.php?user_not_found');
            }
        }else {
            header('Location: modify-staffProfileAddNew.php?query_failed');
        }
    }


    if (isset($_POST['submit'])) {
        $user_id       = $_POST['user_id'];
        $stud_image    = $_FILES['stud_image']['name'];
        $stud_image_tmp = $_FILES['stud_image']['tmp_name'];
        $employee_id   = $_SESSION['admin_id'];


        //staff image validation
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
        //if (isset($file_extention) && ($fileDimension)) {
        if (isset($file_extention) && ($fileDimension)){
        $img_width = $fileDimension[0];
        $img_height = $fileDimension[1];
        //if(in_array($file_extention, $allowed_extention))
        //check image size
            if ($_FILES['stud_image']['size']< 1000000) {
                //check image width and height
                if ($img_width <= "2000" && $img_height <= "2500") {

                    if (empty($errors)) {
                        $query = "UPDATE staff SET stud_image = '{$stud_image}', employee_id = '{$employee_id}' WHERE id = {$user_id} LIMIT 1";
                        $result = mysqli_query($connection, $query);

                        if ($result) {
                        //upload image
                                move_uploaded_file($stud_image_tmp, "../upload/staff/" . $stud_image);
                                header('Location: staffList.php?staff_modified=true');                                 
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
// }else{
//     $errors[] = 'Failed to modify the record2';
// }


} 
    
    



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Image</title>
    <link rel="stylesheet" href="CSS/modify-image-style1.css">
</head>
<body>
    <div class="container">
        <div class="title">
            <div class="routing">Modify Staff Registration / Modify Image</div>
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

        <form action="modifyimage.php?pp" method="post" class="userform" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

            <div class="imagemodify">
                <input id="inputImage" type="file"  accept ="image/" onchange="loadfile(event)"  name="stud_image"  required>
                <img   id="preimage" width="100px" height="100px" <?php echo 'value="'. $stud_image. '"'; ?>>
            </div>

            <div class="button">
                <button type="submit" class = "submit" name="submit" >Submit</button>
                <button type="button" class="back"  onclick="window.open('staffList.php','_top');">Back</button>
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