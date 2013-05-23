<?php
require "../php_scripts/skooler.php";
	$cls_ob=new Classes();
				
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../script/common.js" type="text/javascript"> </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Skooltimes class</title>
<link href="../styles/first.css" rel="stylesheet" type="text/css" />
</head>

<body>


    <?php require "some_php/headbar.php"; ?>
<br class="clearfloat" />
<div class="wrapper">
 
  <div class="menu leftbar">
    <?php $cls_ob->put_menu(); ?>
  </div>  
  
  <div class="class_content rightbar">
		<div class="class_details">
	    <?php $cls_ob->put_header(); ?>
		</div>
	<br class="clearfloat" />
	
	<?php $cls_ob->put_content(); ?>
   
  </div>
<!-- wrapper --></div>

<?php require ("../".GUI."footer.php"); ?>
</body>

</html>
