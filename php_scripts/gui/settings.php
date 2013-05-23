<?php require (GUI."head.php"); ?>
<body>
	
<?php require "./php_scripts/gui/headbar.php"; ?>

<div class="wrapper">
	<div class="leftbar">
    <ul class="nav fltlft">
	  <li><a href="./">Home</a></li>
      <li><a class="<?php if(@$_GET['act']!="pswd") echo "current"; ?>" href="./?action=settings">Settings</a></li>
      <li><a class="<?php if(@$_GET['act']=="pswd") echo "current"; ?>" href="./?action=settings&act=pswd">Change Password</a></li>
    </ul>
   </div>
 
<div class="rightbar">
 <?php
	  if(@$_GET['act']=="pswd")
	  {
		  if(isset($_POST['changepass']))
			{//Uploading photo to images/users/ and adding name of photo to user
				$usr_ob->change_password($_POST['oldpass'], $_POST['pass']);
			}
?>
		<table cellpadding="10"><tbody>
		  <form method="POST" action="./?action=settings&act=pswd" enctype="multipart/form-data" >
		  <tr><td width="113">Current Password</td><td width="295"><input name="oldpass" type="password"></td></tr>
		  <tr><td>New Password</td><td>
		    <input name="pass" id="pass" type="password">
		</td></tr>
		  <tr><td>Confirm Password</td><td>
		    <input id="cpass" type="password" name="cpass" />
		    </td></tr>
		  <tr><td colspan="2"><input type="submit" name="changepass" value="Change Password" /></td></tr>
		  </form>
	  </tbody></table>
<?php
	  }
	  else
	  {
		  if(isset($_POST['save']))
			{//Uploading photo to images/users/ and adding name of photo to user
				if($_FILES["file"]["error"] === UPLOAD_ERR_OK)	$usr_ob->upload_new_img();
				$usr_ob->save_changes($_POST['name'], $_POST['mail_id'], $_POST['mob']);
			}

 ?>
	  <table  cellpadding="10"><tbody>
		  <form method="POST" action="./?action=settings" enctype="multipart/form-data" >
		  <tr><td>Name</td><td><input name="name" value="<?php echo $usr_ob->user['name']; ?>" type="text"></td></tr>
		  <tr><td>Mobile No</td><td><input name="mob" value="<?php echo $usr_ob->user['mob']; ?>" type="text"></td></tr>
		  <tr><td>E-mail id</td><td><input name="mail_id" value="<?php echo $usr_ob->user['mail']; ?>" type="text"></td></tr>
		  <tr><td>Photo</td><td><input type="file" name="file" /></td></tr>
		  <tr><td colspan="2"><input type="submit" name="save" value="Save Changes" /></td></tr>
		  </form>
	  </tbody></table>
		
			
			
		
<?php 
		}
?>
	
</div><!-- end .rightbar -->

</div><!-- end .wrapper -->
 <?php require (GUI."footer.php"); ?>
</body>
</html>

