<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['detsuid'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_POST['update'])) {
        $userid = $_SESSION['detsuid'];
        $expense_de = $_POST['expensede'];
        $cate_id = $_POST['category'];
        $amount = $_POST['amount'];
        $date = $_POST['expensedate'];


        $sam = "select amount from expense_master where expense_id = '$_GET[updateid]';";
        $q2 = mysqli_query($con, $sam);
        $re = mysqli_fetch_array($q2);
        $am = $re['amount'];
        if ($am == $amount) {
            echo "<script>alert('amount has not  been updated');</script>";
        } else {
            echo "<script>alert('amount has been updated');</script>";
            if ($am < $amount) {
                $new_am = $amount - $am;
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
                        $am = $am - $new_am;
                        $uam = "update budget_master set amount = $am where cate_id=$c_id";
                        $re = mysqli_query($con, $uam);
                        if ($am <= 0) {
                            echo "<script>alert('$am ...............................out of budget.......................................');</script>";
                        } else {
                            echo "<script>alert('$am budget remaining');</script>";
                        }
                    }
                }
            } else {
                $new_am = $am - $amount;
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
                        $am = $am + $new_am;
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


            }
        }




        $query = mysqli_query($con, "update expense_master set amount = '$amount',expense_de='$expense_de',cate_id ='$cate_id' ,date='$date' where expense_id = '$_GET[updateid]';");;
        if ($query) {
            echo "<script>alert('Expense has been updated');</script>";
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
        <title>Finance planning|| New Update Expense</title>
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
                    <li class="active">Expense / Manage Expense/ Update Expense</li>
                </ol>
            </div>
            <!--/.row-->




            <div class="row">
                <div class="col-lg-12">



                    <div class="panel panel-default">
                        <div class="panel-heading">Update Expense</div>
                        <div class="panel-body">
                            <p style="font-size:16px; color:red" align="center"> <?php if ($msg) {
                                                                                        echo $msg;
                                                                                    }  ?> </p>
                            <div class="col-md-12">
                                <?php

                                if (isset($_GET['updateid'])) {
                                    $rowid = intval($_GET['updateid']);

                                    $userid = $_SESSION['detsuid'];
                                    $ret = mysqli_query($con, "SELECT expense_master.amount, expense_master.expense_de,expense_master.date,expense_master.pay_mode, category_master.cate_name,category_master.cate_id
                             FROM expense_master,category_master
                             WHERE  expense_master.cate_id = category_master.cate_id
                             AND expense_master.expense_id = '$rowid';");

                                    while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                        <form role="form" method="post" action="">
                                            <div class="form-group">
                                                <label>Cost of Item</label>

                                                <input class="form-control" type="number" value="<?php echo $row['amount']; ?>" required="true" name="amount">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text" class="form-control" name="expensede" value="<?php echo $row['expense_de']; ?>" required="true">
                                            </div>

                                            <div class="form-group">
                                                <label>Date of Expense</label>
                                                <input class="form-control" type="date" value="<?php echo $row['date']; ?>" name="expensedate" required="true">
                                            </div>
                                            <div class="form-group">
                                                <label>Payment Mode : </label>
                                                <label><?php echo $row['pay_mode']; ?></label>
                                            </div>

                                            <div class="form-group">
                                                <label>Category</label>
                                                <select name="category">
                                                    <option value="<?php echo $row['cate_id']; ?>"><?php echo $row['cate_name']; ?>
                                                    </option>
                                                    <option value="">Select Category</optio>
                                                        <?php
                                                        $s_cate = $row['cate_id'];

                                                        $q1 = mysqli_query($con, "select * from category_master where cate_id !='$s_cate';  ");
                                                        while ($row = mysqli_fetch_assoc($q1)) {

                                                            $c_id = $row['cate_id'];
                                                            $c_name = $row['cate_name'];
                                                        ?>

                                                    <option value="<?php echo $c_id; ?>"><?php echo $c_name; ?></optio>
                                                    <?php } ?>
                                                </select>
                                            </div>
                            </div>
                    <?php }
                                } ?>

                    <div class="form-group has-success">
                        <button type="submit" class="btn btn-primary" name="update">Update Expense</button>
                    </div>


                        </div>

                        </form>
                    </div>
                </div>
            </div><!-- /.panel-->
        </div><!-- /.col-->
        <?php include_once('includes/footer.php'); ?>
        </div><!-- /.row -->
        </div>
        <!--/.main-->

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