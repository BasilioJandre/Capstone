<?php
session_start();

if(isset($_SESSION['sess']))
{
$sess = $_SESSION['sess'];

	if($sess == 'TRUE')
	{
	$user_id = $_SESSION['id'];
	$email = $_SESSION['email'];
	$dept = $_SESSION['dept'];
	$role = $_SESSION['role'];
	$name = $_SESSION['name'];
	$sess = $_SESSION['sess'];
	}
}

else
{
$_SESSION['sess']='';
$sess = '';
}

?>