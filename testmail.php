<?php
$to = "sivaraj617@gmail.com";
$subject = "HTML Test email";

$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <admin@buddeytf.com>' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";

$result = mail($to,$subject,$message,$headers);
if(!$result) {   
    echo "Error";   
} else {
    echo "Success";
}
?>