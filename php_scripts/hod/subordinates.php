<?php
if(isset($_POST['new_sub']))
{
	if($_POST['new_sub']!="" && $_POST['mail_id']!="")
	send_invitation_by_hod();
	else
	 echo "<div clas='msg'>enter details</div>";
}
?>

<ul class="users_list">
<?php
$result=$this->db->query("SELECT tut_id FROM tutor WHERE dpt_id='".$this->dpt['id']."' AND tut_id!='".$this->user["id"]."'");
    while($row=$result->fetch_assoc())
        {
            $r=$this->db->query("SELECT * FROM user WHERE usr_id='".$row['tut_id']."'");
            $f=$r->fetch_assoc();
        echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>"; 
        }            
?>
</ul>

<table>
  <tbody>
  <tr><td>&nbsp;<td>&nbsp;</td><td>&nbsp;</td></tr>
  <tr><th>Name<td>E-mail Id</td><td>&nbsp;</td></tr>
  <form action="./?act=6" method="POST" >
  <tr>
    <td>
      <input type="text" name="name" id="name" />
    </td>
    <td>
    <input type="text" name="mail_id" id="mail_id" />
    </td>
    <input type="hidden" name="profession" id="profession" value="Tutor" />
    <input type="hidden" name="info" id="info" value='<?php echo $this->dpt['name']; ?>' />
    <input type="hidden" name="dpt" id="dpt" value='<?php echo $this->dpt['id']; ?>' />
    <td><input type="submit" name="new_sub" id="new_sub" value="invite new subordinate" /> </td>
  </tr>
  </tbody>
</table>
