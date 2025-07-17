<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['detsuid']==0)) {
  header('location:logout.php');
  } else{
//code deletion
if(isset($_GET['delid']))
{

$rowid=$_GET['delid'];
$query1=mysqli_query($con,"select amount,pay_mode from income_master where income_id='$rowid';");
$res1= mysqli_fetch_array($query1);
$pay_mode = $res1['pay_mode'];
$amo = $res1['amount'];
 $on = "Online";
 $ca = "Cash";
if($pay_mode == $ca)
{
	$query2=mysqli_query($con,"select cash_total from bal_master where user_id='$_SESSION[detsuid]';");
	$res2= mysqli_fetch_array($query2);
	$cash_total = $res2['cash_total'];
	$total = $cash_total - $amo;

	$query3=mysqli_query($con,"update bal_master set cash_total = '$total' where user_id='$_SESSION[detsuid]';");
	
    

}
elseif($pay_mode == $on)
{
    $query2=mysqli_query($con,"select online_total from bal_master where user_id='$_SESSION[detsuid]';");
	$res2= mysqli_fetch_assoc($query2);
	$on_total = $res2['online_total'];
	$total = $on_total - $amo;

	
	$query3=mysqli_query($con,"update bal_master set online_total = '$total' where user_id='$_SESSION[detsuid]';");
}
$query=mysqli_query($con,"delete from income_master where income_id='$rowid';");
if($query){
echo "<script>alert('Record successfully deleted');</script>";
echo "<script>window.location.href='manage-income.php'</script>";
} else {
echo "<script>alert('Something went wrong. Please try again');</script>";

}
}
  }
