<?php
//required when the superior choice selected

    $result=$this->db->query("SELECT * FROM user WHERE usr_id='".$this->clg['sup_id']."'") or die("not success");
    $f=$result->fetch_assoc();
?>
<ul class="users_list">
    <?php
        echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>"; 
    ?>
</ul>
