<?php 
class Message 
{
	protected $db;//db connection
	protected $user;//array name,id
	protected $to;//array name,id,photo
	protected $recent;//array id
	
	
	function __construct($db,$user)
	{
		$this->db=$db;
		$this->user=$user;
		if(isset($_GET['id']))
		{
			if(!$this->to['id']=$this->get_id($_GET['id']) && $this->to['username']=$_GET['id'])
			{
				$_SESSION['msg']="there is no communication needed between reciever and sender";
				die("Invalid recipient!");
			}
			else if($this->get_id($_GET['id'])==$this->user['id'])
			{
				$_SESSION['msg']="there is no communication needed between reciever and sender";
				die("Invalid recipient!");
			}
		}
	}
	function display()
	{
		$result=$this->db->query("SELECT DISTINCT `sender`, `reciever` FROM messages WHERE `reciever` = '".$this->user["id"]."' OR `sender`= '".$this->user["id"]."' LIMIT 20");
		$i=0;
		$this->recent[]=0;
		while($row=$result->fetch_assoc())
		{
			if($row['sender']==$this->user['id'])
			{ 
				if(in_array($row['reciever'],@$this->recent))
				{
					continue;
				}
				else
				{
						$tmp=$row['reciever'];
				}
				
			}
			else
			{ 
				if(in_array($row['sender'],@$this->recent)) 
				{
					continue;
				}
				else
				{
					$tmp=$row['sender'];
				}
			}
				
			$res=$this->db->query("SELECT `name`, `photo`, `username` FROM `user` WHERE `usr_id` = '".$tmp."'");
			$row1=$res->fetch_assoc();
			echo "<tr><td><a href='./?action=msg&id=".$row1['username']."'>".$row1['name']."</a></td></tr><br />";
			$this->recent[]=$tmp;
		}
	}
	function show_message()
	{
		
		$sender=$this->get_id($_GET['id']);
		$res=$this->db->query("SELECT `name`, `photo` FROM `user` WHERE `usr_id` = '".$sender."'");
			$row1=$res->fetch_assoc();
			$this->to['photo']=$row1['photo'];
			$this->to['name']=$row1['name'];
	
		$result=$this->db->query("SELECT * FROM messages WHERE `reciever` = '".$this->user["id"]."' AND `sender` ='$sender' OR `reciever` = '".$sender."' AND `sender` ='".$this->user["id"]."' ORDER BY  `messages`.`time` ASC ");	
		echo "<ul class='message'";
		while($row=$result->fetch_assoc())
		{
			echo '<li>';
			if($row['reciever']==$this->user["id"])
				echo '<div class="user badge"><img  src="'.$this->put_photo_by_id($this->to['photo']).'" width="50" height="50">
				<h5>'.$this->to['name'].'</h5><p>'.$row['msg'].'</p></div>';
			else 
				{
					echo '<div class="recepient badge"><img src="'.$this->put_photo_by_id($this->user['photo']).'" width="50" height="50">
				<h5>'.$this->user['name'].'</h5><p>'.$row['msg'].'</p></div>';
			}		
			echo '</li>';
				
		}
?>
		</ul>
		
		<form action="./?action=msg&id=<?php echo $this->to['username']; ?>" method="POST">
		  <textarea name="message" cols="30" rows="5"></textarea><br />
			<input type="submit" name="send" value="   Send   " />
		</form>
<?php
	}
	function send($message,$to)
	{
		if(!$to=$this->get_id($to))
			die("Invalid recipient");
		$sql="INSERT INTO `messages` ( `id`, `msg`, `reciever`, `sender`, `time`) 
							VALUES (NULL, '$message', '".$to."', '".$this->user['id']."', '".date("Y-m-d H:i:s")."');";
		$result=$this->db->query($sql);
	}
//geting id by username
	function get_id($username)
	{
		if($res=$this->db->query("SELECT `usr_id` FROM `user` WHERE `username` = '".$username."'"))
		{
			if($row=$res->fetch_assoc())
			{
				return $row['usr_id'];
			}
			else
			{
				return 0;
			}
		}
	}
	
	function put_photo_by_id($photo)
		{
			if($photo!="")
			{
				return "images/users/".$photo;
			}
			else
			{
				return "images/user.png";
			}
		}
		
}//class
?>		
