<?php
class Hod extends Skooler
{
                public $dpt;// array name,id
				public $clg;//array name, id, sup_id
                public $sub;//array id,name,cls
                public $classes, $subordinates;
                
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
                    
                        //Retrieving the details of department to dpt array                       
                       if($result=$this->db->query("SELECT * FROM department WHERE dpt_hod_id='".$this->user['id']."' "))
                        {
                            if($result->num_rows==1)
                            {
                                $row=$result->fetch_assoc();
                                $this->dpt=array("id"=>$row['dpt_id'],"name"=>$row['caption']);         
                            }
                        }
                        //Retrieving the details of college to clg array
                        if($result=$this->db->query("SELECT * FROM college WHERE clg_id=(SELECT clg_id FROM department WHERE dpt_hod_id='".$this->user['id']."')"))
                        {
                            $row=$result->fetch_assoc();							
                            $this->clg=array("id"=>$row['clg_id'], "name"=>$row['caption'], "sup_id"=>$row['sup_id']);
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
                                
                    
                    
                
                   // $_SESSION['dpt']=$this->dpt;
                   // $_SESSION['clg']=$this->clg;
                            
                    
                }//fns get_details()
//printing details to main page
  function put_user_info()
				{
					echo "<table border='0'><tbody><tr><td>Name : </td><td>".$this->user['name']."</td></tr>
<tr><td>Profesion: </td><td>".$this->user['profession']."</td></tr>
<tr><td>Department: </td><td>".$this->dpt['name']."</td></tr></tbody></table>";
				}
								
//this function puts the choices

                function put_choices()
                    {
                        echo '<div id="CollapsiblePanel1" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">College</div>';
                          echo '<div class="CollapsiblePanelContent">';
                              echo '<a href="./?act=1">Superior</a><br />';
                              echo '<a href="./?act=2">HODs</a><br />';
                              echo '<a href="./?act=3">Departments and classes</a><br />';
                       echo "</div>";
                        echo '</div>';
                          echo '<div id="CollapsiblePanel2" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">My Department</div>';
                          echo '<div class="CollapsiblePanelContent">';
                          echo '<a href="./?act=5">Classes</a><br />';
                          echo '<a href="./?act=6">Subordinates</a><br />';
                          echo '</div></div>';
                          echo '<div id="CollapsiblePanel3" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">Subjects</div>';
                          echo '<div class="CollapsiblePanelContent">';
                          $i=1;
                          if($this->sub)
                          {
							  foreach($this->sub as $item)
							  {
								  echo '<a href="./?act=7&choice='.$i++.'">'.$item["name"].'</a><br />';
							  }
						  }
                          echo '</div></div>'; 
                       
                    }
 //-------------------------------------------------------------------
               function put_choice_contents()
                {
                    
                   switch($this->current['act']) 
                   {
                       case 1 : //act=1 when college-superior items choosed....
                                        require "hod/superior.php";
                                        
                        break;
                        //----------------------------------------------------------------------
                        case 2: // act=2 when hods choice is selected..
                                    require "hod/hod.php";                               
                        break; 
                        case 3: 
                        $this->put_colleg();
                        break;
                        //------------------------------------------------------------------------
                        case 5:require "hod/class_content.php";
                        break;
                        //------------------------------------------------------------------------
                        case 6: require "hod/subordinates.php";
                        break;
                        //------------------------------------------------------------------------
                        case 7: require "hod/subjects.php";
                        break;
                        //------------------------------------------------------------------------
                        default : 
								if($res2=$this->db->query("SELECT * FROM `class` WHERE `dpt_id`= '".$this->dpt["id"]."'"))
								while($f1=$res2->fetch_assoc())
								{
									$this->put_home($f1['cls_id'],3);
								}
							
						                 
                        
                    }
                    
                }
//printing additional choices
                function put_related_choices()
                {
                    echo '<br /><hr />';
                    switch($this->current['act'])
                    {
                        case 3://when Notice-classes selected
                                        require "hod/classes.php"; 
                                        break;
                        case 4://when notice-Department selected
                                    $i=0;
                                    foreach($this->dpt as $item)
                                    {
                                        echo '<a href="./?act=4&choice='.$i.'">'.$item['name'].'</a><br />';
                                        $i++;
                                    }
                                    break;
                        case 5://My dpt- classes
                                    echo '<a href="./?act=5&choice=new">Add new class</a>';
                                    
                        default:
                    }
                }
//this will print a header for corresponding action
                function put_choice_head()
                {
                    switch($this->current['act'])
                    {
                        case 1: echo "Superior"; break;
                        case 2: echo "HODs"; break;
                        case 5: echo "My Department-Classes"; break;
                        case 6: echo "My Department-Subordinates"; break;
                        case 7: $i=$_GET['choice']-1; echo $this->sub[$i]['name'] ;
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
}//class_hod
require "hod/send.php";
?>
