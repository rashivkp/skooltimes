<?php require (GUI."head.php"); ?>
<body>
	
<?php require "./php_scripts/gui/headbar.php"; ?>

<div class="wrapper">
	<div class="leftbar">
    <ul class="nav fltlft">
	  <?php $usr_ob->put_nb_menu(); ?>
    </ul>
   </div>
 
  <div class="rightbar">
	<?php
	if(@$_GET['action']=="nb" && @$_GET['act']=="new")
	{ 
		if(@$_POST['new_ntf'])
		{
			//creating new notification
			$usr_ob->new_notice($_POST["title"],$_POST["notice"],$_POST["for"]);
			
		}
		
		?>
		<h2>Create New Notification</h2>
		<table border="0" cellspacing="10"><tbody><form action="./?action=nb&act=new" method="POST" >			
			<tr><td><label>Caption</label></td><td><textarea name="title" cols="30" rows="2"></textarea></td></tr>
			<tr><td><label>Content</label></td><td><textarea name="notice" cols="30" rows="5"></textarea></td></tr>
			<tr><td><label>Available To</label></td><td><select name="for" >
															<option value="all">All</option>
															<option value="students">All students</option>
															<option value="staffs">All Staffs</option>
														</select></td></tr>
			<tr><td colspan="2" align="center"><input type="submit" name="new_ntf" value="          Publish         "></td></tr>
		</form>
		</tbody></table>
<?php
	}
	else
	{
		?>
		<div class="noticeboard">
		<?php $usr_ob->put_noticeboard(); ?>
		</div>	
		
<?php	
	}
	?>
	
	<!-- end .content --></div>

</div><!-- end .wrapper -->
<?php require (GUI."footer.php"); ?>
</body>
</html>
