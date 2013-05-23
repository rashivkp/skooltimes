<?php //---------------- This document is used for submiting the invitation details--------
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="/skooltimes/styles/first.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="newbox.js" > </script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add New Institute</title>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
</head>

<body bgcolor="#FFE4E1" >
<div class="wrapper">
<form action="send.php" method="POST" >
<table id="invitation">
<tbody>
<tr><th>Name</th><th>Mail id</th><th>Type</th></tr>
<tr>
    <td>
        <input type="text" name="name" id="name" tabindex="0" />
        </td>
    <td>
        <input type="text" name="mail_id" id="mail_id" tabindex="1" />
       </td>
    <td>
      <select name="profession" id="profession" tabindex="2">
       <option>Superior</option>
       </select>
</tr>
<tr>
  <td>&nbsp;</td><td>&nbsp;</td></tr>
<tr>
    <td><input type="submit" value="Send" /></td>
    <td><input type="Reset" /></td>
</tr>				  
</tbody>
</table>
                
</form>
<br />
<br />
<br />

<a href="../admin/">Back to Home</a>
</div>






<script type="text/javascript">
</script>
</body>
</html>

