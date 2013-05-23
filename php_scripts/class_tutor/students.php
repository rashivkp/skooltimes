<?php 
if(isset($_POST['new_stud']))
{
	$this->send_inv_by_classtutor();
}
else if(isset($_GET['del']))
{
	$this->db->query("DELETE FROM `student` WHERE std_id='".$_GET['del']."'");
	$this->db->query("DELETE FROM `sub_qstn` WHERE posted_id='".$_GET['del']."'");
	$this->db->query("DELETE FROM `sub_ans` WHERE posted_id='".$_GET['del']."'");
	$this->db->query("DELETE FROM `user` WHERE usr_id='".$_GET['del']."'");
	header("location:./?act=4");
	echo "Deleted Successfully";
	
}
?>

<table>
<tbody>
<tr><th>&nbsp;</th><th>&nbsp;</th><th>Contact NO.</th></tr>
<?php
$result=$this->db->query("SELECT std_id FROM student WHERE cls_id='".$this->cls['id']."'") or die("error conn");
    
    while($row=$result->fetch_assoc())
        {
			$sql="SELECT * FROM user WHERE usr_id='".$row['std_id']."' ";
			$result1=$this->db->query($sql);
			$row1=$result1->fetch_assoc();
			echo "<tr>
			<td><img width='50' height='50' src='".$this->put_photo_by_id($row1['photo'])."'/></td>
			<td><a href='./?action=msg&id=".$row1['username']."'>".$row1['name']."</a></td>
			<td>".$row1['mob']."</td>
			<td><a href=./delete.php?act=stud&id=".$row['std_id']."><img src='./images/drop.png' /></a></td></tr>";
		}            
?>
</form>
</tbody>
</table>

<table>
  <tbody>
    <tr><th>Name<th>Mail id</th></td><th>&nbsp;</th></tr>
  <form action="./?act=4" method="POST" >
    <tr>
      <td>
        <input type="text" name="name" id="name" />
      </td>
      <td>
      <input type="text" name="mail_id" id="mail_id" />
      </td>
      <input type="hidden" name="profession" id="profession" value="Student" />
      <input type="hidden" name="info" id="info" value='<?php echo $this->cls['id']; ?>' />
      <input type="hidden" name="dpt" id="dpt" value='<?php echo $this->dpt['id']; ?>' />
      <td><input type="submit" name="new_stud" id="new_stud" value="invite new student" /> </td>
    </tr>
  </form>
  </tbody>
</table>
