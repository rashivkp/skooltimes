<?php 
class Classes
{
	public $cls;//arry id,tut,name,tut_name,dpt,kw
	public $clg;//array id,sup,name,addr
	public $dpt;//array id, name, hod
	public $current;// array act,choice,opt,id ----these are for handling links and proper media to be presented...
	public $db;
	public $sub;//array name, id,tut
  
	function __construct()
	{
		//this is connection properties of database connection, change them for specifying the correct db
	   session_start();
	   if($_SESSION['login_status']===true)
	   {
		   $this->db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
		   $this->user=$_SESSION['user'];
		   if(isset($_SESSION['classes']))
		   {
			   $this->cls=$_SESSION['classes'];
		   }
		   else
		   {
			   $this->cls['kw']=@$_GET['id'];
			   $_SESSION['goto']=null;
		   }
			$this->get_details();
	   }
	   else
	   {
		   $_SESSION['message']="Please Login";
		   header("location:../");
	   }
		   
		//$this->db=$GLOBAL['db'];
	}
	
	function get_details()
	{
		if($res1=$this->db->query("SELECT * FROM `user` WHERE `username`= '".$this->cls['kw']."'"))
				{
					$row1=$res1->fetch_assoc();
					$this->cls['tut_name']=$row1['name'];
					$result=$this->db->query("SELECT * FROM `class` WHERE `tut_id` ='".$row1['usr_id']."'");
					   if($row=$result->fetch_assoc())
					   {
						   $this->cls['name']=$row['caption'];
						   $this->cls['tut']=$row['tut_id'];
						   $this->cls['dpt']=$row['dpt_id'];
						   $this->cls['id']=$row['cls_id'];
						   
					   }
					   $_SESSION['classes']=$this->cls;		   
				}
		
		$r=$this->db->query("SELECT * FROM `department` WHERE dpt_id ='".$this->cls['dpt']."' ");
		 if($row=$r->fetch_assoc())
		{
			$this->dpt=array('name'=>$row['caption'], 'id'=>$row['dpt_id'], 'hod'=>$row['dpt_hod_id']);
			if($r1=$this->db->query("SELECT * FROM  `college` WHERE  `clg_id` ='".$row['clg_id']."'"))
				 {
					 $row1=$r1->fetch_assoc();
					 $this->clg=array('name'=>$row1['caption'], 'id'=>$row1['clg_id'], 'address'=>$row1['address'], 'country'=>$row1['country']);
				 }	 
		}	
		
		$result=$this->db->query("SELECT * FROM `subject` WHERE `cls_id`= '".$this->cls['id']."'");
				  while($row=$result->fetch_assoc())
				   {
					   $this->sub[]=array("id"=>$row['sub_id'],"tut"=>$row['tut_id'],"name"=>$row['caption']);
				   }  		
	}
	
	function put_header()
	{
?>

		<h4 class="subhead"><?=$this->dpt['name']." ".$this->cls['name']?></h4>				
		<img src="../images/class.jpg" width="500px" height="200px" />
		<div class="desc_class"><?="college : ".$this->clg['name']?><br />
		<?="class Tutor : ".$this->cls['tut_name']?></div>
<?php
	}
	
	function put_posts()
	{
	}
	function put_menu()
	{
		?>
		<ul class="main">
		  <li><a href="../">Home</a></li>
		  <li><a href="./?act=std">students</a></li>
		  <li><a href="./?act=tut">Tutors</a></li>
		  <li><a href="./">Subjects</a>
			<ul class="sub_menu">
				<?php
				if($this->sub)
				{
					$i=1;
					foreach($this->sub as $item)
						echo '<li><a href="./?sub='.$i++.'" >'.$item['name']."</a></li>";
				}
				?>
			</ul>
		  </li>
		</ul>
		<?php
	}
	function put_content()
	{
		if(@$_GET['act']=="std")
		{
			?>
			<div>
				<table>
				<tbody>
				<?php $result=$this->db->query("SELECT * FROM `student` WHERE `cls_id`= '".$this->cls['id']."'");
				  while($row=$result->fetch_assoc())
				   {
					   if($res1=$this->db->query("SELECT * FROM `user` WHERE `usr_id`= '".$row['std_id']."'"))
						{
							$f=$res1->fetch_assoc();
							echo "<tr><td><img width='50' height='50' src='".$this->put_photo_by_id($f['photo'])."'/></td>
						<td><a href='../?action=msg&id=".$f['username']."'>".$f['name']."</a></td></tr>"; 
						}
						
				   }  ?>
				</tbody>
				</table>				   
			</div>
			<?php
			
		}
		else if(@$_GET['act']=="tut")
		{
			?>
			<div>
				<table>
				<tbody>
				<?php $result=$this->db->query("SELECT * FROM `subject` WHERE `cls_id`= '".$this->cls['id']."'");
				  while($row=$result->fetch_assoc())
				   {
					   if($res1=$this->db->query("SELECT * FROM `user` WHERE `usr_id`= '".$row['tut_id']."'"))
						{
							$f=$res1->fetch_assoc();
							echo "<tr><td><img width='50' height='50' src='".$this->put_photo_by_id($f['photo'])."'/></td>
						<td><a href='../?action=msg&id=".$f['username']."'>".$f['name']."</a></td></tr>"; 

						}
						
				   }  ?>
				</tbody>
				</table>
			</div>
			<?php
		}
		else
		{
				if(isset($_GET['sub']))
				{
					$tmp=$_GET['sub']-1;
					?>
						<div class="menu">
							<ul class="nav">
								<li><a href="./?sub=<?=$_GET['sub']?>">Posts</a></li>
								<li><a href="./?sub=<?=$_GET['sub']?>&opt=works">Works</a></li>
							</ul>
						<br class="clearfloat" /><br class="clearfloat" />
		<!-- end .header --></div>
					<div class="content">
					<?php
					
					if(@$_GET['opt']=="works")
					{
						$qry="SELECT * FROM works WHERE cls='".$this->cls["id"]."' AND sub='".$this->sub[$tmp]["id"]."' ";
							$result=$this->db->query($qry);					
							if($result->num_rows)
							{
								
								
								echo '<ul class="subj_works">';
								
												while($row=$result->fetch_assoc())
												{
													echo "<li><div class='workitem'>
													
															
															<div class='caption'><img src='../images/work.png' />".$row['caption']."</div>
															<div class='last_date'>Last date of submission: ".$row['last_date']."</div>
															<div class='post_date'> posted on: ".$row['post_date']." </div>
													</div></li><hr class='clearfloat' />";
															
												}
										echo "</ul><br class='clear' />";
												
							}
							else
								echo "There is no works";
					}
					else
					{//if subject is selected then it
						if(isset($_POST['new_qstn']))
						{
							$res=$this->db->query("INSERT INTO `sub_qstn` (`cls_id`, `sub_id`, `posted_id`, `qstn`, `post_date`, `qst_id`,`last_update`) 
										VALUES ('".$this->cls['id']."',
										'".$this->sub["$tmp"]['id']."',
										'".$this->user['id']."',
										'".$_POST['qstn']."',
										'".date("Y-m-d H:i:s")."',											
										NULL, '".date("Y-m-d H:i:s")."');");
						}
						else if(isset($_POST['post_reply']))
						{
							$this->db->query("INSERT INTO  `sub_ans` (`qst_id`, `ans_id`, `posted_id`, `post_date`, `ans` )
																VALUES ('".$_POST['qstn']."',
																 NULL,
																  '".$this->user['id']."',
																   '".date("Y-m-d H:i:s")."', 
																   '".$_POST['reply']."')");
							$this->db->query("UPDATE `sub_qstn` SET last_update='".date("Y-m-d H:i:s")."' WHERE qst_id='".$_POST['qstn']."'");
						}

						
					?>	
					<table>
						<tbody>

							<tr><td><a href="javascript:hideshow(document.getElementById('new_post'))">New Post <span>↓</span></a></td></tr>
							<tr id='new_post' >
							<form method="POST" action="./?sub=<?=$tmp+1?>" >
										<td>
										  <textarea cols="30" rows="5" name="qstn" ></textarea>                                          
									    </td>
										<td><input type="submit" name="new_qstn" value="POST"></td>
										
							</form></tr>
						</tbody>
						</table>		
			<ul class="post">
				<?php
				$result=$this->db->query("SELECT * FROM sub_qstn WHERE sub_id='".$this->sub[$tmp]['id']."' ORDER BY  `last_update` DESC LIMIT 10");
										$i=0;
				while(@$row=$result->fetch_assoc())
				{
					if($i++>10)
					exit;
					$p_id=$row['posted_id'];
					$result1=$this->db->query("SELECT * FROM user WHERE usr_id='".$p_id."'");
					$row1=$result1->fetch_assoc()
			?>
				<li>
				 <div class="posts">
				 <div class="post_header">
													
						<div class="post_author">
							<div class="avatar-wrap"><img class="avatar" width='68' height='68' src="<?php echo $this->put_photo_by_id($row1["photo"]); ?>" /></div>
							<div class="posted_user" ><?php echo $row1['name']; ?></div>
							<br />
						</div>
						<div class="post_content">
							<div class="qstn"><?php echo $row['qstn'] ?></div>
						</div>
						<div class="post_replays">
							<ul class="comments">
							<?php
							$result2=$this->db->query("SELECT * FROM sub_ans WHERE qst_id='".$row['qst_id']."'");
							while($row2=$result2->fetch_assoc())
							{
								$rp_id=$row2['posted_id'];
								$result3=$this->db->query("SELECT * FROM user WHERE usr_id='".$rp_id."'") or die("error on replays");
								$row3=$result3->fetch_assoc(); ?>
								<li>
								<div class="reply_author">
									<div class="avatar-wrap"><img class="avatar" width='68' height='68' src="<?php echo $this->put_photo_by_id($row3["photo"]); ?>" /></div>
									<span class="posted_user" ><?php echo $row3['name']; ?></span>
									
								</div>
								<div class="post_content">
								<div class="reply"><?php echo $row2['ans'] ?></div>
								</div>
								</li>
								<br class="clear" />
							<?php 
							} ?>
							<li><a href="javascript:hideshow(document.getElementById('newreply<?php echo $i; ?>'))">Reply<span>↓</span></a></li>
							
							
							</ul>
							<div id="newreply<?php echo $i; ?>" class="newreply">
								<form action="./?sub=<?=$tmp+1?>" method="POST">
									<textarea cols="30" rows="5" name="reply" /></textarea>
									<input type="hidden" name="qstn" value="<?php echo $row['qst_id']; ?>" />
									<input type="submit" name="post_reply" value="Reply" />
								</form>
							
						</div><!-- post_replays div -->		
						
				</div><!-- posts div -->  
				<hr />
				</li>		  
			<?php
				}
				?>
				</ul>
						
						
						<?php
						  
						
						
						
						
					}
					echo "</div>";
			}
		}
	}//fns
	
	function put_photo_by_id($photo)
		{
			if($photo!="")
			{
				return "../images/users/".$photo;
			}
			else
			{
				return "../images/user.png";
			}
		}
	
}//class
   ?>
