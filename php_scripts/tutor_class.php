<?php
class Tutor extends Skooler
{
                public $dpt;// array name,id,hod_id
				public $clg;//array name, id, sup_id
                public $sub;//array name, id, cls
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
						<tr><td>Department: </td><td>".$this->dpt['name']."</td></tr>
						<tr><td>College: </td><td>".$this->clg['name']."</td></tr></tbody></table>";
				}
//this function puts the choices
                function put_choices()
                    {
                        echo '<div id="CollapsiblePanel1" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">College</div>';
                          echo '<div class="CollapsiblePanelContent">';
                              echo '<a href="./?act=1">Superiors</a><br />';
                              echo '<a href="./?act=2">Colleages</a><br />';
                              echo '<a href="./?act=4">Department and classes</a><br />';
                       echo "</div>";
                        echo '</div> <div id="CollapsiblePanel2" class="CollapsiblePanel">
                          <div class="CollapsiblePanelTab" tabindex="0">Subjects</div>';
                          echo '<div class="CollapsiblePanelContent">';
                          $i=1;
                          if($this->sub)
						  {
							  foreach($this->sub as $item)
							  {
								  echo '<a href="./?act=3choice='.$i++.'">'.$item["name"].'</a><br />';
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
									echo'<ul class="users_list">';
										$rst=$this->db->query("SELECT * FROM user WHERE usr_id='".$row['dpt_hod_id']."'") or die("not success");
										while($f=$rst->fetch_assoc())
										{
											
								echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>"; 
									
										}
										echo '</ul>';
								}
                                        
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
                        //------------------------------------------------------------------------
                        case 3: //act=3 when any subject is choosed
								require "tutor/subject.php";
                        
                        break;
                        //------------------------------------------------------------------------
                        case 4: 
                       $this->put_colleg();
                        break;
                        default : if($res2=$this->db->query("SELECT * FROM `class` WHERE `dpt_id`= '".$this->dpt["id"]."'"))
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
                        default:
                    }
                }
//this will print a header for corresponding action
                function put_choice_head()
                {
                    switch($this->current['act'])
                    {
                        case 1: echo "Superiors"; break;
                        case 2: echo "Colleages"; break;
                        case 3: $tmp=$_GET['choice']-1;
								echo $this->sub[$tmp]["name"]; break;                        
                        default: echo "HOME";
                    }
                }

				
//puting menu for the notification section
                function put_nb_menu()
                {
				?>
					<li><a href="./">Home</a></li>
					<li><a class="<?php if(@$_GET['act']=="")echo "current"; ?>" href="./?action=nb">Notifications</a></li>
				<?php
				}

}//class_hod
?>
