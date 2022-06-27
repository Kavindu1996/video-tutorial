<?php session_start(); ?>
<?php include 'navbar.php';?>
<?php require_once('../database/inc/connection.php')?>
<?php
  
   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }

    $errors   = array();
    $notice_title   = '';
    $notice_content = '';
    

    if (isset($_GET['edit_id'])) {
        //getting the staff records from the database
        $edit_id = mysqli_real_escape_string($connection, $_GET['edit_id']);
        $query = "SELECT * FROM notice WHERE id = {$edit_id} LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $result                 = mysqli_fetch_assoc($result_set);
                $notice_title          = $result['notice_title'];
                $notice_content        = $result['notice_content'];
         
            }else {
                header('Location: notice.php?notice_not_found');
            }
        }else {
            header('Location: notice.php?query_failed');
        }
    }


    if (isset($_POST['submit'])) { 
        $edit_id           = $_POST['edit_id'];
        $notice_title      = $_POST['notice_title'];
        $notice_content    = $_POST['notice_content'];
        $employee_id       = $_SESSION['admin_id'];


        //checking max length
        $max_len_fields = array('notice_title' => 100);

        foreach ($max_len_fields as $field => $max_len) {
            if (strlen(trim($_POST[$field])) > $max_len){
                $errors[] = $field . ' must be less than ' . $max_len . ' characters';
            }
        }

        //notice title validation
        $notice_title = mysqli_real_escape_string($connection, $_POST['notice_title']);
        $query = "SELECT * FROM notice WHERE notice_title = '{$notice_title}' AND id !={$edit_id} AND is_deleted = '0' LIMIT 1";
        $result_set = mysqli_query($connection, $query);

        if ($result_set) {
            if (mysqli_num_rows($result_set) == 1) {
                $errors[] = 'notice Title already exists';
            }
        }

            if (empty($errors)) {                
                    $query = "UPDATE notice SET notice_title = '{$notice_title}', notice_content = '{$notice_content}', employee_id = '{$employee_id}' WHERE id = {$edit_id} LIMIT 1";
                    $result = mysqli_query($connection, $query);
                
                    if ($result) {
                       header('Location: notice.php?notice_added=true');

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
    <title>Modify Notice</title>
    <link rel="stylesheet" href="CSS/modify-noticeStyle3.css">
</head>
<body>

    <div class="container">
        <div class="title">
            <div class="routing">Notice / Modify Notice</div>
            <b>Modify Notice</b>
        </div>
        <!-- mobileview -->
        <div class="mobiletitle">
            <b>Staff Information</b>
        </div>
            <div class="mobilerouting">Staff / Staff Information</div>

        <!--validation1 display start-->
        <?php
        
        if (!empty($errors)) {
            echo '<div class="errmsg">';
            foreach ($errors as $error) {
                echo '- ' . $error . '</br>';
            }
            echo '</div>';
        }
            
        ?>  

        <form action="modify-notice2.php" method="post" class="userform" >
            <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">    
            <div class="user-details">
                <div class="input-field">
                    <!-- notice_title -->
                    <label for="title">Enter title</label>
                    <input type="text" name="notice_title" id="title" <?php echo 'value="' . $notice_title . '"'; ?> required>
                </div>
                <!-- notice_content -->
                <div class="text_area">
                <textarea name="notice_content" id="notice_editor" <?php echo 'value="' . $notice_content . '"'; ?>></textarea>
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