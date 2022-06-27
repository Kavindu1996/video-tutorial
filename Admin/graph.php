<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php 
      //Checking if a valid user is logged in
      if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
        }

    //getting the data from the database
    $today = date("Y-m-d");
    $query = "SELECT COUNT(appointment.appointment_id) AS customer_count, SUM(appointment.amount) AS total, staff.first_name As staff_name FROM appointment INNER JOIN staff ON appointment.staff_id = staff.id WHERE appointment_date = '$today' GROUP BY staff_id;";
    $result = mysqli_query($connection, $query);
    $chart_data = '';
    while($row = mysqli_fetch_array($result))
    {
    $chart_data .= "{ staff_name:'".$row["staff_name"]."', customer_count:".$row["customer_count"]."}, ";
    }

?>

<!DOCTYPE html>
<html>
 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graph</title>
    <link rel="stylesheet" href="CSS/graphStyle2.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
  
 </head>
 <body>
    <main class="container">
        <div class="sub-section">
            <div class="headding">
                <span class ="button_Next_Text">Graph</span>
                <div class="today">
                    <?php
                        echo "Date: $today"  ;
                    ?>
                </div>
            </div>
        </div>
    </main>

    <!-- chart -->
    <div class="chart">
        <h2>Today's Customers per staff Member</h2>
        <div id="chart"></div>
   </div>

<script>
    Morris.Bar({
    element : 'chart',
    data:[<?php echo $chart_data; ?>],
    xkey:['staff_name'],
    ykeys:['customer_count'],
    labels:['customer_count'],
    hideHover:'auto',
    stacked:true
    });
</script>
 </body>
</html>
<?php mysqli_close($connection); ?>