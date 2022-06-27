<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier_Panel</title>
    <link rel="stylesheet" href="CSS/cashierStyle1.css">
</head>
<body>
<div class="wrapper">
        <!--header-->


        <div class="section">
            <div class="hamburger"><a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
            <div class="top_navbar">
                <div class="left_area"><h3>hair<span>Shine</span></h3></div>
                <div class="right_area">Welcome! <a href="logout.php" class="logout_btn">Logout</a></div>
            </div>
        </div>



        <!--side bar-->
        <div class="sidebar">
            <ul>
                
                <li><a id="1"  href="#" class="active">
                    <span class="icon"><i  class="fas fa-home"></i></span>
                    <span>Dashboard</span>
                </a></li>
                <li><a id="2" href="#">
                    <span class="icon"><i  class="fas fa-home"></i></span>
                    <span>Staff Details</span>
                </a></li>
                <li><a id="3" href="#">
                    <span class="icon"><i  class="fas fa-home"></i></span>
                    <span>Apoinment</span>
                </a></li>
                <li><a id="4" href="#">
                    <span class="icon"><i  class="fas fa-home"></i></span>
                    <span>Customer Details</span>
                </a></li>
                <li><a id="5" href="#">
                    <span class="icon"><i  class="fas fa-home"></i></span>
                    <span>Payment Details</span>
                </a></li>
                <li><a id="6" href="#">
                    <span class="icon"><i  class="fas fa-home"></i></span>
                    <span>Notice</span>
                </a></li>
                <li><a id="7" href="#">
                    <span class="icon"><i  class="fas fa-home"></i></span>
                    <span>About</span>
                </a></li>
                <li><a id="8" href="#">
                    <span class="icon"><i  class="fas fa-home"></i></span>
                    <span>Settings</span>
                </a></li>
            </ul>
        </div>

        <div class="content"></div>

    </div>
    <script src="JS/jquery.js"></script>
    <script>
        $(document).ready(function(){
            $('#1').click(function(){
                $('.content').load('appointment.php');
            });
            $('#2').click(function(){
                $('.content').load('appointment.php');
            });
            $('#3').click(function(){
                $('.content').load('appointment.php');
            });
            $('#4').click(function(){
                $('.content').load('customerDetails.php');
            });
            $('#5').click(function(){
                $('.content').load('appointment.php');
            });
            $('#6').click(function(){
                $('.content').load('appointment.php');
            });
            $('#7').click(function(){
                $('.content').load('appointment.php');
            });
            $('#8').click(function(){
                $('.content').load('appointment.php');
            });

            $('.sidebar ul li a').click(function(){
                $(this).addClass('active').parent().siblings().find('a').removeClass('active');
            });
            
            
            

            // $(".wrapper .sidebar ul li a").on("click", function() {
            // $(".wrapper .sidebar ul li a").find(".active").removeClass("active");
            // $(this).parent().addClass("active");
            // });
        });

        
    </script>
</body>
</html>