<?php
/*
	Function Script: profile/functions/changePassword.php
	This script changes a Students Login password.
	
	Inputs:
		'password' - Must match database password
		'newpass' - 1 to 20 characters. Must not match password
		'cpass' - Must match newpass
		
	Output Codes:
		0 = Script failure
		1 = Success
		2 = Idle Timeout
		3 = Incorrect Password
		4 = New Password Constraint Error
		5 = New Password is the Same
		6 = Passwords do not match
*/

//Start/Load session
session_start();

//check idle time
if(($_SESSION['idle'] + 600) < time()){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
	echo "2";
	exit();
}

$logins = new mysqli("localhost", "Scheduler", "system", "Logins");
if($logins->connect_error){
	echo "0";
	exit();
}
$pass = $_POST['password'];
$npass = $_POST['newpass'];
$cpass = $_POST['cpass'];
$user = $_SESSION['username'];

//check new password length constraints
if(strlen($npass) > 20 || strlen($npass) < 1){
   echo "4";
   exit();
}
//check if current password and new password are the same.
if($npass == $pass){
   echo "5";
   exit();
}
//check to see if new password and confirm password are the same. 
if($npass != $cpass){
   echo "6";
   exit();
}
// check if password matches current password in database
$res = $logins->query("SELECT * FROM Logins WHERE Sid = '$user' AND Password = '$pass';");
if ($res->num_rows == 0) {
    echo "3";
    exit();
}

//update new password
$sql = "UPDATE Logins SET Password = '$npass' WHERE Sid = '$user';";
if ($logins->query($sql) === TRUE) {
    echo "1";
} else {
    echo "0";
}
$logins->close();

exit();

?>