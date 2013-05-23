<?php 

require_once "config_set.php";  // including the details for connect to mysql database 
require_once "message_class.php";  // including the details for connect to messages 
require_once "classes_class.php";  //including the 'classes' class 
require_once "subject_class.php";  //including the 'classes' class 

class Skooler // this is a common class for all users, can retrieve the basic details from the db 'user'
{
	//act is the number of the main choice
	//opt is the sub_choice 
	//choice is another sub choice
    public $user;//array=> name,id, profession,mail,username, photo,mob
    protected $slogin;
    public $db; //mysqli connect object
    public $msg; //message connect object
    public $sub_ob; //message connect object
    
    public $current;// array act,choice,opt,id ----these are for handling links and proper media to be presented...
  
		function __construct()
		{
			//this is connection properties of database connection, change them for specifying the correct db
		   
			$this->db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
			 
			if($_SESSION['login_status']==true)
			{
				$this->user=$_SESSION['user'];
				$this->current=array('act'=>@$_GET['act'], 'choice'=>@$_GET['choice'], 'id'=>@$_GET['id'], 'opt'=>@$_GET['opt']);
			}
			else
			{
				session_destroy();
				session_start();
				$_SESSION['message']="please login";
				header("location:./");
				exit();
			}
		}
            //--------------------------------------------------------------------------------            
//checking the password and username at the login form
		static function check_login($username,$passwd)
		{
			$dbconnect=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME) or die("error");
			$res=mysqli_query($dbconnect,"SELECT `passwd` FROM `user` WHERE `username` = '$username'") or die("query error");			
			if(mysqli_num_rows($res)==1)
			{
				$rpass=mysqli_fetch_assoc($res);
				$pass=$rpass['passwd'];
				
				$result=mysqli_query($dbconnect,"SELECT * FROM `user` WHERE `passwd` = '".crypt($passwd, $pass)."' AND `username` = '$username'") or die("query error");
				if(mysqli_num_rows($result)==1)
				{
						$row=mysqli_fetch_assoc($result);                        
						$_SESSION['user']=array("username"=>$row['username'],
												"mail"=>$row['mail_id'],
												"name"=>$row['name'],
												"profession"=>$row['type'],
												"id"=>$row['usr_id'],
												"mob"=>$row['mob'],
												"photo"=>$row['photo']);
												
						$_SESSION['login_status']=true;
						$_SESSION['pass_error']=0;
				
				header("location:./");
				
				}
				else
				{
					$_SESSION['message']="Username or password is incorrect...!";
					header("location:./");			
				}
			}
			else
			{
				$_SESSION['message']="Username or password is incorrect...!";
				header("location:./");			
			}
			
			mysqli_close();
		}
//function for logging out
		function logout()
		{
			$msg;
			if($_SESSION['pass_error']>2)
			{
				$msg="Login again to make any changes..!";
			}
			else
			{
				$msg="Successfully logged out";
			}
			
			session_destroy();
			session_start();
			$_SESSION['message']=$msg;
			header("location:./");
		}
//function for printing the photo 
		function put_photo()
		{
			if($this->user["photo"]!="")
			{
				echo "images/users/".$this->user["photo"];
			}
			else
			{
				echo "images/user.png";
			}
		}
// Uploading photo to "images/users/" and adding name of photo to 'user' table
		function upload_new_img()
		{
			if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg")) && ($_FILES["file"]["size"] < 2000000))
			{
				if ($_FILES["file"]["error"] > 0)
				{
					echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
				}
				else
				{
					$filename=$_FILES["file"]["name"];
    			    $img_name=$this->user['username'].rand(0000,9999);
    			    $file_ext = substr($filename, strripos($filename, '.'));
					$img_name.=$file_ext;							
    			    move_uploaded_file($_FILES["file"]["tmp_name"], "images/users/" . $img_name);						
					$id=$this->user['id'];
					$result=$this->db->query("UPDATE  `user` SET  `photo` =  '$img_name'  WHERE  `user`.`usr_id` =  '$id';  ;") or die("query error");
					$_SESSION['user']['photo']=$img_name;//seting session to refresh the basic variable values to reflect this change
				}
			}
			else
			{
				echo "Invalid file";
			}
		}
//an essential function
		static function create_ob()
		{
			switch($_SESSION['user']['profession'])
				{
					case "Student":     $usr_ob=new Student();
					break;
				//    case "Class Rep":   $usr_ob=new Class_Rep();
				//    break;
					case "Tutor":           $usr_ob=new Tutor();
					break;
					case "Class Tutor":  $usr_ob=new Class_Tutor();
					break;
					case "HOD":             $usr_ob=new Hod();
					break;
					case "Superior":        $usr_ob=new Superior();
					break;
					default: session_destroy();
								  die("unexpected error occured ,,,,,,!!!");	
				}
		return $usr_ob;			
		}
//this function is used for geting the source of the images of a definite person by id		
		function put_photo_by_id($photo)
		{
			if($photo!="")
			{
				return "images/users/".$photo;
			}
			else
			{
				return "images/user.png";
			}
		}

//uploading to library
		function new_book($bname, $author, $comment)
		{
			$allowedExtensions = array(".txt",".pdf",".pptx",".ppt",".jpeg",".jpg",".png");
			//array(".txt","csv","htm","html","xml","css",".doc","xls","rtf","ppt",".pdf","swf","flv","avi","wmv","mov","jpg","jpeg","gif","png");					
			$filename=$_FILES['file']['name'];
								
			if ($_FILES["file"]["size"] < 2000000)
			{
				if ($_FILES['file']['tmp_name'] > '') 
				{
					if (!in_array($filename=substr($filename, strripos($filename, '.')), $allowedExtensions)) 
					{
					die($filename.' is an invalid file type!<br/>'.'<a href="javascript:history.go(-1);">'.'&lt;&lt Go Back</a>');
					}
					else
					{
						echo "here it is";
						if ($_FILES["file"]["error"] === UPLOAD_ERR_OK )
						{
						
							$f_name=$this->user['username'].rand(0000,9999);
							$file_ext = substr($filename, strripos($filename, '.')); //geting extension
							$f_name.= $file_ext;							    			    
							move_uploaded_file($_FILES["file"]["tmp_name"], "./library/" . $f_name);	
							$sql="INSERT INTO `library` (`book`, `author`, `comment`, `id`, `file`, `posted_by`, `post_date`) 
									VALUES ('$bname', '$author', '$comment', NULL, '$f_name', '".$this->user["id"]."', '".date("y.m.d")."');";
							$result=$this->db->query($sql) or die("query error");
						}
						else
						{
							echo "Return Code: " . $_FILES["file"]["error"] . "<br />";							
						}
					}
				}
			}
			else
			{
				echo "Invalid file";
			}
		}
//used for changing settings				
		function save_changes($name,$mail_id,$mob)
		{
			$sql="UPDATE  `user` SET  `name` =  '$name',`mail_id` =  '$mail_id',`mob` =  '$mob' WHERE  `user`.`usr_id` =  '".$this->user['id']."';";
			$this->db->query($sql) or die("query error");
			$_SESSION['user']['name']=$name;
			$_SESSION['user']['mob']=$mob;
			$_SESSION['user']['mail_id']=$mail_id;
			echo "changes saved";
		}
//changing password				
		function change_password($oldp,$newpass)
		{
			$res=$this->db->query("SELECT `passwd` FROM `user` WHERE `usr_id` = '".$this->user['id']."'") or $this->logout();			
			if(mysqli_num_rows($res)==1)
			{
				$rpass=mysqli_fetch_assoc($res);
				$pass=$rpass['passwd'];						
				$result=$this->db->query("SELECT * FROM `user` WHERE `passwd` = '".crypt($oldp, $pass)."' AND `usr_id` = '".$this->user['id']."'") or die("query error");
				if(mysqli_num_rows($result)==1)
				{								
					$sql="UPDATE  `user` SET  `passwd` =  '".crypt($newpass)."' WHERE  `user`.`usr_id` =  '".$this->user['id']."';";
					$this->db->query($sql);							
					echo "changes saved";
				}
				else
				{
					if($_SESSION['pass_error']++>2)
					{
						$this->logout();
					}
				}
			}
		}
//Recovering password by sending a passcode to user's emailid				
		static function recover_passwd($username, $mail_id)
		{
			$msg="";
							//creating a passcode
			$chars = 'b0c9dfg2hj1klm7np3rs4tvw6xza8eio5u';
			$p_code="";
			for ($p = 0; $p < 10; $p++)
			{
				$p_code .= ($p%2) ? $chars[mt_rand(19, 33)] : $chars[mt_rand(0, 18)];
			}
		
			if ($db=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME)) 
			{
				
							
				$rslt=mysqli_query($db,"SELECT * FROM  `user` WHERE  `username` =  '$username' AND  `mail_id` =  '$mail_id'");
				if(mysqli_num_rows($rslt)==1)
				{
					$sql1="INSERT INTO recover_passwd (passcode, mail_id) VALUES ('$p_code', '$mail_id')";
					if(mysqli_query($db,$sql1)) 
					{ 
						//send mail
						$to      = "$mail_id";
						$subject = 'Password Recovery';
						$message = 'Use This code to recover your password  '.$p_code.'  click here "http://www.skooltimes.cu.cc/recover/?act=confirm"  to recover your password';
						$headers = 'From: admin@skooltimes.com' . "\r\n" .
							'Reply-To: rashivkp@gmail.com' . "\r\n" ;
						mail($to, $subject, $message, $headers);								
						$msg="Recovery message sent, check your mail";
					}
				}
				else
				{
					$msg="not a registered mail id or username";							
				}
			}
			$_SESSION['message']=$msg;
			
		}
//function for checking passcode to show password
		static function changing_passwd($pass, $mail_id, $newpass)		
		{
			
			if ($db=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME)) 
			{					
							
				$rslt=mysqli_query($db,"SELECT * FROM  `recover_passwd` WHERE  `passcode` =  '$pass' AND  `mail_id` =  '$mail_id'");
				if(mysqli_num_rows($rslt)==1)
				{		
					$sql1="UPDATE  `user` SET  `passwd` =  '".crypt($newpass)."' WHERE  `user`.`mail_id` =  '$mail_id';";
					if($rs=mysqli_query($db,$sql1)) 
					{ 
						
						$msg="Successfully changed your password";
						mysqli_query($db,"DELETE FROM recover_passwd WHERE  `passcode` =  '$pass' AND  `mail_id` =  '$mail_id'");
					}
				}
				else
				{
					$msg="error";							
				}
			}
			$_SESSION['message']=$msg;
		}
		function message()
		{
			$this->msg=new Message($this->db, $this->user);
			if(isset($_GET['id']))
			  {
				  if((isset($_POST['send'])) && $_POST['message']!="")//if button clicked
				  {
					  $this->msg->send($_POST['message'],$_GET['id']);
				  }  
				  $this->msg->show_message(); 
			  }
			  else
			  {
				  $this->msg->display();			  
			  }
		}
//noticeboard content will be displayed by this funtion					
		function put_noticeboard()
		{
			$result=$this->db->query("SELECT *FROM notification WHERE `type`='all' OR `type`='students'");
			while($row=$result->fetch_assoc())
			{
				echo "<div class='posts'>
						<div class='nbcaption'> "
							.$row['caption'].
						"</div>
						<div class='notice'>".
							$row['content'].
						"</div>
						 ";?>
						
		<aside>
					<div class='dat'>
						 posted on: <?=$row['post_date']?>
					</div>

</aside>
<hr /></div>
<?php
				
			}
		}
//printing details of library books and links
		function put_books()
		{
			$result=$this->db->query("SELECT *FROM library LIMIT 10");
			while($row=$result->fetch_assoc())
			{
				echo "<div class='libitem'>
						<div class='book'>".$row['book']."</div>
						<div class='author'><span class='author_title'>Author: </span>".$row['author']."</div>
						<div class='comment'><span class='comment_title'>Comment: </span>".$row['comment']."</div>
						<a class='libdownlink' href='./library/".$row['file']."'>Download</a>
					</div>";
				
			}
		}
//searching for books				
		function search_books($query)
		{
			$result=$this->db->query("SELECT DISTINCT * FROM  `library` WHERE  `book` LIKE  '%$query%' OR  `author` LIKE  '%$query%'");
			if($result->num_rows)
			{
				while($row=$result->fetch_assoc())
				{
					echo "<div class='libitem'>
						<div class='book'>".$row['book']."</div>
						<div class='author'><span class='author_title'>Author: </span>".$row['author']."</div>
						<div class='comment'><span class='comment_title'>Comment: </span>".$row['comment']."</div>
						<a class='libdownlink' href='./library/".$row['file']."'>Download</a>
					</div>";
				
				}
			}
			else
				echo "<div>no books found for your result</div><a href='./?action=lib'>go back..</a>";
		}
		
		
		
		/*subject */
		
		function put_subject($cls_id, $sub_id,$action)
		{
		//connect to sub_qstn table and retrieve latest 10 questions ..
			if(isset($_POST['new_qstn']))
				{
					$res=$this->db->query("INSERT INTO `sub_qstn` (`cls_id`, `sub_id`, `posted_id`, `qstn`, `post_date`, `qst_id`, `last_update`) 
								VALUES ('".$cls_id."',
								'".$sub_id."',
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
						<tr id="new_post">
						<form method="POST" action="<?=$action?>">
									<td><textarea cols="30" rows="5" name="qstn" /></textarea></td>
									<td><input type="submit" name="new_qstn" value="POST"></td>
									
						</form></tr>
					</tbody>
					</table>
				<?php
				
			$result=$this->db->query("SELECT * FROM sub_qstn WHERE cls_id='".$cls_id."' AND sub_id='".$sub_id."' ORDER BY  `last_update` DESC limit 10 ");
				$i=0;
				?>
				<ul class="post">
				<?php
				while($row=$result->fetch_assoc())
				{
					if($i++>10)
					exit;
					$p_id=$row['posted_id'];
					$result1=$this->db->query("SELECT * FROM user WHERE usr_id='".$p_id."'");
					$row1=$result1->fetch_assoc()
			?>
				<li>
				 <div class="posts">
				 
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
							$result2=$this->db->query("SELECT * FROM sub_ans WHERE qst_id='".$row['qst_id']."' ");
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
								<form action="<?=$action?>" method="POST">
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
		function put_sub_works($cls_id,$sub_id)
		{
			
			$qry="SELECT * FROM works WHERE cls='".$cls_id."' AND sub='".$sub_id."' ";
			if($result=$this->db->query($qry))
			{
				if($result->num_rows)
				{
					?>
					<ul class="subj_works">
							
			<?php			$i=1;
							while($row=$result->fetch_assoc())
							{
								echo "<li><div class='workitem'>
								
										
										<div class='caption'><img src='images/work.png' />".$row['caption']."</div>
										<div class='last_date'>Last date of submission: ".$row['last_date']."</div>
										<div class='post_date'> posted on: ".$row['post_date']." </div>
								</div></li><hr class='clearfloat' />";
										
							}
					echo "</ul><br class='clear' />";
				}
				else
					echo "<div class='sub_works'>There is no works</div>";
			}
		}
		function getheader_printmsg($msg)
		{
			$_SESSION['msg']=$msg;
			if(@$_SESSION['msg']!="")
			{
				
				
			}
			
					///
		}
		
			function apache_request_headers() 
			{
			  $arh = array();
			  $rx_http = '/\AHTTP_/';
			  foreach($_SERVER as $key => $val) {
				if( preg_match($rx_http, $key) ) {
				  $arh_key = preg_replace($rx_http, '', $key);
				  $rx_matches = array();
				  // do some nasty string manipulations to restore the original letter case
				  // this should work in most cases
				  $rx_matches = explode('_', $arh_key);
				  if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
					foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
					$arh_key = implode('-', $rx_matches);
				  }
				  $arh[$arh_key] = $val;
				}
			  }
			  return( $arh );
			}
			
			
		function put_home($cls_id,$limit)
		{
		$result=$this->db->query("SELECT * FROM sub_qstn WHERE cls_id='".$cls_id."' ORDER BY  `last_update` DESC LIMIT $limit ");
				$i=0;
				?>
				<ul class="post">
				<?php
				while($row=$result->fetch_assoc())
				{
					if($i++>10)
					exit;
					$p_id=$row['posted_id'];
					$result1=$this->db->query("SELECT * FROM user WHERE usr_id='".$p_id."'");
					$row1=$result1->fetch_assoc()
			?>
				<li>
				 <div class="posts">
				 
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
							$result2=$this->db->query("SELECT * FROM sub_ans WHERE qst_id='".$row['qst_id']."' ");
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
							
							</ul>
							
						
				</div><!-- posts div -->  
				<hr />
				</li>		  
			<?php
				}
				?>
				</ul>
		<?php
		}
		function put_colleg()
		{
		$result=$this->db->query("SELECT * FROM department WHERE clg_id='".$this->clg['id']."'");
								echo "<ul class='lstnone'>";
								while($dptf=$result->fetch_assoc())
								{
									$result1=$this->db->query("SELECT * FROM class WHERE dpt_id='".$dptf['dpt_id']."'");
									echo "<li><div class='college'>
									<div class='caption'>".$dptf['caption']."</div><ul class='lstnone'>";
									
									while($clsf=$result1->fetch_assoc())
									{
										$result2=$this->db->query("SELECT * FROM user WHERE usr_id='".$clsf['tut_id']."'");
										$usrf=$result2->fetch_assoc();
										echo "<li class='msg'><div class='author'><a href='classes/?id=".$usrf['username']."'>".$clsf['caption']."</a></div><li>";
									}
									echo "</div></li>";
								}
								echo "</ul>";
		}
}//class skooler

require "student_class.php";

require "hod_class.php";

require "superior_class.php";

require "tutor_class.php";

require "class_tutor_class.php";
      
    
?>
