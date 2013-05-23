<?php 
	require "../php_scripts/skooler.php";
	session_start();
?>

<html>
<head>
<link href="../styles/first.css" rel="stylesheet" type="text/css" />
	<title>
	</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	if(isset($_POST['recover']))
	{
		
		Skooler::recover_passwd($_POST['username'], $_POST['mail_id']);
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
		<form action="./" method="POST">
			<tr><td>Username</td><td><span id="sprytextfield2">
			  <input type="text" name="username">
		  <span class="textfieldRequiredMsg">A value is required.</span></span></td></tr>
			<tr><td>Mail id</td><td><span id="sprytextfield1">
            <input type="text" name="mail_id">
          <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">please enter your registered mail id</span></span></td></tr>
			<tr><td colspan="2" align="center"><input type="submit" name="recover" value="Recover Password"></td></tr>
		</form>
		</tbody></table>
	</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur"]});
</script>
</body>
</html>
