<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>

<?php
  
   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }

    $errors = array();
    $notice_title      = '';
    $notice_content      = '';

    if (isset($_POST['submit'])){
        $notice_title = $_POST['notice_title'];
        $notice_content = $_POST['notice_content'];
        $employee_id   = $_SESSION['admin_id'];

        //checking max length
        $max_len_fields = array('notice_title' => 100);

        foreach ($max_len_fields as $field => $max_len) {
            if (strlen(trim($_POST[$field])) > $max_len){
                $errors[] = $field . ' must be less than ' . $max_len . ' characters';
            }
        }

        //notice title validation
        $notice_title = mysqli_real_escape_string($connection, $_POST['notice_title']);
        $query = "SELECT * FROM notice WHERE notice_title = '{$notice_title}' && is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'notice Title already exists';
            }
        }
        


        if (empty($errors)) {
            $query = "INSERT INTO notice(notice_title, notice_content, employee_id) VALUES('$notice_title', '$notice_content', '$employee_id');";
            $result = mysqli_query($connection, $query);

            if ($result) {
                header('Location: notice.php?notice_added');;
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
    <title>New Notice</title>
    <link rel="stylesheet" href="CSS/AddNewNotice9.css">
</head>
<body>

    <div class="container">
        <div class="title">
            <div class="routing">Notice / Add New Notice</div>
            <b>Add New Notice</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
        <b>Add New Notice</b>
        </div>
        <div class="mobilerouting">Notice / Add New Notice</div>

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

        <form action="AddNewNotice.php" method="post" class="userform">     
            <div class="user-details">
                <!-- title area -->
                <div class="input-field">
                    <label for="title">Enter title</label>
                    <input type="text" name="notice_title" id="title" <?php echo 'value="' . $notice_title . '"'; ?> required>
                </div>
                <!-- content area -->
                <div class="text_area">
                <textarea name="notice_content" id="notice_editor" <?php echo 'value="' . $notice_title . '"'; ?> ></textarea>
                </div>
            
                <div class="button">
                    <button id = "submit" type="submit" name="submit" >publish</button>
                    <button type="button" class="back"  onclick="window.open('notice.php','_top');">Back</button>
                </div>
            </div>      
        </form>
    </div>
    <script src="ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('notice_editor');
    </script>
</body>
</html>
<?php mysqli_close($connection); ?>