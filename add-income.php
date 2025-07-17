<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['detsuid'] == 0)) {
	header('location:logout.php');
} else {

	if (isset($_POST['submit'])) {
		$userid = $_SESSION['detsuid'];
		$pay_mode = $_POST['pay_mode'];
		$income_de = $_POST['incomede'];
		$amount = $_POST['amount'];
		$date = $_POST['incomedate'];


		$cash = "Cash";
		$on = "Online";
		if ($pay_mode == $cash) {
			$st = "select cash_total from bal_master where user_id =$_SESSION[detsuid];";
			$q2 = mysqli_query($con, $st);
			$re = mysqli_fetch_array($q2);
			$cashtotal = $re['cash_total'];
			if ($amount == 0) {
				echo "<script>alert('income amount less then zero ');</script>";
			} else {
				$query = mysqli_query($con, "insert into income_master(user_id,income_de,amount,date,pay_mode) values('$userid','$income_de','$amount','$date','$pay_mode')");

				$total = $cashtotal + $amount;
				$q3 = "update bal_master set cash_total = $total where user_id= $_SESSION[detsuid]";
				$re = mysqli_query($con, $q3);

				if ($query && $q3) {
					echo "<script>alert('Income has been added');</script>";
					echo "<script>window.location.href='manage-income.php'</script>";
				} else {
					echo "<script>alert('Something went wrong. Please try again');</script>";
				}
			}
		}
		if ($pay_mode == $on) {
			$st = "select online_total from bal_master where user_id =$_SESSION[detsuid];";
			$q2 = mysqli_query($con, $st);
			$re = mysqli_fetch_array($q2);
			$onlinetotal = $re['online_total'];
			if ($amount == 0) {
				echo "<script>alert('income amount less then zero ');</script>";
			} else {
				$query = mysqli_query($con, "insert into income_master(user_id,income_de,amount,date,pay_mode) values('$userid','$income_de','$amount','$date','$pay_mode')");

				$total = $onlinetotal + $amount;
				$q3 = "update bal_master set online_total = $total where user_id= $_SESSION[detsuid]";
				$re = mysqli_query($con, $q3);

				if ($query && $q3) {
					echo "<script>alert('Income has been added');</script>";
					echo "<script>window.location.href='manage-income.php'</script>";
				} else {
					echo "<script>alert('Something went wrong. Please try again');</script>";
				}
			}
		}
	}

?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Finance planning || Add Income</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
		<link href="css/datepicker3.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">

		<!--Custom Font-->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
		<script>
			function checkDate(input) {
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
	</head>

	<body>
		<?php include_once('includes/header.php'); ?>
		<?php include_once('includes/sidebar.php'); ?>

		<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
			<div class="row">
				<ol class="breadcrumb">
					<li><a href="#">
							<em class="fa fa-home"></em>
						</a></li>
					<li class="active">Income / Add Income</li>
				</ol>
			</div><!--/.row-->




			<div class="row">
				<div class="col-lg-12">



					<div class="panel panel-default">
						<div class="panel-heading"> Add Income</div>
						<div class="panel-body">
							<p style="font-size:16px; color:red" align="center"> <?php if ($msg) {
																						echo $msg;
																					}  ?> </p>
							<div class="col-md-12">
								<form role="form" method="post" action="">
									<div class="form-group">
										<label>Add Income</label>
										<input class="form-control" type="number" value="" required="true" name="amount">
									</div>
									<div class="form-group">
										<label>Description</label>
										<input type="text" class="form-control" name="incomede" value="" required="true">
									</div>
									<div class="form-group">
										<label>Date of Income</label>
										<input class="form-control" type="date" value="" name="incomedate" required="true" onchange="checkDate(this)">
									</div>
									<div class="form-group">
										<label>Payment Mode</label>
										<select name="pay_mode">
											<option value="Cash">Cash</option>
											<option value="Online">Online</option>
										</select>
									</div>
									<div class="form-group has-success">
										<button type="submit" class="btn btn-primary" name="submit">Add Income</button>
									</div>
								</form>
							</div>
						</div>
					</div><!-- /.panel-->
				</div><!-- /.col-->
				<?php include_once('includes/footer.php'); ?>
			</div><!-- /.row -->
		</div><!--/.main-->

		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/chart.min.js"></script>
		<script src="js/chart-data.js"></script>
		<script src="js/easypiechart.js"></script>
		<script src="js/easypiechart-data.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<script src="js/custom.js"></script>

	</body>

	</html>
<?php }  ?>