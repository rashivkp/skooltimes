<?php     //______________this script helping to send invitations to mail addresses _________
session_start();
require "../php_scripts/config_set.php";
$usr_id=000;
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
    $p_code=random(); //PASSCODE GENERATED

//saving the mail_id and secret_code in db

if ( $db=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME) ) {
        $sql="INSERT INTO invited (mail, code, profession, c_name, inv_by) VALUES ('$mail_id', '$p_code', '$prof', '$c_name','$usr_id')";
        if(mysqli_query($db,$sql)) { 
                //send mail
                $to      = "$mail_id";
                $subject = 'Invitation from skooltimes with the passcode';
                $message = 'welcome to skooltimes ,
                hi, '.$c_name.'  we are providing great service to improve the knowledge,,, we hope this will be helpful
                to you ...      use this secret code to signup :  '.$p_code.'click here to register : http\/\/www.skooltimes.cu.cc/signup.php';
                $headers = 'From: admin@skooltimes.com' . "\r\n" .
                    'Reply-To: rashivkp@gmail.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);
                $_POST['msg']="your invitation send successfully";
                $_SESSION['msg']="Invited Sccessfully";
                header("location:index.php");
                    
        }
        else{
            die("error occured during the process of connect to db");
        } 	
}
else{
            die("error occured during the process");
}
?>
