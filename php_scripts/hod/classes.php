<?php
//printing the related choices for the dpt-class and nb-classes
$result=$this->db->query("SELECT cls_id, caption FROM class WHERE dpt_id='".$this->dpt['id']."'");
if($this->current['act']==5)
{
    while($row=$result->fetch_assoc())
        {
            echo '<a href="./?act=5&choice='.$row['cls_id'].'">'.$row['caption'].'</a><br />';
        }
    
}
    ?>
    
