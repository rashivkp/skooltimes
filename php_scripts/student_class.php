<?php
class Student extends Skooler
{
                public $sub;//array [0,1..] caption,id,tut_id               
                public $cls;//array name,id,tut_id 
                public $dpt;//array name, id, hod_id
                
                //constructor 
                function __construct()
                {
                        parent::__construct();
                        if(isset($_SESSION['cls']))
                        {
                            $this->cls=$_SESSION['cls'];
                            $this->dpt=$_SESSION['dpt'];
                            $this->sub=$_SESSION['sub'];
                        }
                        else
                            $this->get_details();
                       
                } //close constructor student
                //___________________________________________________________________
               
                //function for geting the details needed for printing on main page of student.
                function get_details()
                {
                    //geting details from the class table seting $this->cls
                    $usr_id=$this->user['id'];
                     if($result=$this->db->query("SELECT * FROM class WHERE cls_id=(SELECT cls_id FROM student WHERE std_id='$usr_id')"))
                        {
                            if($result->num_rows==1)
                            {
                                $row=$result->fetch_assoc();
                                $this->cls=array("id"=>$row['cls_id'],"name"=>$row['caption'],"tut_id"=>$row['tut_id']);
                            }
                        }
                    //reading details of subjects seting $this->sub
                    $cls=$this->cls['id'];
                    $result=$this->db->query("SELECT * FROM subject WHERE cls_id='$cls'") or die("conn err");
                    while($f=$result->fetch_assoc())
                    {
                        $this->sub[]=array("name"=>$f['caption'], "id"=>$f['sub_id'],"tut_id"=>$f['tut_id']);
                    }
                    
                    //geting details from department table to set the dpt array
                    $result=$this->db->query("SELECT * FROM department WHERE dpt_id=(SELECT dpt_id FROM class WHERE cls_id='".$this->cls['id']."')") or die("conn err");
                    if($f=$result->fetch_assoc())
                    {
                        $this->dpt=array("name"=>$f['caption'], "id"=>$f['dpt_id'],"hod_id"=>$f['dpt_hod_id']);
                    }
                   //geting from college
                    $result=$this->db->query("SELECT * FROM college WHERE clg_id=(SELECT clg_id FROM department WHERE dpt_id='".$this->dpt['id']."')") or die("conn err");
                    if($f=$result->fetch_assoc())
                    {
                        $this->clg=array("name"=>$f['caption'], "id"=>$f['clg_id'],"sup_id"=>$f['sup_id']);
                    }
                }//fns get_details()
                //______________________________________________________________________________________
                
//printing details to main page
            function put_user_info()
				{
					echo "<table class='white' border='0'><tbody><tr><td>Name : </td><td>".$this->user['name']."</td></tr>
						  <tr><td>Profesion: </td><td> ".$this->user['profession']."</td></tr>
						<tr><td>class: </td><td>".$this->dpt['name']."  ".$this->cls['name']."</td></tr>
						<tr><td>College: </td><td>".$this->clg['name']."</td></tr></tbody></table>";
				}                
                
                //this used to print the left pane of student_main and lists all the choices available for a student
				function put_choices()
				{
					require "./php_scripts/student/choice.php";
				}
//this function puts the choices and the contents of options in right pane of student mainpage
                function put_choice_contents()
                {
                   switch($this->current['act']) 
                   {
                       case 1 : //act=1 when subject items choosed....
                       $i=1;
                       foreach($this->sub as $item)
                                          {
                                              echo '<a class="msg" href="./?act=1&choice='.$i++.'">'.$item['name']."</a><br />";
                                          }
                                       require "student/subject.php";
                        break;
                        //----------------------------------------------------------------------
						case 2: // act=2 tutor
							echo '<ul class="users_list">';
							$sql="SELECT tut_id FROM tutor WHERE dpt_id='".$this->dpt['id']."'";
							$rslt=$this->db->query($sql);
							for($i=1; $i<=$rslt->num_rows; $i++)
							{
								$row=$rslt->fetch_assoc();
								$sql="SELECT * FROM user WHERE usr_id='".$row['tut_id']."' ";
								$result1=$this->db->query($sql);
								$f=$result1->fetch_assoc();
								
								echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>"; 

							}
							echo "</ul>";
                        break;
                        //------------------------------------------------------------------------
                        case 3: //act=3 classmates 
							echo '<ul class="users_list">';							
							$sql="SELECT std_id FROM student WHERE cls_id='".$this->cls['id']."' AND std_id!='".$this->user["id"]."' ";
							$rslt=$this->db->query($sql);
							for($i=1; $i<=$rslt->num_rows; $i++)
							{
								$row=$rslt->fetch_assoc();
								$sql="SELECT * FROM user WHERE usr_id='".$row['std_id']."' ";
								$result1=$this->db->query($sql);
								$f=$result1->fetch_assoc();
								
								echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>"; 
								
							}
							echo "</ul>";
							break;
						case 4: 
								$this->put_colleg();
                        
                        break;
                        //------------------------------------------------------------------------
                        default : $cls_id=$this->cls['id'];
									$this->put_home($cls_id,10);
                        
                    }
                }
                //__________________________________________________________________________________________
                function put_related_choices()
                {
                         }
//choosing the headings
                function put_choice_head()
                {
                    switch($this->current['act'])
                    {
                        case 1: if(@$_GET['choice']){ $i=$_GET['choice']-1; echo $this->sub[$i]['name'];} else {echo "Subject"; } break;
                        case 2: echo "Tutors"; break;
                        case 3: echo "Class Mates"; break;
                        case 4: echo "College"; break;
                        default: echo "Home";
                    }
                }
                
                function __destruct()
                {
                    $this->db->close();
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
				<?php
				}

}//class student
?>
