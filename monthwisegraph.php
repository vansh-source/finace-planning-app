<?php
session_start();
error_reporting(0);

$conn = mysqli_connect("localhost", "root", "", "dhfm");
if ($conn) {
    // echo "Connected";
}

try {
    $currentUserId = $_SESSION['detsuid'];

    $query = "SELECT DATE_FORMAT(e.date, '%Y-%m') AS expense_month, SUM(e.amount) AS total_amount
              FROM expense_master e
              INNER JOIN category_master c ON c.cate_id = e.cate_id
              WHERE e.user_id = ?
              GROUP BY DATE_FORMAT(e.date, '%Y-%m')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $expenseMonth = $row['expense_month'];
        $amount = (float) $row['total_amount'];
        $data[] = array(
            'expense_month' => $expenseMonth,
            'amount' => $amount
        );
    }

    $stmt->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finance planning || Month Wise Graph</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/datepicker3.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="canvas2image.js"></script>

    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Amount');

            <?php
            foreach ($data as $row) {
                $expenseMonth = $row['expense_month'];
                $amount = (float) $row['amount'];
                echo "data.addRow(['$expenseMonth', $amount]);";
            }
            ?>

            var options = {
                title: 'Monthly Expense Report',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('linechart'));
            chart.draw(data, options);
        }

        function saveChartAsImage() {
            var chart = document.getElementById('linechart');
            var options = {
                backgroundColor: 'white', // Set the background color of the chart to white
            };

            html2canvas(chart, options).then(function(canvas) {
                var dataURL = canvas.toDataURL('image/png');
                var link = document.createElement('a');
                link.href = dataURL;
                link.download = 'chart.png';
                link.click();
            });
        }

        function getMonthName(month) {
            var monthNames = [
                "January", "February", "March", "April", "May", "June", "July",
                "August", "September", "October", "November", "December"
            ];

            return monthNames[month - 1];
        }
    </script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        #linechart {
            margin-left: 90px;
            margin-top: 45px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="col-md-3">
            <?php include_once('includes/header.php'); ?>
            <?php include_once('includes/sidebar.php'); ?>
        </div>
        <div class="row">
            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="#">
                                <em class="fa fa-home"></em>
                            </a></li>
                        <li class="active">Report / Month Wise graph
                </div>
            </div>
            <div class="col-md-12">
                <div id="linechart" style="width: 90%; height: 500px;"></div>
                <div class="text-center mt-3">
                    <button class="btn btn-primary" onclick="saveChartAsImage()">Save Chart as Image</button>
                </div>

            </div>
        </div>
    </div>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
</body>

</html>