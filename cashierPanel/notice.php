<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'cashierSidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['cashier_id'])) {
        header('Location: ../mainLogin.php');
    }

    //getting user information from the database
    $query = "SELECT * FROM notice WHERE is_deleted = 0 ORDER BY id DESC";
    $result_set = mysqli_query($connection, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Notice</title>
    <link rel="stylesheet" href="CSS/noticeStyle4.css">
</head>
<body>
<main class="container">
    <div class="sub-section">
        <div class="headding">
            <span class ="button_Next_Text">Notice</span>
        </div>
    </div>

    <div class="body">
        <table class="masterlist">
            <tbody>
                <?php
                    //Data is displayed after successful query execution
                    if ($result_set) {
                        while ($notice = mysqli_fetch_assoc($result_set)) {
                            $timestamp = $notice['Date_published'];
                            $date = date('d M Y', strtotime($timestamp));
                            $time = date('h:i A', strtotime($timestamp));
                            ?>
                            <tr>
                                <td><?php echo $notice['id'];?></td>
                                <td>
                                    <b><a  href="details.php?notice_id=<?php echo $notice['id'];?>" id = "notifications"><?php echo $notice['notice_title'];?></a></b>
                                    <div class="date">
                                        <span>Published on: <?php echo $date; ?> At: <?php echo $time; ?></span>
                                    </div>
                                </td>
                            </tr>
                        <?php
                                
                            }
                        }else {
                        ?>

                            <tr>
                                <td>echo "Database query failed.";</td>
                            </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    // table row style
    var odd = document.querySelectorAll('tr:nth-child(odd)');
    var even = document.querySelectorAll('tr:nth-child(even)');
    for(var i of odd) {
        i.style.backgroundColor = '#f2f2f2';
    }

    for(var i of even) {
        i.style.backgroundColor = 'white';
    }

</script> 
</body>
</html>
<?php mysqli_close($connection); ?>

