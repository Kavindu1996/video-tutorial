<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
    }

    //getting the service records from the database
    $query = "SELECT * FROM service WHERE is_deleted = 0";
    $serviceType = mysqli_query($connection, $query);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Service</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="CSS/serviceStyle14.css">
</head>
<body>
    <main class="container">
        <div class="sub-section">
            <div class="headding">
                <span class ="button_Next_Text">Service</span>
            </div>
            <button type="button" id="addNew" name="submit" onclick="window.open('addService.php','_top');">
                <span>Add New</span>
            </button>
        </div>


        <div class="body">
            <table class="masterlist">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Service Charge</th>
                        <th>Duration</th>
                        <th>Service Category</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //Data is displayed after successful query execution
                        if ($serviceType) {
                            //The records in the staffMembers is assigned to the service variable as an associative array
                            while ($service = mysqli_fetch_assoc($serviceType)) {

                    ?>
                            <tr>
                            <td data-label="Name"><?php echo $service['name'];?></td>
                            <td data-label="Description"><?php echo $service['description']; ?></td>
                            <td data-label="Service Charge"><?php echo $service['service_charge'];?></td>
                            <td data-label="Duration"><?php echo $service['duration']; ?></td>
                            <td data-label="Service Category"><?php echo $service['service_category'];?></td>
                            <td data-label="option">
                                <!-- edit -->
                                <button type="button" id='addNew2' name="submit" onclick="window.open('modify-Service.php?service_id=<?php echo $service['service_id'];?>','_top');">
                                    <span class ="button_Edit_text">Edit</span>
                                </button>

                                <!-- delete -->
                                <input type="hidden"  class="delete_id_value"  value="<?php echo $service['service_id'];?>">
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
            var deleteid = $(this).closest("tr").find('.delete_id_value').val();
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
                    url : "delete-service.php",
                    data : {
                        "delete_btn_set" : 1,
                        "delete_id" : deleteid,                                  
                    },
                    success : function (response) {
                        swal("Data Deleted Successfuly.!", {
                            icon: "success",
                        }).then((result) => {
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