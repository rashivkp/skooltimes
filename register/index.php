<?php // --------------------used for signup process ... ui for entering mail id and passcode-----------------------------
require "../php_scripts/register_class.php";
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="/skooltimes/styles/first.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>skooltimes</title>
</head>
<body>
<div class="wrapper">
  
  <?php //if button pressed this will check it,,,
if(@$_POST['mail_id']&&@$_POST['code']){
    //the script check the passcode entered and linking continue...
    if(Register::check_pass($_POST['mail_id'], $_POST['code']))
    {
        header("location:steps.php"); //sending data and continue signup process..
    }
    else
        die("cant recognise you, please make sure that you are invited by a admin user at skooltimes... ");
}


?>
  
  <form action="index.php" method="POST" >
  <table>
  <tbody>
  <tr><td colspan="2" align="center"> Welcome to skooltimes - Registration </td></tr>
  <tr><td>Mail id</td><td>
  <input type="text" name="mail_id" />
  </td></tr>
  <tr><td>Pass code</td><td>
    <input type="password" name="code" />
    </td></tr>
  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
  <tr><td colspan="2" align="center"><input type="submit" value="send" /></td></tr>
  </tbody>
  </table>
  </form>

  <br />
  <br />
  <a href="../">Home</a>
  
</div>
</html>
