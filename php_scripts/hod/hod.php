<?php
//required when the superior choice selected

    $result=$this->db->query("SELECT dpt_hod_id FROM department WHERE clg_id='".$this->clg['id']."'") or die("not success");
    while($row=$result->fetch_assoc())
    {
        echo'<ul class="users_list">';
            $rst=$this->db->query("SELECT * FROM user WHERE usr_id='".$row['dpt_hod_id']."'") or die("not success");
            while($f=$rst->fetch_assoc())
            {
        echo "<li><div class='useritm'><div class='photo avatar-wrap'><img class='avatar' width='90' height='90' src='".$this->put_photo_by_id($f['photo'])."'/></div>
									<div class='name'><a href='./?action=msg&id=".$f['username']."'>".$f['name']."</a></div></div></li>";   
            }
            echo '</ul>';
    }
    ?>
