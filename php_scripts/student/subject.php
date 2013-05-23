<?php //student's subject items choosed...
if($this->current['choice']!=0)
{
    if(!(isset($this->current['opt'])))
    $this->current['opt']="Works";
    $subject_opts=array("Works","Posts");
    echo '<ul id="class_sub_choice">';

    foreach($subject_opts as $item)
    {
        if($item===$this->current['opt'])
                echo '<li ><a class="active_opt" href="#">'.$this->current['opt'].'</a></li>';
        else
                echo '<li><a href="./?act=1&choice='.$this->current['choice'].'&opt='.$item.'">'.$item.'</a></li>';
    }
      echo '</ul> <br /><br /><br />';
      $tmp=$this->current['choice']-1;//this is for using with the $this->sub array, its starts from zero..
                  switch($this->current['opt'])
                  {
                      case "Works" : 	
									$this->put_sub_works($this->cls["id"],$this->sub["$tmp"]["id"]);      
                      break;
                      case "Posts" : 
									$action="./?act=1&choice=".$this->current['choice']."&opt=Posts";
									$this->put_subject($this->cls["id"],$this->sub["$tmp"]['id'],$action);
                      break;
                      default: echo "invalid option, dont edit your own options ";                           
                    }           
            
        
}
?>
