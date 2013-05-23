    <?php 
if(@$_SESSION['admin']!==true)
{
    header("location:index.php");
}
if(@$_SESSION['msg']!="")
{
    echo $_SESSION['msg'];
    $_SESSION['msg']="";
}
?>

<center>
            <a href="index.php?action=logout">Logout </a>            <br /><br /> 
            <a href="invite.php">Add a Institute </a>            <br /><br />
</center>
