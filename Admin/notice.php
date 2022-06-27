<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
    }

    ///Checking if a valid user is logged in
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="CSS/noticeStyle23.css">
</head>
<body>
<main class="container">
    <div class="sub-section">
        <div class="headding">
            <span class ="button_Next_Text">Notice</span>
            <button type="button" id="addNew" name="submit_data" onclick="window.open('AddNewNotice.php','_top');">
                <span>Add New Notice</span>
            </button>
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
                            //strtotime — Parse about any English textual datetime description into a Unix timestamp
                            $date = date('d M Y', strtotime($timestamp));
                            $time = date('h:i A', strtotime($timestamp));
                ?>

                        <tr>
                            <td><?php echo $notice['id'];?></td>
                            <td>
                                <b><a href="details.php?notice_id=<?php echo $notice['id'];?>"><?php echo $notice['notice_title'];?></a></b>
                                <div class="date">
                                    <span>Published on: <?php echo $date; ?> At: <?php echo $time; ?></span>
                                </div>
                            </td>
                            <td>
                                <!-- edit -->
                                <button type="button" id='addNew2' name="submit" onclick="window.open('modify-notice2.php?edit_id=<?php echo $notice['id'];?>','_top');">
                                <span class ="button_Edit_text">Edit</span>
                                </button>
                                <!-- delete -->
                                <input type="hidden"  class="delete_id_value"  value="<?php echo $notice['id'];?>">
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
                        url : "delete-notice.php",
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
