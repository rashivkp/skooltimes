<?php require (GUI."head.php"); ?>
<body>
	<div id="error" align="center"> <?php if(@$_SESSION['message']!=""){ echo $_SESSION['message']; session_destroy(); } ?> </div>
    <div class="wrapper" >	
		<div id="heading" align="center">
			<img src="images/logo.png" height="100" width="200" /><br />
		</div>

		<div id="login_form" align="center">
			<table id="login_table" height="153" border="0" >
			<form action="./" method="POST" >
				<tr>
					<td height="25" width="100">User name</td>
					<td>
                    <input name="user" type="text" />
                    </td>
				</tr>
				<tr>
					<td height="25">Password</td>
					<td>
					  <input name="passwd" type="password" />
				    </td>
				</tr>
				<tr>
					<td colspan="2"><center><input name="login" type="submit" id="login" value="        Login          " /></center></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" colspan="2"><a class="forgot" href="recover/">Forgot Password</a></td>
				</tr>
				
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				

				</form>
				</table>
		</div> 
	</div>
<?php require (GUI."footer.php"); ?>
</body>
</html>
