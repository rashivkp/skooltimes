<?php require (GUI."head.php"); ?>
<body>	
<?php require "./php_scripts/gui/headbar.php"; ?>

<div class="wrapper">
	<div class="leftbar">
	<ul class="nav fltlft">
	  <li><a href="./">Home</a></li>
	  <li><a class="current" href="./?action=msg">Messages</a></li>
	</ul>
	 <br class="clearfloat" />
	 <br class="clearfloat" />
	</div>
	<div class="rightbar">
	 <?php	  
	   $usr_ob->message();			  		
	?>
		<!-- end .content --></div>
 
  
</div><!-- end .wrapper -->

 <?php require (GUI."footer.php"); ?>
</body>
</html>

