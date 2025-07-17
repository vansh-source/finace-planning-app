<?php
session_start();
error_reporting(0);

$conn = mysqli_connect("localhost", "root", "", "dhfm");
if ($conn) {
    // echo "Connected";
}
?>
<?php
session_start();
error_reporting(0);


if (strlen($_SESSION['detsuid']) == 0) {
    header('location:logout.php');
    exit();
}

$totalExpense = 0; // Initialize total expense variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['startdate']) && isset($_POST['enddate'])) {
        $startDate = $_POST['startdate'];
        $endDate = $_POST['enddate'];

        try {
            $currentUserId = $_SESSION['detsuid'];

            $query = "SELECT c.cate_name, SUM(e.amount) AS total_amount
                      FROM expense_master e
                      INNER JOIN category_master c ON c.cate_id = e.cate_id 
                      WHERE e.user_id = ? AND e.date BETWEEN ? AND ?
                      GROUP BY e.cate_id";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iss", $currentUserId, $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = array();
            while ($row = $result->fetch_assoc()) {
                $categoryName = $row['cate_name'];
                $amount = (float) $row['total_amount'];
                $data[] = array(
                    'category_name' => $categoryName,
                    'amount' => $amount
                );

                $totalExpense += $amount; // Add amount to the total expense
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Start date and end date are required";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finance planning|| Datewise Expense Report</title>
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
            data.addColumn('string', 'Category');
            data.addColumn('number', 'Amount');

            <?php
            foreach ($data as $row) {
                $categoryName = $row['category_name'];
                $amount = (float) $row['amount'];
                echo "data.addRow(['$categoryName', $amount]);";
            }
            ?>

            var options = {
                title: 'Expense Distribution',
                is3D: true
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

        function saveChartAsImage() {
            var chart = document.getElementById('piechart');
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

        function checkDate(input, field) {
            var selectedDate = new Date(input.value);
            var currentDate = new Date();

            if (selectedDate > currentDate) {
                input.value = formatDate(currentDate);
            }
        }

        function formatDate(date) {
            var year = date.getFullYear();
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var day = ("0" + date.getDate()).slice(-2);

            return year + "-" + month + "-" + day;
        }
    </script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="#">
                                <em class="fa fa-home"></em>
                            </a></li>
                        <li class="active">Report / Expense graph
                </div>
            </div>
            <div class="col-md-3">
                <?php include_once('includes/sidebar.php'); ?>
            </div>


            <div class="col-md-9">
                <form id="dateForm" method="POST" action="#" style="margin-top: 45px;">
                    <div class="form-group">
                        <label for="startdate">Start Date:</label>
                        <input type="date" id="startdate" name="startdate" class="form-control" value="<?php echo isset($_POST['startdate']) ? $_POST['startdate'] : ''; ?>" onchange="checkDate(this, 'startdate')">
                    </div>
                    <div class="form-group">
                        <label for="enddate">End Date:</label>
                        <input type="date" id="enddate" name="enddate" class="form-control" value="<?php echo isset($_POST['enddate']) ? $_POST['enddate'] : ''; ?>" onchange="checkDate(this, 'enddate')">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
                    <div class="text-center mt-3">
                        <h4>Total Expense: <?php echo $totalExpense; ?>rs</h4>
                    </div>
                <?php endif; ?>

                <div id="piechart" style="width: 100%; height: 500px;"></div>

                <div class="text-center mt-3">
                    <button class="btn btn-primary" onclick="saveChartAsImage()" style="margin-left: -375">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script>
        $('#calendar').datepicker();
    </script>
    <div class="col-md-3">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/sidebar.php'); ?>
    </div>
</body>

</html>