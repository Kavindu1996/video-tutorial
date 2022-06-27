<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php

   //Checking if a valid user is logged in
   if (!isset($_SESSION['admin_id'])) {
    header('Location: ../mainLogin.php');
    }

    $date = '';

   //getting the customer records from the database
    if (isset($_GET['date'])) {
        $date = mysqli_real_escape_string($connection, $_GET['date']);
        $query = "SELECT * FROM appointment INNER JOIN customer ON appointment.phone_number = customer.phone_number WHERE (appointment_date LIKE '%{$date}%') AND appointment.is_deleted = '0' ORDER BY appointment_date";
    }else {
        $query = "SELECT * FROM appointment INNER JOIN customer ON appointment.phone_number = customer.phone_number WHERE appointment.is_deleted = '0' ORDER BY appointment_date;";
    }
    $customerMembers = mysqli_query($connection, $query);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Customer Details</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="CSS/customerDetailsStyle2.css">
    
</head>
<body>
<main class="container">
    <div class="sub-section">
        <div class="headding">
            <span class ="button_Next_Text">Customer Details</span>
        </div>

        <!-- Data related to the user selection date is displayed -->
        <form action="" method="GET">
            <div class="filter">
                <div class="fromDate">      
                    <input type="date" name="date"  class="form-control"  value="<?php echo $date; ?>" autofocus/>  
                </div>  
                <div class="filterButton">  
                    <button type="submit"  class="button">Filter</button>
                </div> 
            </div>
        </form>
    </div>
   
    <div class="body">            
        <table class="masterlist">
            <thead>
                <tr>
                <th>Invoice No</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Appointment Date</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Amount</th>
                <th>Service</th>
                <th>Bill</th>
                <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //Data is displayed after successful query execution
                    if ($customerMembers) {
                        while ($customer = mysqli_fetch_assoc($customerMembers)) {
                ?>
                            <tr>
                                <td data-label="Id"><?php echo "H".$customer['appointment_id'];?></td>
                                <td data-label="First Name"><?php echo $customer['first_name'];?></td>
                                <td data-label="Last Name"><?php echo $customer['last_name']; ?></td>
                                <td data-label="Appointment Date"><?php echo $customer['appointment_date']; ?></td>
                                <td data-label="Phone Number"><?php echo $customer['phone_number']; ?></td>
                                <td data-label="Email"><?php echo $customer['email']; ?></td>
                                <td data-label="Address"><?php echo $customer['address']; ?></td>
                                <td data-label="Amount"><?php echo $customer['amount']; ?></td>
                                <td data-label="Service"><?php echo $customer['service']; ?></td>
                                <!-- view BILL -->
                                <td  data-label="Bill">
                                <button type="button" id='addNew1' name="submit" onclick="window.open('bill.php?user_id=<?php echo $customer['appointment_id'];?>','_top');">
                                    <span>Bill</span>
                                </button>
                                </td>
                                <!-- delete -->
                                <td data-label="Delete">
                                <input type="hidden"  class="delete_id_value"  value="<?php echo $customer['appointment_id'];?>">
                                <!-- Sometimes, you do not want a link to navigate to another page or reload a page. Using javascript:, you can run code that does not change the current page.
                                This, used with void(0) means, do nothing - don't reload, don't navigate, do not run any code. -->
                                <!-- javascript:void(0) this part is not necessary-->
                                <a href="javascript:void(0)" class="delete_btn_ajax"  id= "addNew3">Delete</a>
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
                        url : "deleteCustomerDetails.php",
                        data : {
                            "delete_btn_set" : 1,
                            "delete_id" : deleteid,              
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