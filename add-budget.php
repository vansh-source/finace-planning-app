<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);
if (strlen($_SESSION['detsuid'] == 0)) {
	header('location:logout.php');
} else {
	if (isset($_POST['submit'])) {
		$userid = $_SESSION['detsuid'];
		$cate_id = ($_POST['category']);
		$sdate = ($_POST['startdate']);
		$edate = ($_POST['enddate']);
		$amount = ($_POST['amount']);
		echo "test";

		$query = mysqli_query($con, "insert into budget_master(user_id,cate_id,amount,set_amount,start_date,end_date) value('$userid','$cate_id','$amount','$amount','$sdate','$edate');");
		if ($query) {
			echo "<script>alert('budget has been added');</script>";
			echo "<script>window.location.href='manage-budget.php'</script>";
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
		<title>DHFM || Add budget</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
		<link href="css/datepicker3.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">

		<!--Custom Font-->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
		<script type="text/javascript">


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
					<li class="active">Budget / Add Budget</li>
				</ol>
			</div><!--/.row-->




			<div class="row">
				<div class="col-lg-12">



					<div class="panel panel-default">
						<div class="panel-heading">Add Budget</div>
						<div class="panel-body">
							<p style="font-size:16px; color:red" align="center"> <?php if ($msg) {
																						echo $msg;
																					}  ?> </p>
							<div class="col-md-12">
								<?php
								$userid = $_SESSION['detsuid'];


								?>
								<form role="form" method="post" action="" name="add-budget" onsubmit="return checkpass();">



									<div class="form-group">
										<label>Start Date</label>
										<input class="form-control" type="date" value="" name="startdate" required="true">
									</div>


									<div class="form-group">

										<label>Category</label>
										<select name="category">
											<option value="">Select Category</optio>
												<?php
												$q1 = mysqli_query($con, "select cate_id,cate_name from category_master");
												while ($row = mysqli_fetch_assoc($q1)) {
													$c_id = $row['cate_id'];
													$c_name = $row['cate_name'];
												?>

											<option value="<?php echo $c_id; ?>"><?php echo $c_name; ?></optio>
											<?php } ?>

										</select>
									</div>

									<div class="form-group">
										<label>Budget</label>
										<input class="form-control" type="number" value="" required="true" name="amount">
									</div>



									<div class="form-group">
										<label>End Date</label>
										<input class="form-control" type="date" value="" name="enddate" required="true">
									</div>


									<div class="form-group has-success">
										<button type="submit" class="btn btn-primary" name="submit">Add</button>
									</div>

							</div>
						<?php } ?>
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
	<?php  ?>