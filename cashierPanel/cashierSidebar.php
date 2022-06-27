<?php require_once('../database/inc/connection.php')?>
<?php
   //checking if a user is logged in
   

?>

<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
      <!-- <title>Sidebar</title> -->
      <link rel="stylesheet" href="style.css">
      <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      <link rel="stylesheet" href="CSS/cashierSidebarStyle22.css">
   </head>
   <body>
              <!--header-->

         <div class="section">
            <div class="top_navbar">
               <div class="right_area">Welcome <?php echo $_SESSION['cashier_first_name']?>! 
                  <button type="button" name="logout_btn" class="logout_btn"  onclick="window.open('../mainLogout.php','_top');">
                  <span>Logout</span>
                  </button>
               </div>
            </div>
         </div>



      <div class="btn">
         <span class="fas fa-bars"></span>
      </div>

      <nav class="sidebar">
      <div class="btn2">
         <span class="fas fa-bars"></span>
      </div>
         <?php
         
         $query = "SELECT salon_name FROM salon WHERE salon_id = 1";
         $result_set = mysqli_query($connection, $query);
         foreach ($result_set as $row) {
         ?>
         <div class="left_area"><h3><?php echo $row['salon_name']?></h3></div>
         <?php
         }
         ?>
         <ul>
            <!-- <li id="tab-1" onclick="showTab(1);" class="active"> -->
            <li id="tab-1" onclick="showTab(1);">
               <a class="active" id="tabcontent-1" href="search Details.php">Apoinment</a>
            </li>
            <li id="tab-1" onclick="showTab(1);">
               <a class="active" id="tabcontent-1" href="onlineCustomers.php">Online Customers</a>
            </li>
            <li id="tab-2" onclick="showTab(3);">
               <a id="tabcontent-3" href="customerDetails.php">Customer Details</a>
            </li>
            <li id="tab-3" onclick="showTab(2);">
               <a id="tabcontent-2" href="staffDetails.php">Staff Details</a>
            </li>
            <li id="tab-6">
               <a  class="serv-btn" href="notice.php">Notice</a>
            </li>
            <li id="tab-7">
               <a id="tabcontent-7" href="about.php">About</a>
            </li>
         </ul>

         </ul>
      </nav>

    <script>
       $('.btn').click(function(){
    $(this).toggleClass("click");
    $('.sidebar').toggleClass("hide");
  });

   $('.btn2').click(function(){
      $(this).toggleClass("click");
      $('.sidebar').toggleClass("show");
   });


    $('.feat-btn').click(function(){
      $('nav ul .feat-show').toggleClass("show");
      $('nav ul .first').toggleClass("rotate");
    });

 
    </script>
      <script src="../owner/JS/sidebar.js"></script>
   </body>
</html>
