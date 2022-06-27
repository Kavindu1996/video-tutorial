<?php session_start(); ?>
<?php include 'sidebar.php';?>
<?php 
      //Checking if a valid user is logged in
      if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
        }

    //getting the data from the database
    $today = date("Y-m-d");
    $query = "SELECT appointment_date AS Date, SUM(amount) AS Total FROM appointment GROUP BY appointment_date";
    $result = mysqli_query($connection, $query);
    $chart_data = '';
    while($row = mysqli_fetch_array($result))
    {
    $chart_data .= "{ Date:'".$row["Date"]."', Total:".$row["Total"]."}, ";
    }

?>

<!DOCTYPE html>
<html>
 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Graph</title>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <link rel="stylesheet" href="CSS/dailyIncomeGraphStyle2.css">
  
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
        <h2>Daily Income</h2>       
        <div id="chart"></div>
   </div>
    <script>
        Morris.Bar({
        element : 'chart',
        data:[<?php echo $chart_data; ?>],
        xkey:['Date'],
        ykeys:['Total'],
        labels:['Total'],
        hideHover:'auto',
        stacked:true
        });
    </script>
</body>
</html>
<?php mysqli_close($connection); ?>

