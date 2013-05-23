<?php //__________________Login Page______________
session_start();
require_once "php_scripts/skooler.php";
if(@$_SESSION['login_status']==true)
{
	//creating the user object 
	$usr_ob=Skooler::create_ob();
	if(isset($_GET['action']))
	{
		switch($_GET['action'])
		{
			case "logout" : $usr_ob->logout();
			break;
							
			case "settings" : require (GUI."settings.php");
			break;
			
			case "lib" : require (GUI."library.php");
			break;
			
			case "nb" : require (GUI."noticeboard.php");
			break;
			
			case "msg" : require (GUI."message.php");
			break;						
			
			default : @$_SESSION['classes']=null;
			require (GUI."main.php");
		}
	}
	else
	{	@$_SESSION['classes']=null;
		require (GUI."main.php");
	}
	
}
else if(@$_POST['user']&&@$_POST['passwd'])
{
	Skooler::check_login($_POST['user'],$_POST['passwd']);//redirects to index.php	
}
else
{
	 require (GUI."login.php");
}



 ?>
