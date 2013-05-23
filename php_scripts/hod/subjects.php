<?php //handles the subject which is taking by class tutor user
if($this->current['choice'])
{
    if(!(isset($this->current['opt'])))
    $this->current['opt']="Works";
    $subject_opts=array("Works","Posts","Others");
    echo '<ul id="class_sub_choice">';

    foreach($subject_opts as $item)
    {
        if($item===$this->current['opt'])
                echo '<li ><a class="active_opt" href="#">'.$this->current['opt'].'</a></li>';
        else
                echo '<li><a href="./?act=7&choice='.$this->current['choice'].'&opt='.$item.'">'.$item.'</a></li>';
    }
      echo '</ul> <br /><br /><br />';
      $tmp=$this->current['choice']-1;//this is for using with the $this->sub array, its starts from zero..
                  switch($this->current['opt'])
                  {
                      case "Works" :  	
										if(@$_POST['new_work'])
										{
											$qry="INSERT INTO `works` (`cls`, `sub`, `caption`, `post_date`, `last_date`, `wrk_id`) VALUES 
											('".$this->sub["$tmp"]["cls"]."', '".$this->sub["$tmp"]['id']."', '".$_POST['work']."', '".date("y.m.d")."', '".$_POST['ldate']."', '');";
											if($result=$this->db->query($qry))
												$_SESSION['msg']="Work added";echo $_SESSION['msg'];$_SESSION['msg']="";
										
											
										}
										
										$this->put_sub_works($this->sub["$tmp"]["cls"],$this->sub["$tmp"]["id"]);
										
										echo "<h2>Add New Work</h2><table>
										<form action='./?act=7&choice=".$this->current['choice']."' method='POST'>
												<tr><td>Work Details</td><td><textarea cols='30' rows='2' name='work'></textarea></td></tr>
												<tr><td>Last Date</td><td><input type='date' name='ldate' /></td></tr>												
												<tr><td>&nbsp;</td><td><input type='submit' name='new_work' /></td></tr>									
											</form></table>";
                      break;
                      case "Posts" : 
									
									$action="index.php?act=7&choice=".$this->current['choice']."&opt=Posts";
									$this->put_subject($this->sub["$tmp"]["cls"],$this->sub["$tmp"]['id'],$action);
								
                      break;
                      case "Others" : echo "this may include any interests of your tutor";
                      break;
                      default: echo "invalid option, dont edit your own options ";                           
                    }           
            
        
}

?>
