<?php require (GUI."head.php"); ?>

<body>
	
<?php require "./php_scripts/gui/headbar.php"; ?>

<div class="wrapper">
	<div class="leftbar">
	 <div class="menu">
		<ul class="nav fltlft">
		  <li><a href="./">Home</a></li>
		  <li><a class="<?php if(@$_GET['act']=="")echo "current"; ?>" href="./?action=lib">Books</a></li>
		  <li><a class="<?php if(@$_GET['act']=="new") echo "current"; ?>" href="./?action=lib&act=new">Upload Book</a></li>
		</ul>
    </div>
   </div>
 
  <div class="rightbar">
	<?php
	if(@$_GET['action']=="lib" && @$_GET['act']=="new")
	{ 
		if(@$_POST['new_book'])
		{
			//uploading book
			$usr_ob->new_book($_POST['nbook'], $_POST['author'], $_POST['comment']);			
		}
		
		?>
		<h2>Upload New Book</h2>
		<table border="0" cellspacing="10"><tbody>
		<form action="./?action=lib&act=new" method="POST" enctype="multipart/form-data">
			<tr><td><label>Name</label></td><td><input type="text" name="nbook" /></td></tr>
			<tr><td><label>Author</label></td><td><input type="text" name="author" /></td></tr>
			<tr><td><label>Comment</label></td><td><textarea rows='5' cols='30' type="text" name="comment" ></textarea></td></tr>
			<tr><td><label>Select File</label></td><td><input type="file" name="file" /></td></tr>
			<tr><td align="center" colspan="2"><input type="submit" name="new_book" value="Post new Book" /></td></tr>	
		</form>
		</tbody></table>
<?php
	}
	else
	{
		?>
		<div class="search_lib">
			<form action="./?action=lib" method="GET" >
				<input type="hidden" name="action" value="lib" />
				<input type="text" name="query" id="query" />
				<input type="submit" name="search" value="Search Books" />
			</form>
		</div>
		<div class="lib">
<?php
		if(@$_GET['query']!="")
		{
			$usr_ob->search_books($_GET['query']);		
		}
		else 
		{?>
			
			<?php $usr_ob->put_books(); ?>
			
		
<?php	
		}
		echo "</div>";
	}
	?>
	
	<!-- end .content --></div>
 
</div><!-- end .wrapper -->
 <?php require (GUI."footer.php"); ?>
</body>
</html>
