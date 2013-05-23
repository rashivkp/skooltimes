<?php 
require "config_set.php";
class Register
{
    public $inv_id,$usr_id,$inv_type,$db,$username,$usr_type;
    function __construct()
    {
        $this->db=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $this->inv_id=$_SESSION['inv_by'];
        $this->inv_type=$_SESSION['inv_type'];

    }
//checks when the passcode and mail id is submited to start registration
    static function check_pass($mail_id, $pass)
    {
        $dbconnect=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME) or die("error on db conn");
        $result=mysqli_query($dbconnect,"SELECT * FROM invited WHERE mail='$mail_id' AND code='$pass'");
        if(mysqli_num_rows($result))
        {
            $fetched=mysqli_fetch_assoc($result);
            $_SESSION['profession']=$fetched['profession'];
            $_SESSION['mail_id']=$fetched['mail'];
            $_SESSION['pass']=true;
            $_SESSION['inv_by']=$fetched['inv_by'];
            $_SESSION['dpt']=$fetched['dpt'];
            $_SESSION['info']=$fetched['info'];
            $inv_by=$fetched['inv_by'];
             if($result1=mysqli_query($dbconnect,"SELECT type FROM user WHERE usr_id='$inv_by'"))
                {
                    $row=mysqli_fetch_assoc($result1);
                    $_SESSION['inv_type']=$row['type'];
                }
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
        // it will print the approriated extra form fields needed for each type of user 
        function get_forms($usr_type)
        {
                switch($usr_type)
                {
                        case 'Student'  : 
                                                if($this->inv_type=="HOD")
                                                {         echo"  <tr><td>Semester</td>
                                                                            <td><select name=\"sem\" id=\"sem\">
                                                                            <option>1</option>
                                                                            <option>2</option>
                                                                            <option>3</option>
                                                                            <option>4</option>
                                                                            <option>5</option>
                                                                            <option>6</option>
                                                                            </select></td>
                                                                        </tr>";
                                                }
                                                break;
                        case 'Tutor':
                        case 'HOD' :    echo "<tr>
                                                                    <td>Department</td>
                                                                    <td><input type='text' name='dpt' id='dpt' value='".$_SESSION['info']."' readonly='readonly' /></td>
                                                                                    <input type='hidden' name='dpt' id='dpt' value='".$_SESSION['dpt']."' readonly='readonly' />
                                                                </tr>";
                                                break;
                        case 'Superior' :
                                                    echo '<tr><td colspan="2" align="center" >college Details<hr /></td></tr>
                                                              <tr><td>country</td>
                                                                      <td><select name="country" id="country" ><option>India</option><select></td>
                                                              </tr>
                                                              <tr><td>state</td>
                                                                      <td><select name="region" id="region"><option>kerala</option></select></td>
                                                              </tr>
                                                              <tr><td>district</td>
                                                                      <td><select name="dist" id="dist" >
                                                                        <option>malappuram</option>
                                                                        <option>calicut</option></select></td>
                                                              </tr>
                                                              <tr><td>College</td>
                                                                      <td><input type="text" name="college" id="college" />
                                                              </tr>';
                                                    break;
                                default: echo "error";
                        }

        }
        
function get_invoke_fns()
        {
			if($_POST['mail'] && $_POST['passwd']==$_POST['confirm'] && $_POST['username']!="" && $_POST['passwd']!="" && $_POST['name']!="")
			{
                        $this->insert_to_user(); //inserted to user table, which is common to all users
                        $this->usr_id=$this->get_usr_id(); //setting usr_id for using to insert other tables
                switch($_POST['type'])
                {
                        case 'Class Rep':                         
                        case 'Student'  : $this->insert_to_student();
                                break;
                        case 'HOD' :$this->insert_hod();
                                break;
                        case 'Tutor' : $this->insert_tutor();
                                break;
                        case 'Superior': $this->insert_superior();
                                break;
                        case 'Class Tutor': $this->insert_class_tutor();
                                break;
                        default: echo "error";
                }
                $this->remove_invitation();
                session_destroy();
                header("location:about.php");
           }
           else
           {
			   $_SESSION['msg']="please fill all details";
               header("location:steps.php");
		   }
        }
        
        function get_usr_id() // gets the usr_id from user table
        {
                $username=$_POST['username'];
                  if($result=mysqli_query($this->db,"SELECT usr_id FROM user WHERE username='$username'"))
                {
                    $row=mysqli_fetch_assoc($result);
                    return $row['usr_id'];
                }
        }
        
            
        function insert_to_user()
        {
                $mail_id=$_POST['mail'];
                $name=$_POST['name'];
                $type=$_POST['type'];
                $passwd=crypt($_POST['passwd']);
                $username=$_POST['username'];
                $mob=$_POST['mob'];
                //Uploading photo to images/users and adding name of photo to user
                if(mysqli_query($this->db,"INSERT INTO user (username,name,type,mail_id,passwd,mob) VALUES('$username', '$name', '$type', '$mail_id', '$passwd', '$mob')"))
                {
                    echo "inserted to user";
				}
				if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg")) && ($_FILES["file"]["size"] < 20000))
				  {
				  if ($_FILES["file"]["error"] > 0)
					{
					echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
					}
				  else
					{
					
					if (file_exists("../images/users/" . $_FILES["file"]["name"]))
					  {
					  echo $_FILES["file"]["name"] . " already exists. ";
					  }
					else
					  {
						$img_name=rand(0000,9999);
						$img_name.=$_FILES["file"]["name"];
					  move_uploaded_file($_FILES["file"]["tmp_name"], "../images/users/" . $img_name);						
						$username=$_POST['username'];
					   $dbconnect=mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME) or die("error");
						$result=mysqli_query($dbconnect,"UPDATE  `user` SET  `photo` =  '$img_name'  WHERE  `user`.`username` =  '$username';  ;") or die("query error");
					  }
					}
				  }
				else
				  {
				  echo "Invalid file";
				  }
                   
        }

        function insert_to_student() //creating entries in student table while registering 
        {
           
                $cls_id=0;                  
                    switch($this->inv_type)
                    {
        // if invited person is Class Tutor then, retrieving the cls_id here
                        case "Class Tutor"  :    $cls_id=$_SESSION['info'];
                                                        break;
                                                        
        // if invited person is hod then, retrieving the cls_id here
                        case "HOD"             : 
                                                        $classcaption=$_POST['dpt'].$_POST['sem'];
                                                        if($result1=mysqli_query($this->db,"SELECT dpt_id FROM department WHERE dpt_hod_id='$this->inv_id'"))
                                                        {
                                                            $row1=mysqli_fetch_assoc($result1);
                                                            $dpt_id=$row['dpt_id'];
                                                             if($result2=mysqli_query($this->db,"SELECT cls_id FROM class WHERE dpt_id='$dpt_id' AND caption='$classcaption'"))
                                                                {
                                                                    $row2=mysqli_fetch_assoc($result2);
                                                                    $cls_id=$row2['cls_id'];
                                                                }
                                                        }
                                                        break;
                                                        
      
                        }                                       
                                                    
                    if(mysqli_query($this->db,"INSERT INTO student (std_id, cls_id) VALUES('$this->usr_id','$cls_id')"))
                    {
                        echo "inserted to student";
                    }
                }



        function insert_tutor()
        {
            $dpt_id=0;
            switch($this->inv_type)
                        {
                            case "HOD"      :  $dpt_id=$_POST['dpt']; break;
                            case "Superior":
                                                                  $clgresult=mysqli_query($this->db,"SELECT clg_id FROM college WHERE sup_id='$this->inv_id'");
                                                                  $row=mysqli_fetch_array($clgresult);
                                                                  $clg_id=$row['clg_id'];
                                                                    $dpt=$_POST['dpt'];
                                                                  $dptresult=mysqli_query($this->db,"SELECT dpt_id FROM department WHERE clg_id=$clg_id AND caption='$dpt' "); 
                                                                  $row2=mysqli_fetch_array($dptresult);
                                                                  $dpt_id=$row2['dpt_id'];
                        }                                               

                        mysqli_query($this->db,"INSERT INTO tutor (tut_id, dpt_id) VALUES($this->usr_id, $dpt_id)");
                }
        
function insert_class_tutor()// this will register the class tutor new user
        {
            $dpt_id=0;
            $new_cls=$_POST['sem'];
            switch($this->inv_type)
                        {
                            case "HOD"      :            //geting the department key by HOD id.
                                                                  $dptresult=mysqli_query($this->db,"SELECT dpt_id FROM department WHERE dpt_hod_id='$this->inv_id' ");
                                                                  $row2=mysqli_fetch_array($dptresult);
                                                                  $dpt_id=$row2['dpt_id'];
                            case "Superior":
                                                                  $clgresult=mysqli_query($this->db,"SELECT clg_id FROM college WHERE sup_id='$this->inv_id'");
                                                                  $row=mysqli_fetch_array($clgresult);
                                                                  $clg_id=$row['clg_id'];
                                                                   $dpt= $_POST['dpt'];
                                                                  $dptresult=mysqli_query($this->db,"SELECT dpt_id FROM department WHERE clg_id=$clg_id AND caption='$dpt'"); 
                                                                  $row2=mysqli_fetch_array($dptresult);
                                                                  $dpt_id=$row2['dpt_id'];
                        }                                               
                        $classcaption=$_POST['dpt'].$_POST['sem'];
                        mysqli_query($this->db,"INSERT INTO class (caption, tut_id, dpt_id) VALUES('$$classcaption', '$this->usr_id', '$dpt_id')");
                }
        //this will do the insertion of new hod in db
        function insert_hod()
        {
            $clgresult=mysqli_query($this->db,"SELECT clg_id FROM college WHERE sup_id='$this->inv_id' "); //geting the college key
            $row=mysqli_fetch_array($clgresult);
            $clg_id=$row['clg_id'];
            $dpt_caption=$_SESSION['info']; 
            mysqli_query($this->db,"INSERT INTO department (caption, clg_id, dpt_hod_id) VALUES('$dpt_caption', '$clg_id', '$this->usr_id')");
                $dptresult=mysqli_query($this->db,"SELECT dpt_id FROM department WHERE dpt_hod_id='$this->usr_id' AND clg_id='$clg_id'"); 
                $row2=mysqli_fetch_array($dptresult);
                $dpt_id=$row2['dpt_id'];
            mysqli_query($this->db,"INSERT INTO tutor (tut_id, dpt_id) VALUES('$this->usr_id', '$dpt_id')");
        }

//new Superior by ADMINISTRATOR'S INVITATION ONLY
        function insert_superior()
        {
            $clg=$_POST['college'];
            $address=$_POST['dist'].'  '.$_POST['region'];
            $country=$_POST['country'];
             mysqli_query($this->db,"INSERT INTO college (sup_id, caption, address, country) VALUES('$this->usr_id', '$clg', '$address', '$country')");
        }
  //Removing the invitation details from invite table to ban the user to make more attempt of registrations..
        function remove_invitation()
        {
            $mail=$_POST['mail'];
            mysqli_query($this->db,"DELETE FROM invited WHERE mail='$mail'");
        }          
}
    
?>
