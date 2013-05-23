<?php 
	require "../php_scripts/skooler.php";
	session_start();
?>

<html>
<head>
<link href="../styles/first.css" rel="stylesheet" type="text/css" />
	<title>
	</title>
</head>
<body>
<?php 
	if(isset($_POST['recover']))
	{
		if($_POST['newpass']==$_POST['newpass'])
		{
			Skooler::changing_passwd($_POST['pass'], $_POST['mail_id'],$_POST['newpass']);
		}
		else
		{
			echo "passwords do not match";
		}
	}
?>
	<div class="wrapper">
	<div class="error">
<?php 
		if(@$_SESSION['message']!="")
		{
			echo $_SESSION['message'];
			session_destroy();
		}
?>
	</div>
		<table><tbody>
		<form action="recover.php" method="POST">
			<tr><td>Passcode</td><td><input type="password" name="pass"></td></tr>
			<tr><td>Mail id</td><td><input type="text" name="mail_id"></td></tr>
			<tr><td>New Password</td><td><input type="password" name="newpass"></td></tr>
			<tr><td>Confirm Password</td><td><input type="password" name="cnewpass"></td></tr>
			<tr><td colspan="2" align="center"><input type="submit" name="recover" value="Recover Password"></td></tr>
		</form>
		</tbody></table>
	</div>
</body>
</html>
