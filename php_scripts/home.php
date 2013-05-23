<?php
function put_home($cls_id)
{
$result=$this->db->query("SELECT * FROM sub_qstn WHERE cls_id='".$cls_id."' ORDER BY  `last_update` DESC limit 10 ");
				$i=0;
				?>
				<ul class="post">
				<?php
				while($row=$result->fetch_assoc())
				{
					if($i++>10)
					exit;
					$p_id=$row['posted_id'];
					$result1=$this->db->query("SELECT * FROM user WHERE usr_id='".$p_id."'");
					$row1=$result1->fetch_assoc()
			?>
				<li>
				 <div class="posts">
				 
						<div class="post_author">
							<div class="avatar-wrap"><img class="avatar" width='68' height='68' src="<?php echo $this->put_photo_by_id($row1["photo"]); ?>" /></div>
							<div class="posted_user" ><?php echo $row1['name']; ?></div>
							<br />
						</div>
						<div class="post_content">
							<div class="qstn"><?php echo $row['qstn'] ?></div>
						</div>
						<div class="post_replays">
							<ul class="comments">
							<?php
							$result2=$this->db->query("SELECT * FROM sub_ans WHERE qst_id='".$row['qst_id']."' ");
							while($row2=$result2->fetch_assoc())
							{
								$rp_id=$row2['posted_id'];
								$result3=$this->db->query("SELECT * FROM user WHERE usr_id='".$rp_id."'") or die("error on replays");
								$row3=$result3->fetch_assoc(); ?>
								<li>
								<div class="reply_author">
									<div class="avatar-wrap"><img class="avatar" width='68' height='68' src="<?php echo $this->put_photo_by_id($row3["photo"]); ?>" /></div>
									<span class="posted_user" ><?php echo $row3['name']; ?></span>
									
								</div>
								<div class="post_content">
								<div class="reply"><?php echo $row2['ans'] ?></div>
								</div>
								</li>
								<br class="clear" />
							<?php 
							} ?>
							<li><a href="javascript:hideshow(document.getElementById('newreply<?php echo $i; ?>'))">Reply<span>↓</span></a></li>
							
							
							</ul>
							<div id="newreply<?php echo $i; ?>" class="newreply">
								<form action="<?=$action?>" method="POST">
									<textarea cols="30" rows="5" name="reply" /></textarea>
									<input type="hidden" name="qstn" value="<?php echo $row['qst_id']; ?>" />
									<input type="submit" name="post_reply" value="Reply" />
								</form>
							
						</div><!-- post_replays div -->		
						
				</div><!-- posts div -->  
				<hr />
				</li>		  
			<?php
				}
				?>
				</ul>
<?php
}
?>
