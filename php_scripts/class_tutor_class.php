<?php
class Class_Tutor extends Skooler
{
                public $dpt;// array name,id,hod_id
				public $clg;//array name, id, sup_id
                public $cls;//array name,id,rep_id
                public $sub;//array name, id, cls
                public $subordinates;
                
                function __construct()
                    {                        
                        parent::__construct();
                        if(isset($_SESSION['dpt']))
                        {
                            $this->dpt=$_SESSION['dpt'];
                            $this->clg=$_SESSION['clg'];
                        }
                        else
                        {
                            $this->get_details();
                        }
                            

                    }
//geting details from various tables, and store them in sessions
                function get_details()
                {          
                    //geting details from the class table ...set $cls array
                     if($result=$this->db->query("SELECT * FROM class WHERE tut_id='".$this->user['id']."' "))
                        {
                            if($result->num_rows==1)
                            {
                                $row=$result->fetch_assoc();
                                $this->cls=array("id"=>$row['cls_id'],"name"=>$row['caption'],"rep_id"=>$row['rep_id']);         
                            }
                        }
                    //geting details from the department table for set dpt array
                     $clg=0;
                     if($result=$this->db->query("SELECT * FROM department WHERE dpt_id=(SELECT dpt_id FROM tutor WHERE tut_id='".$this->user['id']."') "))
                        {
                            if($result->num_rows==1)
                            {
                                $row=$result->fetch_assoc();
                                $this->dpt=array("id"=>$row['dpt_id'],"name"=>$row['caption'],"hod_id"=>$row['dpt_hod_id']);         
                                $clg=$row['clg_id'];
                            }
                        }
                    //geting details from the college table...set $clg array
                     if($result=$this->db->query("SELECT * FROM college WHERE clg_id='$clg'"))
                        {
                            if($result->num_rows==1)
                            {
                                $row=$result->fetch_assoc();
                                $this->clg=array("id"=>$row['clg_id'],"name"=>$row['caption'],"sup_id"=>$row['sup_id']);         
                             }
                        }
                    //geting details from the college table...set $sub array
                    $usr=$this->user["id"];
                     if($result=$this->db->query("SELECT * FROM subject WHERE tut_id='$usr'"))
                        {
                            while($row=$result->fetch_assoc())
                            {
                                $this->sub[]=array("id"=>$row['sub_id'],"name"=>$row['caption'],"cls"=>$row['cls_id']);         
                            }
                        }
                        
                }//fns get_details()

//printing details to main page
function put_user_info()
				{
					echo "<table border='0'><tbody><tr><td>Name : </td><td>".$this->user['name']."</td></tr>
							<tr><td>Profesion: </td><td>".$this->user['profession']."</td></tr>
							<tr><td>Class : </td><td> ".$this->dpt['name']."  ".$this->cls['name']."</td></tr></tbody></table>";
				}
//this function puts the hoices
                function put_choices()
                    {
                        echo '<div id="CollapsiblePanel1" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">College</div>';
                          echo '<div class="CollapsiblePanelContent">';
                              echo '<a href="./?act=1">Superiors</a><br />';
                              echo '<a href="./?act=2">Colleages</a><br />';
                              echo '<a href="./?act=3">Departments and classes</a><br />';
                       echo '</div>';
                        echo '</div>';
                        echo '<div id="CollapsiblePanel2" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">My Class</div>';
                          echo '<div class="CollapsiblePanelContent">';
                          echo '<a href="./?act=4">Students</a><br />';
                          echo '<a href="./?act=5">Tutors</a><br />';
                          echo '<a href="./classes/?id='.$this->user['username'].'">Goto class..</a><br />';
                          echo '</div></div>';
 
                        echo '<div id="CollapsiblePanel3" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">My Subjects</div>';
                          echo '<div class="CollapsiblePanelContent">';
                          $i=1;
                          if($this->sub)
                          {
							  foreach($this->sub as $item)
							  {
								  echo '<a href="./?act=6&choice='.$i++.'">'.$item["name"].'</a><br />';
							  }
						  }
                          echo '</div></div>'; 
                                    
                    }
 //-------------------------------------------------------------------
               function put_choice_contents()
                {
                    /*echo @$_SESSION['msg'];@$_SESSION['msg']="";*/
                   switch($this->current['act']) 
                   {
                       case 1 : //act=1 when college-superior items choosed....
								//required when the superior choice selected

									$result=$this->db->query("SELECT * FROM user WHERE usr_id='".$this->clg['sup_id']."'") or die("not success");
									$f=$result->fetch_assoc();
								?>
								<ul class="users_list">
								<?php
													echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>"; 
								
                        $result=$this->db->query("SELECT dpt_hod_id FROM department WHERE clg_id='".$this->clg['id']."'") or die("not success");
						while($row=$result->fetch_assoc())
						{
							
								$rst=$this->db->query("SELECT * FROM user WHERE usr_id='".$row['dpt_hod_id']."'") or die("not success");
								while($f=$rst->fetch_assoc())
								{
										echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>"; 
								
								}
								
						}
						echo '</ul>';
						          
                        break;
                        //----------------------------------------------------------------------
                        case 2: // act=2 when colleages choice is selected..
                        
						$result=$this->db->query("SELECT tut_id FROM tutor WHERE dpt_id='".$this->dpt['id']."'") or die("not success");
						while($row=$result->fetch_assoc())
						{
							echo'<ul class="users_list">';
								$rst=$this->db->query("SELECT * FROM user WHERE usr_id='".$row['tut_id']."'") or die("not success");
								while($f=$rst->fetch_assoc())
								{
										echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>"; 
								}
								echo '</ul>';
						}
					
											break;
						case 3: 
                        $this->put_colleg();
                        break;
                        //------------------------------------------------------------------------
                        case 4: require "class_tutor/students.php";
                        break;
                        //------------------------------------------------------------------------
                        case 5: require "class_tutor/tutor.php";
                        break;
                        //------------------------------------------------------------------------                        
                        case 6: require "class_tutor/subject.php";
                        break;
                        //------------------------------------------------------------------------
                        default : 	$cls_id=$this->cls['id'];
									$this->put_home($cls_id,10);
								
                    }
                }
//printing additional choices
                function put_related_choices()
                {
                    echo '<br /><hr />';
                    switch($this->current['act'])
                    {
                        default: echo "";                    }
                }
//this will print a header for corresponding action
                function put_choice_head()
                {
                    switch($this->current['act'])
                    {
                        case 1: echo "Superiors"; break;
                        case 2: echo "Colleages"; break;
                        case 3: echo "Notice Board-My Class"; break;
                        case 4: echo "Class-Students"; break;
                        case 5: echo "Class-Tutors"; break;
                        case 6: $i=$_GET['choice']-1; echo $this->sub[$i]['name'] ;
                        break;
                        default: echo "Home";
                    }
                }
                
//posting new notification on notice board , so inserting to notification table
				function new_notice($title,$notice,$for)
				{
					$sql="INSERT INTO `notification` (`id`, `caption`, `content`, `posted_by`, `post_date`, `type`) 
													VALUES (NULL, 
													'$title', 
													'$notice',
													'".$this->user["id"]."',
													'".date("y.m.d")."', 
													'$for');";
					$this->db->query($sql);
				}

//puting menu for the notification section
                function put_nb_menu()
                {
				?>
					<li><a href="./">Home</a></li>
					<li><a class="<?php if(@$_GET['act']=="")echo "current"; ?>" href="./?action=nb">Notifications</a></li>
					<li><a class="<?php if(@$_GET['act']=="new") echo "current"; ?>" href="./?action=nb&act=new">New Notification</a></li>
				<?php
				}				
//function for inviting				
				function send_inv_by_classtutor()
				{
					$usr_id=$_SESSION['user']['id'];
					function random($length = 10)
					{     
						$chars = 'b0c9dfg2hj1klm7np3rs4tvw6xza8eio5u';
					   $result="";
						for ($p = 0; $p < $length; $p++)
						{
							$result .= ($p%2) ? $chars[mt_rand(19, 33)] : $chars[mt_rand(0, 18)];
						}
					   
						return $result;
					}
					//retrieving data send by form
						$c_name=$_POST['name']; 
						$mail_id=$_POST['mail_id'];
						$prof=$_POST['profession'];
						$info=$_POST['info'];
						if(isset($_POST['dpt']))
							$dpt=$_POST['dpt'];
						else
							$dpt=0;
						$p_code=random(); //PASSCODE GENERATED

					//saving the mail_id and secret_code in db
					$sql="INSERT INTO invited (mail, code, profession, c_name, inv_by, dpt, info) VALUES ('$mail_id', '$p_code', '$prof', '$c_name','$usr_id', '$dpt', '$info')";
					if($this->db->query($sql)) 
					{ 
							//send mail
							$to      = $mail_id;
							$subject = 'SkoolTimes invitation';
							$message = 'welcome to skooltimes ,
							hi, '.$c_name.'  we are providing great service to improve the knowledge,,, we hope this will be helpful
							to you ...      use this secret code to signup :  '.$p_code.'click here to register : http\/\/www.skooltimes.cu.cc/register/';
							$headers = 'From: admin@skooltimes.com' . "\r\n" .
								'Reply-To: rashivkp@gmail.com';
							mail($to, $subject, $message, $headers);
							echo "Invited Successfully";
								
					}
					else
					{
						die("error occured during the process of connect to db");
					} 	
				}
					
}//class_hod
?>
