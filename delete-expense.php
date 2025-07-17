<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['detsuid']==0)) {
  header('location:logout.php');
  } else
if(isset($_GET['delid']))
{

$rowid=$_GET['delid'];

$query1=mysqli_query($con,"select cate_id,amount,pay_mode from expense_master where expense_id='$rowid';");
$res1= mysqli_fetch_array($query1);
$pay_mode = $res1['pay_mode'];
$amo = $res1['amount'];
$cid=$res1['cate_id'];


 $on = "Online";
 $ca = "Cash";
if($pay_mode == $ca)
{
	$query2=mysqli_query($con,"select cash_total from bal_master where user_id='$_SESSION[detsuid]';");
	$res2= mysqli_fetch_array($query2);

	$cash_total = $res2['cash_total'];
	$total = $cash_total + $amo;

	$query3=mysqli_query($con,"update bal_master set cash_total = '$total' where user_id='$_SESSION[detsuid]';");
	
 

}
elseif($pay_mode == $on)
{
    $query2=mysqli_query($con,"select online_total from bal_master where user_id='$_SESSION[detsuid]';");
	$res2= mysqli_fetch_assoc($query2);
	$on_total = $res2['online_total'];
	$total = $on_total + $amo;

	
	$query3=mysqli_query($con,"update bal_master set online_total = '$total' where user_id='$_SESSION[detsuid]';");




}
////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sam="select amount from budget_master where cate_id=$cid";
					$q2=mysqli_query($con,$sam);
					$re = mysqli_fetch_array($q2);
					$am =$re['amount'];
					echo "<script>alert('$am budget of cat before addd expense');</script>";
					$am=$am+$amo;
					$uam = "update budget_master set amount = $am where cate_id=$cid";
					$re =mysqli_query($con,$uam);
					if($am <= 0)
					{
						echo "<script>alert('$am ...............................out of budget.......................................');</script>";
					}
					else
					{
						echo "<script>alert('$am budget remaining');</script>";
					}
///////////////////////////////////////////////////////////////////////////////////////////////////////
$query=mysqli_query($con,"delete from expense_master where expense_id='$rowid';");
if($query){
echo "<script>alert('Record successfully deleted');</script>";
echo "<script>window.location.href='manage-expense.php'</script>";
} else {
echo "<script>alert('Something went wrong. Please try again');</script>";

}
}
