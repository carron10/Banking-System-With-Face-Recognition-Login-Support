<?php
function check_login()
{
echo "ID";
echo $_SESSION['client_id'];


if(strlen($_SESSION['client_id'])==0)
	{
		$host = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra="pages_client_index.php";
		$_SESSION["client_id"]="";
		header("Location: http://$host$uri/$extra");
	}
}
