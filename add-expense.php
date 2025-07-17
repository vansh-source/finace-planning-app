<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['detsuid'] == 0)) {
	header('location:logout.php');
} else {
	// for add income
	if (isset($_POST['submit'])) {
		$userid = $_SESSION['detsuid'];
		$pay_mode = $_POST['pay_mode'];
		$expense_de = $_POST['expensede'];
		$cate_id = $_POST['category'];
		$amount = $_POST['amount'];
		$date = $_POST['expensedate'];
		$rem = $_POST['re'];

		$cash = "Cash";
		$on = "Online";
		// for cash
		if ($pay_mode == $cash) {
			$st = "select cash_total from bal_master where user_id =$_SESSION[detsuid];";
			$q2 = mysqli_query($con, $st);
			$re = mysqli_fetch_array($q2);
			$cashtotal = $re['cash_total'];
			if ($amount > $cashtotal) {
				echo "<script>alert('Expense amount over then total ');</script>";
			} else {

				//////////////////////////////////////for budget minus//////////////////////////////////////////
				$q1 = mysqli_query($con, "select cate_id from budget_master where user_id=$_SESSION[detsuid];");
				while ($row = mysqli_fetch_array($q1)) {
					$c_id = $row['cate_id'];
					if ($c_id == $cate_id) {

						$sam = "select amount from budget_master where cate_id=$c_id";
						$q2 = mysqli_query($con, $sam);
						$re = mysqli_fetch_array($q2);
						$am = $re['amount'];
						echo "<script>alert('$am budget of cat before addd expense');</script>";
						$am = $am - $amount;
						$uam = "update budget_master set amount = $am where cate_id=$c_id";
						$re = mysqli_query($con, $uam);
						if ($am <= 0) {
							echo "<script>alert('$am ...............................out of budget.......................................');</script>";
						} else {
							echo "<script>alert('$am budget remaining');</script>";
						}
					}
				}

				///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


				$query = mysqli_query($con, "insert into expense_master(user_id,cate_id,amount,expense_de,date,pay_mode) values('$userid','$cate_id','$amount','$expense_de','$date','$pay_mode')");
				$last = mysqli_insert_id($con);
				if ($rem) {
					$query1 = mysqli_query($con, "insert into reimburse_master(user_id,cate_id,expense_id,amount,date,re_de,pay_mode) values('$userid','$cate_id','$last','$amount','$date','$expense_de','$pay_mode');");
				}





				$total = $cashtotal - $amount;
				$q3 = "update bal_master set cash_total = $total where user_id= $_SESSION[detsuid]";
				$re = mysqli_query($con, $q3);

				if ($query && $q3) {
					echo "<script>alert('Expense has been added');</script>";
					echo "<script>window.location.href='manage-expense.php'</script>";
				} else {
					echo "<script>alert('Something went wrong. Please try again');</script>";
				}
			}
		}
		// for online 
		if ($pay_mode == $on) {
			$st = "select online_total from bal_master where user_id =$_SESSION[detsuid];";
			$q2 = mysqli_query($con, $st);
			$re = mysqli_fetch_array($q2);
			$onlinetotal = $re['online_total'];
			if ($amount > $onlinetotal) {
				echo "<script>alert('Expense amount over then total ');</script>";
			} else {



				//////////////////////////////////////for budget minus//////////////////////////////////////////
				$q1 = mysqli_query($con, "select cate_id from budget_master where user_id=$_SESSION[detsuid];");
				while ($row = mysqli_fetch_array($q1)) {
					$c_id = $row['cate_id'];
					if ($c_id == $cate_id) {

						$sam = "select amount from budget_master where cate_id=$c_id";
						$q2 = mysqli_query($con, $sam);
						$re = mysqli_fetch_array($q2);
						$am = $re['amount'];
						echo "<script>alert('$am budget of cat before addd expense');</script>";
						$am = $am - $amount;
						$uam = "update budget_master set amount = $am where cate_id=$c_id";
						$re = mysqli_query($con, $uam);
						if ($am <= 0) {
							echo "<script>alert('$am ...............................out of budget.......................................');</script>";
						} else {
							echo "<script>alert('$am budget remaining');</script>";
						}
					}
				}

				///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


				$query = mysqli_query($con, "insert into expense_master(user_id,cate_id,amount,expense_de,date,pay_mode) values('$userid','$cate_id','$amount','$expense_de','$date','$pay_mode')");
				$last = mysqli_insert_id($con);
				if ($rem) {
					$query1 = mysqli_query($con, "insert into reimburse_master(user_id,cate_id,expense_id,amount,date,re_de,pay_mode) values('$userid','$cate_id','$last','$amount','$date','$expense_de','$pay_mode');");
				}
				-$total = $onlinetotal - $amount;
				$q3 = "update bal_master set online_total = $total where user_id= $_SESSION[detsuid]";
				$re = mysqli_query($con, $q3);

				if ($query && $q3) {
					echo "<script>alert('Expense has been added');</script>";
					echo "<script>window.location.href='manage-expense.php'</script>";
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
		<title>DHFM || Add Expense</title>
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
					<li class="active">Expenses / Add Expenses</li>
				</ol>
			</div><!--/.row-->




			<div class="row">
				<div class="col-lg-12">



					<div class="panel panel-default">
						<div class="panel-heading"> Add Expense</div>
						<div class="panel-body">
							<p style="font-size:16px; color:red" align="center"> <?php if ($msg) {
																						echo $msg;
																					}  ?> </p>
							<!-- for add data -->
							<div class="col-md-12">

								<form role="form" method="post" action="">
									<div class="form-group">
										<label>Cost of Item</label>
										<input class="form-control" type="number" value="" required="true" name="amount">
									</div>
									<div class="form-group">
										<label>Description</label>
										<input type="text" class="form-control" name="expensede" value="" required="true">
									</div>

									<div class="form-group">
										<label>Date of Expense</label>
										<input class="form-control" type="date" value="" name="expensedate" required="true">
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
										<label>Payment Mode</label>
										<select name="pay_mode">
											<option value="Cash">Cash</option>
											<option value="Online">Online</option>
										</select>
									</div>
									<div class="form-group">
										<label>Reimburse : </label>
										<input type="checkbox" name="re">
									</div>
									<div class="form-group has-success">
										<button type="submit" class="btn btn-primary" name="submit">Add Expense</button>
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
<?php }  ?>