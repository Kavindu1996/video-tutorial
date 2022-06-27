<?php require_once('../database/inc/connection.php')?>
<?php
   //checking if a user is logged in
   if (!isset($_SESSION['admin_id'])) {
      header('Location: ../mainLogin.php');
  }
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
      <link rel="stylesheet" href="CSS/sidebarStyle28.css">
   </head>
   <body>
              <!--header-->

         <div class="section">
            <div class="top_navbar">
               <div class="right_area">Welcome <?php echo $_SESSION['admin_name']?>! 
               <form action="../mainLogout.php" method="POST">
                  <button type="submit" name="logout_btn" class="logout_btn" >LogOut</button>
               </form>
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
            <li id="tab-1" onclick="showTab(1);" >
               <a class="active" id="tabcontent-1" href="dashboard.php">Dashboard</a>
            </li>
            <li id="tab-2" onclick="showTab(2);">
               <a id="tabcontent-2" href="staffList.php">Staff Details</a>
            </li>
            <li id="tab-3" onclick="showTab(3);">
               <a id="tabcontent-3" href="service.php">Services</a>
            </li>
            <li id="tab-5">
               <a href="#" class="feat-btn">Stoke Report
               <span class="fas fa-caret-down first"></span>
               </a>
               <ul class="feat-show">
                  <li>
                     <a href="stockReportEquipment.php">Equipment</a>
                  </li>
                  <li>
                     <a href="stockReportMoisturizer.php">Moisturizing</a>
                  </li>
               </ul>
            </li>

            <li id="tab-6">
               <a  class="serv-btn" href="income.php">Today Income</a>
            </li>
            <li id="tab-7">
               <a  class="serv-btn" href="dailyIncome.php">Daily Incomes</a>
            </li>
            <li id="tab-8">
               <a  class="serv-btn" href="dailyIncomeGraph.php">Daily Income Graph</a>
            </li>
            <li id="tab-9">
               <a  class="serv-btn" href="graph.php">Graph</a>
            </li>
            <li id="tab-7">
               <a  class="serv-btn" href="appointment.php">Appointment</a>
            </li>
            <li id="tab-8">
               <a  class="serv-btn" href="onlineCustomers.php">Online Customers</a>
            </li>
            <li id="tab-9">
               <a  class="serv-btn" href="customerDetails.php">Customer Details</a>
            </li>

            <li id="tab-10">
               <a  class="serv-btn" href="notice.php">Notice</a>
            </li>
            <li id="tab-11">
               <a id="tabcontent-7" href="about.php">About</a>
            </li>
            <li id="tab-12">
               <a id="tabcontent-8" href="settings.php">Settings</a>
            </li>

         </ul>
      </nav>


      <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
      <!-- <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script> -->

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
   //  $('.serv-btn').click(function(){
   //    $('nav ul .serv-show').toggleClass("show1");
   //    $('nav ul .second').toggleClass("rotate");
   //  });
   //  $('nav ul li').click(function(){
   //    $(this).addClass("active").siblings().removeClass("active");
   //  });

   //  $('.sidebar ul li a').click(function(){
   //              $(this).addClass('active').parent().siblings().find('a').removeClass('active');
   //          });
 
    </script>
      <script src="JS/sidebar.js"></script>
   </body>
</html>
