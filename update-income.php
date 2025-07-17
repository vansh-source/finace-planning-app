<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['detsuid'] == 0)) {
	header('location:logout.php');
} else {

	if (isset($_POST['update'])) {
		$userid = $_SESSION['detsuid'];
		$income_de = $_POST['incomede'];
		$amount = $_POST['amount'];
		$date = $_POST['incomedate'];
		$query = mysqli_query($con, "update income_master set income_de='$income_de', amount = '$amount',date='$date' where income_id ='$_GET[updateid]';");;
		if ($query) {
			echo "<script>alert('Income has been updated');</script>";
			echo "<script>window.location.href='manage-income.php'</script>";
		} else {
			echo "<script>alert('Something went wrong. Please try again');</script>";
		}
	}

?>

	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>DHFM || Update Income</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
		<link href="css/datepicker3.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">

		<!--Custom Font-->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
		<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
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
					<li class="active">Income / Manage Income / Update Income</li>
				</ol>
			</div><!--/.row-->




			<div class="row">
				<div class="col-lg-12">



					<div class="panel panel-default">
						<div class="panel-heading">Update Income</div>
						<div class="panel-body">
							<p style="font-size:16px; color:red" align="center"> <?php if ($msg) {
																						echo $msg;
																					}  ?> </p>
							<div class="col-md-12">
								<?php

								if (isset($_GET['updateid'])) {
									$rowid = intval($_GET['updateid']);

									$userid = $_SESSION['detsuid'];
									$ret = mysqli_query($con, "select income_de,amount,date,pay_mode from income_master where income_id = '$rowid' ");

									while ($row = mysqli_fetch_array($ret)) {
								?>
										<form role="form" method="post" action="">
											<div class="form-group">
												<label>Cost of Item</label>
												<input class="form-control" type="number" value="<?php echo $row['amount']; ?>" required="true" name="amount">
											</div>
											<div class="form-group">
												<label>Description</label>
												<input type="text" class="form-control" name="incomede" value="<?php echo $row['income_de']; ?>" required="true">
											</div>

											<div class="form-group">
												<label>Date of Expense</label>
												<input class="form-control" type="date" value="<?php echo $row['date']; ?>" name="incomedate" required="true">
											</div>


											<!-- <div class="form-group">
									<label>Payment Mode</label>
									<select name="pay_mode">
                                    x``<option value=" </option>
									<option value="Cash">Cash</option>
									<option value="Online">Online</option>
									</select>
								</div> -->
									<?php }
								} ?>
									<div class="form-group has-success">
										<button type="submit" class="btn btn-primary" name="update">Update Income</button>
									</div>


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
<?php } ?>