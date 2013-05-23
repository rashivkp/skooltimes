<?php
    if(isset($_POST['create_subject'])) //create button pressed, so inserting data to 'subject'
    {
        $tut=$_POST['tut_id'];
        $sub=$_POST['sub_name'];
        $cls=$this->cls['id'];
        $sql = "INSERT INTO `subject` (tut_id, cls_id, caption) VALUES ('$tut', '$cls', '$sub');";
        if($this->db->query($sql))
            $_SESSION['msg']="Created the subject successfully";
        else
            $_SESSION['msg']="some error occured during the process of creating subject";
    }

?>
<table>
<tbody>
    <tr><th>Subject Name</th><th>Subject Tutor</th></tr>
<?php 
    $cls_id=$this->cls['id'];
    $r=$this->db->query("SELECT * FROM subject WHERE cls_id='$cls_id'") or die("error query");
    while($f=$r->fetch_assoc())
    {
        $usr_id=$f['tut_id'];
        $r1=$this->db->query("SELECT * FROM user WHERE usr_id='$usr_id'") or die("error query");
        $f1=$r1->fetch_assoc();
        echo '<tr><td><img width="50" height="50" src="'.$this->put_photo_by_id($f1['photo']).'"/></td>
        <td><a href="#">'.$f['caption'].'</a></td><td><a href="./?action=msg&id='.$f1['username'].'">'.$f1['name'].'</a></td>
        <td><a href=./delete.php?act=tut&id='.$f['sub_id'].'><img src="./images/drop.png" /></a></td></tr>';
    }   
?>
    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<form action="./?act=5&choice=new" method="POST">
    <tr><td><input type="text" name="sub_name" id="sub_name" /></td>
            <td><select name="tut_id" id="tut_id" >
                        <?php $result=$this->db->query("SELECT tut_id FROM tutor WHERE dpt_id='".$this->dpt['id']."'");
                                    while($row=$result->fetch_assoc())
                                        {
                                           $r=$this->db->query("SELECT name FROM user WHERE usr_id='".$row['tut_id']."'");
                                            if($f=$r->fetch_assoc())
                                                echo "<option value='".$row['tut_id']."'>".$f['name']."</option>";                                            
                                        }            
                        ?></td>
    </tr>
    <tr><td><input type="submit" name="create_subject" value="create" /></td><td><input type="reset" value="Clear" /></td></tr>
</form>
</tbody>
</table>
