<?php

	include('userplane/presence.php');

	$strUserID = 1234;
/*	This is the userID of the person that is viewing this page.  This value must be an integer.
*/
	$presenceID = 4;
/*	This is the presenceID from Userplane, it will be included with your Flashcom info email
*/
	$strPassword = '3XKI4SU4';
/*	This is the password associated with your presenceID, this will also be included in your
	Flashcom info email and will be the same as your general account password
*/
	$strTargetUserID = 5678;
/*	This is the ID of the user that you are trying to IM
*/
	$wmURL = 'http://www.yoursite.com/userplane/wm.php';
/*	This is the full URL to your webmessenger.php file, this is needed for
	the windows to open on the other users browser.
*/
	$cmdURL = 'http://www.yoursite.com/userplane/cmd.php';
/*	This is the full URL to your cmd.php file, this is needed for
	the user to know when someone declines their IM request.
*/
	$notify = 'false';
/*	Whether or not you want the DHTML notification displayed for every IM request.
	If false and no pop-up blocker is enabled new IM's will open automatically without
	asking the user.
*/
?>


<html>
	<head>
		<title>Example Website Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	<body>
		<?php

		/*	This is the code needed to log this user into Userplane presence.
		*/
			up_presence($presenceID , $strPassword , $strUserID , $wmURL , $notify , $cmdURL );
		?>
		<script src="userplane/functions.js" type="text/javascript" language="javascript"></script>
		userID set on this page is: <b><?php echo( $strUserID ) ?></b>
		 </br>
		targetUserID set on this page is: <b><?php echo( $strTargetUserID ) ?></b>
		</br>
		</br>
		<i>Click <a href="" onClick="javascript: up_launchWM( '<?php echo( $strUserID ) ?>', '<?php echo( $strTargetUserID ) ?>' ); return false;">here</a> to send an IM from <?php echo( $strUserID ) ?> to <?php echo( $strTargetUserID ) ?>.</i>
		</br>
		<i>Click <a href="" onClick="javascript: up_launchUL(); return false;">here</a> to open the Userlist in a separate window.</i>
		</br>
	</body>
</html>