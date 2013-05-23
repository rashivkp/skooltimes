<?php if(isset($_POST['send_inv']) && $_POST['name']!="" && $_POST['mail_id']!="")
{
	send_invitation();
}
else 
	echo "enter details..!";
?>
<form action="./?act=2" method="POST" >
<table id="invitation">
<tbody>
<tr><th>Name</th>
        <th>Mail id</th>
        <th><?php switch($_SESSION['opt_profession'])
                            {                                
                                case "HOD":  echo "Department"; break;
                            }
 ?></th></tr>
<tr>
    <td>
      <input type="text" name="name" id="name" />
      </td>
    <td>
    <input type="text" name="mail_id" id="mail_id" />
    </td>
      <input type="hidden" name="profession" id="profession" value="<?php echo $_SESSION['opt_profession']; ?>" />
      <td>
        <input type="text" name="info" id="info" />
       </td>
</tr>
</tbody>
</table>
<br />
<table>
<tbody>
<tr>
    <td><input type="submit" value="Send" name="send_inv"/></td>
    <td><input type="Reset" /></td>
</tr>				  
</tbody>
</table>
</form>
