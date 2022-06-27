<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
    }

    // getting the salon information from the database
    $query = "SELECT * FROM salon WHERE salon_id = '1'";
    $result_set = mysqli_query($connection, $query);

    if ($result_set) {
        //Data is displayed after successful query execution
        if (mysqli_num_rows($result_set) == 1) {
            $result             = mysqli_fetch_assoc($result_set);
            $salon_name         = $result['salon_name'];
            $salon_phone_number = $result['phone_number'];
            $salon_email        = $result['email'];
            $salon_address      = $result['address'];
        }else {
            header('Location: settings.php?settings_not_found');
        }
    }else {
        header('Location: settings.php?query_failed');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="CSS/settingsStyle23.css">
</head>
<body>
<main class="container">
    <div class="sub-section">
                <span class ="button_Next_Text">Settings</span>
    </div>
    <div class="first">
        <h2>Salon Settings</h2>
        <hr class='mainLine'>
        <table class="table">
            <tr>
                <th>Salon Name  :   </th>
                <td><?php echo '' . $salon_name . ''; ?></td>
            </tr>
            <tr>
                <th>Phone Number :</th>
                <td><?php echo '' . $salon_phone_number . ''; ?></td>
            </tr>
            <tr>
                <th>Email :</th>
                <td><?php echo '' . $salon_email . ''; ?></td>
            </tr>
            <tr>
                <th>Address :</th>
                <td><?php echo '' . $salon_address . ''; ?></td>
            </tr>
        </table>
        <button type="button" id='addNew2' name="submit" onclick="window.open('systemSettings.php','_top');">
        <span class ="button_Edit_text">Edit</span>   
        </button>
    </div>
</main>

</body>
</html>

<?php mysqli_close($connection); ?>