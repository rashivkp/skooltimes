<?php require (GUI."head.php"); ?>

<body>
	
	<?php require (GUI."headbar.php"); ?>
	
	<div class="wrapper">
		<div class="leftbar">
			<div class="choices">
				<?php $usr_ob->put_choices();
				$usr_ob->put_related_choices(); ?>
			</div>
		</div>
		<div class="rightbar">
			<div class="header">
				<div class="photo"><a href="?action=settings"><img class="fltlft" src="<?php $usr_ob->put_photo(); ?>" width='100 px' height='110 px' /></a></div>
				<div class="details fltlft">
				<?php $usr_ob->put_user_info();			
				?>
				
				</div>
			</div>
			
			<br class="clearfloat"/>
			
			<div id="menu"> <?php require "menu.php"; //displaying menu ?>   </div>
			
			<br class="clearfloat"/>
			<div class="subheadcontainer">
				<h4 class="subhead"><?php  $usr_ob->put_choice_head(); ?></h4>
			</div>
			    
			
			<div class="content" id="choice_content">
			
				<?php
				 $usr_ob->put_choice_contents();
				 
					 ?>	                  
			</div>			
		</div>
		
	</div> <!--closing of wrapper-->	
	<?php
	
	
	 require (GUI."footer.php"); ?>
	
</body>
</html>

