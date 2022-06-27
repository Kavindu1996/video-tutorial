<?php session_start(); ?>
<?php require_once('../database/inc/connection.php')?>
<?php include 'sidebar.php';?>
<?php
    //Checking if a valid user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../mainLogin.php');
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Income</title>
    <link rel="stylesheet" href="CSS/dailyIncomeStyle2.css">
    
</head>
<body>
    <main class="container">
        <div class="sub-section">
            <div class="headding">
                <span class ="button_Next_Text">Daily Incomes</span>
                    <form action="" method="GET">
                        <div class="filter">
                            <div class="fromDate">      
                                <input type="date" name="from_date" id="from_date" value ="<?php echo $from_date; ?>" class="form-control" placeholder="From Date" autofocus/>  
                            </div>  
                            <div class="toDate">  
                                <input type="date" name="to_date" id="to_date" value ="<?php echo $to_date; ?>" class="form-control" placeholder="To Date" autofocus/>  
                            </div>  
                            <div class="filterButton">  
                                <button type="submit"  class="button">Filter</button>
                            </div> 
                        </div>
                    </form>

                        <!-- mobileview -->
                    <form action="" method="GET">
                        <div class="mobileview-filter">
                            <div class="fromDate">  
                                <label class="mobileview-label" for="">From:</label>
                                <input type="date" name="from_date" id="from_date" value ="<?php echo $from_date; ?>" class="form-control" placeholder="From Date" autofocus/>  
                            </div>  
                            <div class="toDate">  
                                <label class="mobileview-label" for="">To:</label>
                                <input type="date" name="to_date" id="mobileview-to_date" value ="<?php echo $to_date; ?>" class="form-control" placeholder="To Date" autofocus/>  
                            </div>  
                            <div class="mobileview-filterButton">  
                                <button type="submit"  class="button">Filter</button>
                            </div> 
                        </div>
                    </form>
            </div>
        </div>
       
        <div class="body">
            <table class="masterlist">
                <thead>
                    <tr>
                    <th>Date</th>
                    <th>Total</th>     
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //Displays data related to search dates
                        if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                            $from_date = $_GET['from_date'];
                            $to_date = $_GET['to_date'];

                            $query = "SELECT appointment_date AS Date, SUM(amount) AS Total FROM appointment WHERE appointment_date BETWEEN '$from_date' and '$to_date' GROUP BY appointment_date";
                            $customerMembers = mysqli_query($connection, $query);

                            if ($customerMembers) {
                                while($date = mysqli_fetch_assoc($customerMembers))
                                {
                        ?>
                                <tr>
                                    <td data-label="Date"><?php echo $date['Date']; ?></td>
                                    <td data-label="Total"><?php echo $date['Total']; ?></td>                                        
                                </tr>
                        <?php
                                }
                            }else {
                                echo "";
                            }
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
</script>
</body>
</html>
<?php mysqli_close($connection); ?>
