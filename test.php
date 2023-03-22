<?php 
echo 'Tharani';
define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT'].'/');
echo ROOT_PATH;
require_once(ROOT_PATH.'config.php');
echo TIMEZONE;


$hostname_conn_obj = "localhost";
$database_conn_obj = "ebuddey";
$username_conn_obj = "ebuddey";
$password_conn_obj = "ebuddey123!";

$conn_obj = (mysqli_connect($hostname_conn_obj, $username_conn_obj, $password_conn_obj, $database_conn_obj) or trigger_error(mysql_error(),E_USER_ERROR)); 
mysqli_query("SET time_zone = ".TIMEZONE);
mysqli_query("SET NAMES utf8");


//require_once('includes/functions.php');

$htmlEmilTemplate ='<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	</head>
	<body style="margin:0; background:#f2f2f2;">
	<table width="100%" height="100%" bgcolor="#f2f2f2" cellpadding="10">
	<tr>
	<td>
	
	<table align="center" width="635"   cellspacing="0" cellpadding="0" border="0">
	  <tr>     
		<td bgcolor="#0084ff"  width="100%" height="20"></td> 
	  </tr>
	</table>
	
	<table align="center" width="635"   cellspacing="0" cellpadding="0" border="0">
	  <tr>     
		<td bgcolor="#0084ff"  width="20"></td> 
		<td bgcolor="#0084ff" valign="left" width="220" height="83" border="0" cellpadding="0" cellspacing="0"><a target="_blank" href="'.BASE_URL.'" ><img border="0" alt="'.SITE_NAME.'"  src="'.BASE_URL.'images/EmailLogo.jpg" /></a></td>
		<td width="275" bgcolor="#0084ff" align="center" valign="middle" style="color:#919191;font-family: Arial, Helvetica, sans-serif; font-size: 12px;" >&nbsp;</td>    
		<td bgcolor="#0084ff"  width="20"></td>    
	  </tr>
	</table>
	
	<table border="0" cellspacing="0" cellpadding="0" width="635" height="1" align="center">
	  <tr>
		<td bgcolor="#FFFFFF" valign="top" width="20" height="1" style="border-bottom:1px solid #fff; background:#0084ff;"></td>
		<td width="595" valign="top" height="1" bgcolor="#fff" style="border-bottom:1px solid #f2f2f2; background:#0084ff;">&nbsp;</td>
		<td bgcolor="#FFFFFF" valign="top" width="20" height="1" style="border-bottom:1px solid #fff; background:#0084ff;"></td> 
	  </tr>
	</table>
	
	<table align="center" width="635"   cellspacing="0" cellpadding="0" border="0">
	  <tr>     
		<td bgcolor="#FFFFFF"  width="100%" height="30"></td> 
	  </tr>
	</table>
	
	<table align="center" width="635" border="0" cellspacing="0" height="380" cellpadding="0">
	  
	  <tr>
		<td bgcolor="#FFFFFF" width="20"></td>
		 <td bgcolor="#FFFFFF" width="595" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #919191; line-height: 20px;">
		
			////////EMAIL_STRING////////////
		
		</td>
		<td bgcolor="#FFFFFF" width="20" valign="top"></td>
	  </tr>
	 
	</table>
	
	<table align="center" width="635"   cellspacing="0" cellpadding="0" border="0">
	  <tr>     
		<td bgcolor="#FFFFFF"  width="100%" height="60"></td> 
	  </tr>
	</table>
	
	<table border="0" cellspacing="0" cellpadding="0" width="635" align="center">
	  <tr>
		<td bgcolor="#FFFFFF"  width="20"></td>
		<td width="595"  bgcolor="#fff" style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #919191; line-height: 16px; background:#fff;">
		&copy; '.date('Y').' '.SITE_NAME.'. All rights reserved.<br>
		
		</td>
		<td bgcolor="#FFFFFF"  width="20"></td> 
	  </tr>
	</table>
	
	<table align="center" width="635"   cellspacing="0" cellpadding="0" border="0">
	  <tr>     
		<td bgcolor="#FFFFFF"  width="100%" height="10"></td> 
	  </tr>
	</table>
	
	</td>
	</tr>
	</table>
	</body>
	</html>';

echo $htmlEmilTemplate;