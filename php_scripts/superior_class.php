<?php
class Superior extends Skooler
{                
public $clg;//array name, address, id,
public $dpt;//array[] =>- name, id, hod
                function __construct()
                    {                     
                        parent::__construct();
                           
                      /*  if(isset($_SESSION['dpt']))
                        {
                            $this->dpt=$_SESSION['dpt'];
                        }
                        else
                        {*/
                            //geting details from the college table
                            $this->get_details();
                        //}
                            
                    }
             //-----------------------------------------------------------------------
             //geting details for superior
             function get_details()
             {
				 //setting the clg array			
				 if($r=$this->db->query("SELECT * FROM  `college` WHERE  `sup_id` ='".$this->user['id']."'"))
				 {
					 $row=$r->fetch_assoc();
					 $this->clg=array('name'=>$row['caption'], 'id'=>$row['clg_id'], 'address'=>$row['address'], 'country'=>$row['country']);
				 }
					//setting the dpt array 
				 if($r=$this->db->query("SELECT * FROM `department` WHERE clg_id ='".$this->clg['id']."' "))
				 {
					 for($i=0; $i<$r->num_rows; $i++)
					 {
						 $row=$r->fetch_assoc();
						 $this->dpt[]=array('name'=>$row['caption'], 'id'=>$row['dpt_id'], 'hod'=>$row['dpt_hod_id']);
						 $_SESSION['dpt']=$this->dpt;
					 }
				 }
						
             }
             function put_choices()
                    {
                        echo '<div id="CollapsiblePanel1" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">Department</div>';
                          echo '<div class="CollapsiblePanelContent">';
							  $i=1;
							  if($this->dpt)
							  {
								  foreach(@$this->dpt as $item)
								  {
									  echo '<a href="./?act=1&choice='.$i++.'">'.$item["name"].'</a><br />';
								  }
							  }
                              echo '<a href="./?act=2">Add a Department</a><br />';
                       echo "</div>";
                        echo '</div>';
                       
                    }
           
//printing details to main page
            function put_user_info()
				{
					echo "<table border='0'><tbody><tr><td>Name : </td><td>".$this->user['name']."</td></tr>
						<tr><td>Profesion: </td><td>".$this->user['profession']."</td></tr>
						<tr><td>College: </td><td>".$this->clg['name']."</td></tr></tbody></table>";
				}
//-------------------------------------------------------------------					
                function put_choice_contents()
               {
                                      
                   switch($this->current['act']) 
                   {
                       case 1 : //act=1 Department items choosed....
                                       $tmp=$this->current['choice']-1;
                                        $dpt_opts=array("Staff","Classes");
                                        echo '<ul id="class_sub_choice">';
                                        if(!(isset($this->current['opt']))) $this->current['opt']="Staff";
                                        foreach($dpt_opts as $item)
                                        {
                                            if($item===$this->current['opt'])
                                                    echo '<li ><a class="active_opt" href="#">'.$this->current['opt'].'</a></li>';
                                            else
                                                    echo '<li><a href="./?act=1&choice='.$this->current['choice'].'&opt='.$item.'">'.$item.'</a></li>';
                                        }
                                          echo '</ul> <br /><br /><br />';
                                          
                                      switch($this->current['opt'])
                                      {
                                          case "Staff" :                                           
														echo '<ul class="users_list">';
														if($res1=$this->db->query("SELECT * FROM `user` WHERE `usr_id`= '".$this->dpt["$tmp"]['hod']."'"))
														{
															$f=$res1->fetch_assoc();
															echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
																	<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a><br />Profession: ".$f['type']."</div></div></li>"; 	

														}
														$res=$this->db->query("SELECT * FROM `tutor` WHERE `dpt_id`= '".$this->dpt["$tmp"]['id']."'");
														
														while($row=$res->fetch_assoc())
														{
															if($row['tut_id']==$this->dpt["$tmp"]['hod']) continue;
															if($res1=$this->db->query("SELECT * FROM `user` WHERE `usr_id`= '".$row['tut_id']."'"))
															{
																$f=$res1->fetch_assoc();
																echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
																	<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a><br />Profession: ".$f['type']."</div></div></li>"; 
        
															}
														}
														echo '</ul>';
                                          break;
                                          case "Classes" : 
														$res=$this->db->query("SELECT * FROM `class` WHERE `dpt_id`= '".$this->dpt["$tmp"]['id']."'");
														$i=1;
														while($row=$res->fetch_assoc())
														{
															if($res1=$this->db->query("SELECT * FROM `user` WHERE `usr_id`= '".$row['tut_id']."'"))
															{
																$row1=$res1->fetch_assoc();
																echo '<a href="./classes/?id='.$row1['username'].'">'.$row['caption']." ".$row1['name']."</a><br />";
															}
														}
                                          break;
                                          default: echo "invalid option, dont edit your own options ";                           
                                        }           
                                                
                                            
                        break;
                        //----------------------------------------------------------------------
                        case 2: // act=2 when Invite choices is selected..
                        
                                        $inv_opts=array("HODs");
                                        echo '<ul id="class_sub_choice">';
                                        if(!(isset($this->current['opt']))) $this->current['opt']="HODs";
                                        foreach($inv_opts as $item)
                                        {
                                            if($item===$this->current['opt'])
                                                    echo '<li ><a class="active_opt" href="#">'.$this->current['opt'].'</a></li>';
                                            else
                                                    echo '<li><a href="./?act=2&choice='.$this->current['choice'].'&opt='.$item.'">'.$item.'</a></li>';
                                        }
                                          echo '</ul> <br /><br /><br />';
                                          switch($this->current['opt'])
                                      {
                                          case "HODs" : $_SESSION['opt_profession']="HOD";
                                          break;
                                          case "Class Tutors" : $_SESSION['opt_profession']="Class Tutor";
                                          break;
                                          case "Tutors" : $_SESSION['opt_profession']="Tutor";
                                          break;                            
                                          default: echo "invalid option, dont edit your own options ";                           
                                        }
                                        require "superior/invite.php";
                                  
                        break;
                        //------------------------------------------------------------------------
                        default : 
                        if($res1=$this->db->query("SELECT * FROM `department` WHERE `clg_id`= '".$this->clg['id']."'"))
						{
							while($f=$res1->fetch_assoc())
							{
								
								if($res2=$this->db->query("SELECT * FROM `class` WHERE `dpt_id`= '".$f["dpt_id"]."'"))
								{
									while($f1=$res2->fetch_assoc())
									{
									$this->put_home($f1['cls_id'],2);
									}
								}
							}
						}                    
                        
                    }//switch
                    
                }
//printing additional choices
                function put_related_choices()
                {
                    echo '<br /><hr />';
                    switch($this->current['act'])
                    {
                        case 1://when College-Department selected
                                    break;
                        case 2://when Invite selected
                                    echo "Invite helping you to enlarge your community";
                                    break;
                        case 3://when Notice-college selected
                                        echo '<a href="./?act=3&choice=1">All Students</a><br />';
                                        echo '<a href="./?act=3&choice=2">All HODs</a><br />';
                                        echo '<a href="./?act=3&choice=3">All Staffs</a><br />';       
                                        break;
                        case 4://when notice-Department selected
                                    break;
                        default:
                    }
                }
//this will print a header for corresponding action
                function put_choice_head()
                {
                    switch($this->current['act'])
                    {
                        case 1: $i=$_GET['choice']-1; echo $this->dpt[$i]['name']; break;
                        case 2: echo "Invite"; break;
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

                
}//class
require "superior/send.php";
?>
