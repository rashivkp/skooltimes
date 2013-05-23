<?php //__________________various steps for signup process_______________ 
session_start();
if(@$_SESSION['pass']!=true)
    die("ACCESS FORBIDDEN!!..");
require_once "../php_scripts/register_class.php";
        $new_usr=new Register();
if(isset($_POST['register']))
{
    $new_usr->get_invoke_fns();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="/skooltimes/styles/first.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>skooltimes</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
 <form action="steps.php" method="POST" enctype="multipart/form-data" >
<table width="451">
				<tr><td colspan="2" align="center" >User Details <hr /></td></tr>
				<tr><td colspan='2' align='center' style='color: green'><?=@$_SESSION['msg']?></td></tr>
				<?php 
                
                if( (isset($_POST['checkusrname'])) && ($_POST['username']!="") )
                {
                    
                    $db=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
                    $username=$_POST['username'];
                    
                    if($result=mysqli_query($db,"SELECT * FROM user WHERE username='$username'"))
                    {
                        if(mysqli_num_rows($result)>0)
                        {
                            $_SESSION['confirm']=0;
                            echo "<tr><td colspan='2' align='center' style='color: red'>please choose another one..!</td></tr>";
                        }
                        else
                        {
                            $_SESSION['confirm']=1;
                            echo "<tr><td colspan='2' align='center' style='color: green'>The username is available</td></tr>";
                         }
                    }
                }
                ?>
<tr><td>User Name</td>
					<td>
                    <input type="text" id="username" name="username" value="<?= (@$_SESSION['confirm']==1)?@$_POST['username']:"";   ?>" />
</td>
                </tr>
                <tr><td colspan="2" align="center"><input type="submit" name="checkusrname" value="check availability" /></td>
                </tr>
                
                <tr><td width="149">Full Name</td>
					<td width="290">
					  <input type="text" name="name" />
					</td>
                </tr>
                <tr><td>Mobile No</td>
					<td>
                    <input name="mob" id="mob" type="text" />
                    </td>
                </tr>
				<tr>
		      <td>email id</td>
					<td><input type="text" name="mail" value=<?php print $_SESSION['mail_id'] ?> readonly="readonly" /></td>
                </tr>				
				<tr><td>password</td>
					<td>
                    <input type="password" id="passwd" name="passwd" />
                    </td>
                </tr>
				<tr>
				  <td>Confirm password</td>
				  <td>
				    <input type="password" name="confirm" />
			      </td>
			  	</tr>
                <tr>
				  <td>Profession</td>
				  <td><select name="type" id="type" >
                  <?php echo "<option>".$_SESSION['profession']."</option>"?>
                  </select></td>
			  	</tr>
                
                <?php $new_usr->get_forms($_SESSION['profession']) ?>
                
				<tr>
				  <td>User Photo</td>
				  <td><input type="file" name="file" id="file" /></td>
			  	</tr>
                <tr>				
					<td colspan="2"><center><br /><input type="submit" name="register" value="        Register         " /></center></td>
                </tr>
				<tr>
					<td colspan="2"><center><input type="reset" value="        Reset         " /></center></td>
                </tr>
    </table>
  </form>
</div>
</body>
</html>


