<?php
class subject
{
//printing works of the subject	
		function __construct($db,$user)
		{
			$this->db=$db;
			$this->user=$user;
		}
		function put_sub_works($cls_id,$sub_id)
		{
			$qry="SELECT * FROM works WHERE cls='".$cls_id."' AND sub='".$sub_id."' ";
			if($result=$this->db->query($qry))
			{
				if($result->num_rows)
				{
					?>
					<table>
						<form>
						<tbody>
							<tr><th>&nbsp;</th><th>Work</th><th>Post Date</th><th>Last Date</th></tr>';	
			<?php			$i=1;
							while($row=$result->fetch_assoc())
							{
								echo "<tr>
										<td>".$i++."</td>
										<td>".$row['caption']."</td>
										<td>".$row['post_date']."</td>
										<td>".$row['last_date']."</td></tr>";
							}
					echo "</tbody></form></table>";
				}
				else
					echo "There is no works";
			}
		}
//uploading new qstn		
		function new_qstn($cls_id, $sub_id, $usr_id, $qstn)
		{
		 $res=$this->db->query("INSERT INTO `sub_qstn` (`cls_id`, `sub_id`, `posted_id`, `qstn`, `post_date`, `qst_id`) 
						VALUES ('".$cls_id."',
						'".$sub_id."',
						'".$usr_id."',
						'".$qstn."',
						'".date("y.m.d")."',											
						NULL);");
		}
//uploading new reply of a question		
		static function new_reply($qst_id, $usr_id, $reply)
		{
			$this->db->query("INSERT INTO  `sub_ans` (`qst_id`, `ans_id`, `posted_id`, `post_date`, `ans` )
								VALUES ('".$qst_id."',
											NULL, 
											'".$usr_id."', 
											'".date("y.m.d")."', 
											'".$reply."')");
		}
//printing the new question form
		function new_qstn_form($action)
		{
			?>
		<div class="makenewpost"><a href="javascript:hideshow(document.getElementById('new_post'))">New Post <span>↓</span></a>
			<div id="new_post">
			<form method="POST" action="<?=$action;?>">
						<td><textarea cols="30" rows="5" name="qstn" /></textarea></td>
						<td><input type="submit" name="new_qstn" value="POST"></td>
						
			</form>
			</div>
		</div>
		<?php
		}
//printing the posts
		function put_subject($cls_id, $sub_id,$action)
		{
		//connect to sub_qstn table and retrieve latest 10 questions ..
		$result=$this->db->query("SELECT * FROM sub_qstn WHERE cls_id='".$cls_id."' AND sub_id='".$sub_id."' ");
			$i=0;
			while($row=$result->fetch_assoc())
			{
				if($i++>10)
				exit;
				$p_id=$row['posted_id'];
				$result1=$this->db->query("SELECT * FROM user WHERE usr_id='".$p_id."'");
				$row1=$result1->fetch_assoc()
		?>
			 <div class="posts">
			 
					<div class="post_header">
						<img width='50' height='50' src="<?php echo $this->put_photo_by_id($row1["photo"]); ?>" />
						<span class="posted_user" ><?php echo $row1['name']; ?></span>
						<br />
						<div class="qstn"><?php echo $row['qstn'] ?></div>
					</div>
					
					<div class="post_replays">
						<?php
						$result2=$this->db->query("SELECT * FROM sub_ans WHERE qst_id='".$row['qst_id']."' ");
						while($row2=$result2->fetch_assoc())
						{
							$rp_id=$row2['posted_id'];
							$result3=$this->db->query("SELECT * FROM user WHERE usr_id='".$rp_id."'") or die("error on replays");
							$row3=$result3->fetch_assoc(); ?>
							
							<div class="reply_header">
								<img width='50' height='50' src="<?php echo $this->put_photo_by_id($row3["photo"]); ?>" />
								<span class="posted_user" ><?php echo $row3['name']; ?></span>
								<div class="reply"><?php echo $row2['ans'] ?></div>
							</div>
							
						<?php 
						} ?>
						
						<a href="javascript:hideshow(document.getElementById('newreply<?php echo $i; ?>'))">Reply<span>↓</span></a>
						
						<div id="newreply<?php echo $i; ?>" class="newreply">
							<form action="<?=$action?>" method="POST">
								<textarea cols="30" rows="5" name="reply" /></textarea>
								<input type="hidden" name="qstn" value="<?php echo $row['qst_id']; ?>" />
								<input type="submit" name="post_reply" value="Reply" />
							</form>
						</div>
						
					</div><!-- post_replays div -->		
						
				</div><!-- posts div -->        
		<?php
			}
		}
}//class subject    
		?>



		
