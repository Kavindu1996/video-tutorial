<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
    }

    //getting the staff records from the database
    $search = '';
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($connection, $_GET['search']);
        $query = "SELECT * FROM moisturizer WHERE (moisturizer_name LIKE '{$search}%') AND is_deleted=0 ORDER BY moisturizer_name";
    }else {
        $query = "SELECT * FROM moisturizer WHERE is_deleted=0 ORDER BY moisturizer_name";
    }
    $moisturizerList = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Moisturizer List</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="CSS/stockMoisturizerStyle17.css">
</head>
<body>
<main class="container">
    <div class="sub-section">
        <div class="headding">
            <span class ="button_Next_Text">Moisturizing</span>
        </div>
         <button type="button" id="addNew" name="submit" onclick="window.open('moisturizerAdd.php','_top');">
            <span>Add New</span>
            </button>

        <div class="search">
            <form action="stockReportMoisturizer.php" method="get">
                <label>Search:</label>
                <input type="text" name="search" class="searchBox" value="<?php echo $search; ?>" autofocus>
            </form>
        </div>
    </div>

    <div class="body">
        <table class="masterlist">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Expired Date</th>
                    <th>Price</th>
                    <th>option</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Data is displayed after successful query execution
                    if ($moisturizerList) {
                        while ($moisturizer = mysqli_fetch_assoc($moisturizerList)) {
                ?>
                    <tr>
                    <td data-label="Image"><img src="<?php echo "../upload/moisturizing/".$moisturizer['moisturizer_image'];?>" width="50px" alt="image"></td>
                    <td data-label="Name"><?php echo $moisturizer['moisturizer_name'];?></td>
                    <td data-label="Expired Date"><?php echo $moisturizer['expired_date']; ?></td>
                    <td data-label="Price"><?php echo 'Rs.'. $moisturizer['moisturizer_price'];?></td>
                    <td data-label="Option">
                        <!-- edit -->
                        <button type="button" id='addNew2' name="submit" onclick="window.open('modify-stockReportMoisturizer.php?moisturizer_id=<?php echo $moisturizer['moisturizer_id'];?>','_top');">
                            <span class ="button_text">Edit</span>   
                        </button>
                        <!-- delete -->
                        <input type="hidden" class = "delete_moisturizer_id_value" value="<?php echo $moisturizer['moisturizer_id'];?>">
                        <!-- <input type="hidden" class="delete_moisturizer_image"  value="<?php echo $moisturizer['moisturizer_image'];?>"> -->
                        <!-- Sometimes, you do not want a link to navigate to another page or reload a page. Using javascript:, you can run code that does not change the current page.
                        This, used with void(0) means, do nothing - don't reload, don't navigate, do not run any code. -->
                        <!-- javascript:void(0) this part is not necessary-->
                        <a href="javascript:void(0)" class="delete_btn_ajax"  id= "addNew3">Delete</a>
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
        //https://www.w3schools.com/cssref/sel_nth-child.asp
        var odd = document.querySelectorAll('tr:nth-child(odd)');
        var even = document.querySelectorAll('tr:nth-child(even)');
        for(var i of odd) {
            i.style.backgroundColor = '#f2f2f2';
        }
        for(var i of even) {
            i.style.backgroundColor = 'white';
        }

    //Alert when deleting data
    $(document).ready(function(){
    $('.delete_btn_ajax').click(function(e){
        e.preventDefault();
        //The code you have there $(this).closest('tr').find('td .uprice').val('hello'); will find the correct row and price cell within it.
        //find the row matching ID
        var deleteid = $(this).closest("tr").find('.delete_moisturizer_id_value').val();
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this Data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: "POST",
                    url : "delete-stockReportMoisturizer1.php",
                    data : {
                        "delete_btn_set" : 1,
                        "delete_moisturizer_id" : deleteid,
                        
                    },
                    success : function (response) {
                        swal("Data Deleted Successfuly.!", {
                            icon: "success",
                        }).then((result) => {
                            //If the location is not set it will not reload
                            location.reload();
                        });
                    }
                });
            } 
            });
        });
    });
</script>
</body>
</html>
<?php mysqli_close($connection); ?>