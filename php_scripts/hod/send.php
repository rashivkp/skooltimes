<?php     //______________this script helping to send invitations to mail addresses _________
function send_invitation_by_hod()
{
	$usr_id=$_SESSION['user']['id'];
	function random($length = 10)
	{     
		$chars = 'b0c9dfg2hj1klm7np3rs4tvw6xza8eio5u';
	   $result="";
		for ($p = 0; $p < $length; $p++)
		{
			$result .= ($p%2) ? $chars[mt_rand(19, 33)] : $chars[mt_rand(0, 18)];
		}
	   
		return $result;
	}
	//retrieving data send by form
		$c_name=$_POST['name']; 
		$mail_id=$_POST['mail_id'];
		$prof=$_POST['profession'];
		$info=$_POST['info'];
		if(isset($_POST['dpt']))
			$dpt=$_POST['dpt'];
		else
			$dpt=0;
		$p_code=random(); //PASSCODE GENERATED

	//saving the mail_id and secret_code in db

	if ( $db=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME) ) {
			$sql="INSERT INTO invited (mail, code, profession, c_name, inv_by, dpt, info) VALUES ('$mail_id', '$p_code', '$prof', '$c_name','$usr_id', '$dpt', '$info')";
			if(mysqli_query($db,$sql)) { 
					//send mail
					$to      = $mail_id;
					$subject = 'Invitation from skooltimes with the passcode';
					$message = "welcome to skooltimes ,.\r\n. 
					hi, ".$c_name."  we are providing great service to improve the knowledge,,, we hope this will be helpful
					to you ...  \r\n     use this secret code to signup :  ".$p_code."click here to register : http\/\/www.skooltimes.cu.cc/register/";
					$headers = 'From: "admin@skooltimes.com"' . "\r\n" .
						'Reply-To: "rashivkp@gmail.com"' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					$Name="skooltimes";
					$Email="mohamedrashidc@gmail.com";
					$headers="From: \"$Name\" <$Email>\r\nReply-To: \"$Name\" <$Email>\r\nX-Mailer: chfeedback.php 2.04";
					mail($to, $subject, $message, "From: \"$Name\" <$Email>\r\nReply-To: \"$Name\" <$Email>\r\nX-Mailer: chfeedback.php 2.04" );
					mail($to, $subject, $message, $headers);
					echo "Invited Successfully";
					
			}
			else{
				die("error occured during the process of connect to db");
			} 	
	}
	else{
				die("error occured during the process");
	}
}
?>

