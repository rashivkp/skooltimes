<?php
require "./php_scripts/config_set.php";
$dbconnect=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME) or die("error");
			
switch(@$_GET['act'])
{
	case "stud" :
					mysqli_query($dbconnect,"DELETE FROM `student` WHERE std_id='".$_GET['id']."'");
					mysqli_query($dbconnect,"DELETE FROM `user` WHERE usr_id='".$_GET['id']."'");
					header("location:./?act=4");
	break;
	case "tut" :	$result=mysqli_query($dbconnect,"DELETE FROM `sub_ans` WHERE qst_id='".$_GET['id']."'");
					while($r=mysql_fetch_assoc($result))
					{
						mysqli_query($dbconnect,"DELETE FROM `sub_ans` WHERE qst_id='".$r['qst_id']."'");
					}
					mysqli_query($dbconnect,"DELETE FROM `sub_qstn` WHERE sub_id='".$_GET['id']."'");
					mysqli_query($dbconnect,"DELETE FROM `subject` WHERE sub_id='".$_GET['id']."'");
					header("location:./?act=5");
	break;
	case "cls" :
					mysqli_query($dbconnect,"UPDATE `user` SET `type` =  'Tutor' WHERE  `usr_id` =(SELECT `tut_id` FROM `class` WHERE cls_id='".$_GET['id']."')");
					mysqli_query($dbconnect,"DELETE FROM `subject` WHERE cls_id='".$_GET['id']."'");
					mysqli_query($dbconnect,"DELETE FROM `sub_qstn` WHERE cls_id='".$_GET['id']."'");
					mysqli_query($dbconnect,"DELETE FROM `class` WHERE cls_id='".$_GET['id']."'");
					header("location:./?act=5");
	break;
	case "hod" :
	break;
	default:header("location:./");
}
?>
