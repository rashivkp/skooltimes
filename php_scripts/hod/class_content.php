<?php
//dpt-class contents
if(@$this->current['choice']!='new')
{
    $res=$this->db->query("SELECT * FROM `class` WHERE `dpt_id`= '".$this->dpt['id']."'");
	$i=1;
	echo '<table cellpadding="10"><tbody>';
	while($row=$res->fetch_assoc())
	{
		if($res1=$this->db->query("SELECT * FROM `user` WHERE `usr_id`= '".$row['tut_id']."'"))
		{
			$row1=$res1->fetch_assoc();
			echo '<tr><td><a href="./classes/?id='.$row1['username'].'">'.$row['caption']."</a></td><td>".$row1['name']."</a></td><td><a href=./delete.php?act=cls&id=".$row['cls_id']."><img src='./images/drop.png' /></a></td></tr>";
		}
	}
	echo '</tbody></table>';
}
else if(@$_GET['choice']=='new')
{
    if(isset($_POST['create_class']) && $_POST['tut_id']!="" && $_POST['cls_name']!="" ) //create button pressed, so inserting data to class, and upadating user type to 'Class Tutor'
    {
        $cls=$_POST['cls_name'];
        $tut=$_POST['tut_id'];
        $dpt_id=$this->dpt['id'];
        $sql = "INSERT INTO `class` (`cls_id`, `caption`, `tt_id`, `tut_id`, `dpt_id`, `rep_id`, `course`) VALUES (NULL, '$cls', '', '$tut', '$dpt_id', '', '');";
        $sql1="UPDATE  `user` SET  `type` =  'Class Tutor' WHERE  `user`.`usr_id` =  '$tut'";
        if($this->db->query($sql) && $this->db->query($sql1))
            $_SESSION['msg']="Created the class successfully";
        else
            $_SESSION['msg']="some error occured during the process of creating class";        
    }
?>
    <table>
    <tbody>
    <form action="./?act=5&choice=new" method="POST">
        <tr><th>Class Name</th><th>Class Tutor</th></tr>
        <tr><td><input type="text" name="cls_name" id="cls_name" /></td>
                <td><select name="tut_id" id="tut_id" >
                            <?php $result=$this->db->query("SELECT tut_id FROM tutor WHERE dpt_id='".$this->dpt['id']."'");
                                        while($row=$result->fetch_assoc())
                                            {
                                               $r=$this->db->query("SELECT name FROM user WHERE type='Tutor' AND usr_id='".$row['tut_id']."'");
                                                if($f=$r->fetch_assoc())
                                                    echo "<option value='".$row['tut_id']."'>".$f['name']."</option>";
                                                
                                            }            
                            ?></td>
        </tr>
        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
        <tr><td><input type="submit" name="create_class" value="create" /></td><td><input type="reset" value="Clear" /></td></tr>
    </form>
    </tbody>
    </table>

<?php
}
else
    echo "please choose a class from sidepane to administrate";
?>
