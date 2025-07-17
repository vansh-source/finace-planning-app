<?php
session_start();
error_reporting(0);

$conn = mysqli_connect("localhost", "root", "", "dhfm");
if ($conn) {
    echo "Connected";
}

try {
    $userId = $_SESSION['detsuid'];
    $sql = "SELECT c.cate_name, SUM(e.amount) AS total_amount
            FROM expense_master e
            INNER JOIN category_master c ON c.cate_id = e.cate_id 
            WHERE e.user_id = $userId
            GROUP BY c.cate_name";
    $fire = mysqli_query($conn, $sql);

    $data = "data.addColumn('string', 'Category Name');
    data.addColumn('number', 'Amount');";

    while ($result = mysqli_fetch_assoc($fire)) {
        $data .= "data.addRow(['" . $result['cate_name'] . "', " . $result['total_amount'] . "]);";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finance planning || Datewise Expense Report</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/datepicker3.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            <?php echo $data; ?>

            var options = {
                title: 'Expense Distribution',
                is3D: true,
                pieSliceTextStyle: {
                    color: 'black',
                },
                chartArea: {
                    left: 50,
                    top: 50,
                    width: '100%',
                    height: '80%',
                },
                legend: {
                    alignment: 'center',
                    textStyle: {
                        fontSize: 12,
                    },
                },
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

        function saveChartAsImage() {
            var chart = document.getElementById('piechart');
            var options = {
                backgroundColor: 'white',
            };

            html2canvas(chart, options).then(function(canvas) {
                var dataURL = canvas.toDataURL('image/png');
                var link = document.createElement('a');
                link.href = dataURL;
                link.download = 'chart.png';
                link.click();
            });
        }
    </script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        #linechart {
            margin-left: 90px;
        }
    </style>
</head>

<body>
    <?php include_once('includes/header.php'); ?>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><em class="fa fa-home"></em></a></li>
            <li class="active">Report</li>
        </ol>
    </div><!--/.row-->
    <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="#">
                            <em class="fa fa-home"></em>
                        </a></li>
                    <li class="active">Report / Category wise Graph</li>
                </ol>
            </div>
        </div><!--/.row-->
        <div class="row">
            <div class="col-md-3">
                <?php include_once('includes/sidebar.php'); ?>
            </div>
            <div class="col-md-9">
                <div id="piechart" style="width: 900px; height: 500px;"></div>
                <div class="text-center mt-3">
                    <button class="btn btn-primary" onclick="saveChartAsImage()" style="margin-left: -375">Save</button>
                </div>
            </div>
        </div>


        <?php include_once('includes/header.php'); ?>
</body>

</html>