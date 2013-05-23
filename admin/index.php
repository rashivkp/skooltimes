<?php //__________________Login Page______________
session_start();
require "../php_scripts/config_set.php";
  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../styles/first.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>skooltimes</title>
</head>

<body bgcolor="#FFE4E1" >
    <div class="wrapper" >	
	<?php
    if (@$_GET['action']=="logout")
    {
        session_destroy();
        header("location:index.php");
    }
    else if(@$_SESSION['admin']===true)
    {
        require "main.php";
    }
    else if(isset($_POST['enter']))
    {
        if( admin_login($_POST['user'],$_POST['passwd']) )
        {
            require "main.php";
            }
            else
            {
                session_destroy();
                echo "enter correct keywords";
                }
        
    }
    else
    {
    ?>

        <div id="heading" align="center">
            <img src="../images/logo.png" height="100" width="200" /><br />
        </div>
        <div id="login_form" align="center">
        <table id="login_table" width="400" height="153" border="0" >
        <form action="index.php" method="POST" >
            <tr>
                <td width="100%" height="25">Nick name of Admin</td>
                <td width="100%"><input name="user" type="text" /></td>
            </tr>
            <tr>
                <td height="25">Admin Password</td>
                <td><input name="passwd" type="password" /></td>
            </tr>
            <tr>
                <td colspan="2"><center><input type="submit" name="enter" id="enter" value="        Login          " /></center></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            </form>    </table>
        </div> 
        
        <center id="developer"><a href="../">Goto Skooltimes</a></center>
		<br />
        <center id="developer">copyrighted by skooltimes&copy; 2011</center>
	
<?php } ?>
    </div>
	</body>
		
</html>

<?php 

function admin_login($username, $passwd)
{
        $dbconnect=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME) or die("error");
        $result=mysqli_query($dbconnect,"SELECT * FROM `admin` WHERE `pass` = '$passwd' AND `user` = '$username'") or die("query error");
         mysqli_close($dbconnect);
        if(mysqli_num_rows($result)==1)
        {
                $_SESSION['admin']=true;
                @$_SESSION['login_status']==true;
                return true;
        }
        else
                return false;
}
            


?>
