<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'cashierSidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['cashier_id'])) {
        header('Location: ../mainLogin.php');
    }


    //getting the staff records from the database
        $status = '';
        $search = '';
        $role_id = '';
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($connection, $_GET['search']);
        $query = "SELECT * FROM staff INNER JOIN role ON staff.role_id = role.role_id WHERE (first_name LIKE '{$search}%' AND is_deleted = 0 AND role.role_name != 'admin') ORDER BY id;";
    }else {
        $query = "SELECT * FROM staff INNER JOIN role ON staff.role_id = role.role_id WHERE is_deleted = 0 AND role.role_name != 'admin' ORDER BY id;";
    }
    $staffMembers = mysqli_query($connection, $query);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Staff Members</title>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <link rel="stylesheet" href="CSS/staffDetailsStyle19.css">
</head>
<body>
<main class="container">
    <div class="sub-section">
        <div class="headding">
            <span class ="button_Next_Text">Staff Members</span>

            <!-- search option -->
            <div class="search">
                <form action="staffDetails.php" method="get">
                    <label>Search:</label>
                    <input type="text" name="search" class="searchBox" value="<?php echo $search; ?>" autofocus>
                </form>
            </div>

        <!-- mobileview search-->
            <div class="mobileview-search">
                <form action="staffDetails.php" method="get">
                    <label>Search:</label>
                    <input type="text" name="search" class="searchBox" value="<?php echo $search; ?>" autofocus>
                </form>
            </div>
        </div>
    </div>

    <div class="body">
        <table class="masterlist">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>view profile</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Data is displayed after successful query execution
                    if ($staffMembers) {
                        while ($staff = mysqli_fetch_assoc($staffMembers)) {
                ?>
                            <tr>
                                <td data-label="Image"><img src="<?php echo "../upload/staff/".$staff['stud_image'];?>" width="40px" alt="image"></td>
                                <td data-label="First Name"><?php echo $staff['first_name'];?></td>
                                <td data-label="Last Name"><?php echo $staff['last_name']; ?></td>
                                <td data-label="Role"><?php echo $staff['role_name'];?></td>
                                <td data-label="view profile">
                                    <!-- view profile -->
                                    <button type="button" id='addNew1' name="submit" onclick="window.open('staffProfileViewNew2.php?user_id=<?php echo $staff['id'];?>','_top');">
                                    <span>View Profile</span>
                                    </button>
                                </td>
                                <td data-label="Status"><span id = "status"><?php echo $staff['status']; ?></span></td>
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