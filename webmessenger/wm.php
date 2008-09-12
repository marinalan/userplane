<?php
	// You need to set these variables to be appropriate for your site and user.  Some will be provided by Userplane during account setup
	// If you have not received these values yet please contact Userplane at support@userplane.com or call (323) 938-4401
	$strFlashcomServer = "flashcom.yourcompany.userplane.com";		// The flashcom server: flashcom.yourcompany.userplane.com (from Userplane)
	$strDomainID = "yourdomain.com";								// The domain ID of this site: yourdomain.com (from Userplane)
	$strSessionGUID = "11111";										// The session identifier for the currently logged in user
	$strKey = "";													// Additional login information you may need passed
	$strInstanceID = "";											// The instance that you want this user to join.  If you are using the Webmessenger
																	// with the Webchat or Userlist you will need to make sure the instanceID matches
																	// that of the Webchat or the Userlist for the IM windows to open automatically.

	// You will need to work on the sendCommand JavaScript function a few lines down to respond to any user clicks as you deem necessary

	$strDestinationUserID = $_GET["strDestinationUserID"];
?>

<html>
<head>
	<meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">
	<title>Userplane AV Webmessenger</title>

	<script language="JavaScript">
	<!--
		function sendCommand( commandIn, valueIn )
		{
			if( commandIn == "focus" )
			{
				// DO NOT EDIT

				var wmObject = getWMObject();
				// only do the focus if we are sure it is not going remove focus from typing area
				if( wmObject != null && ( wmObject.focus != undefined || ( navigator.userAgent.indexOf( "MSIE" ) >= 0 && navigator.userAgent.indexOf( "Mac" ) >= 0 ) ) )
				{
					window.focus();
					wmObject.focus();
				}
			}
			else
			{
				// EDIT HERE: you will need to handle the following commands from the wm client
				if( commandIn == "viewProfile" )
				{
					if( valueIn == "-1" )
					{
						// view their own profile
					}
					else
					{
						var userID = valueIn;
						// view userID's profile
					}
				}
				else if( commandIn == "help" )
				{
					// view the help
				}
				else if( commandIn == "buddyList" )
				{
					// view their buddy list
				}
				else if( commandIn == "preferences" )
				{
					// view the preferences
				}
				else if( commandIn == "addBuddy" )
				{
					var userID = valueIn;
					// respond to an add buddy click (XML has also been notified)
				}
				else if( commandIn == "block" )
				{
					// they blocked the user
				}
				else if( commandIn == "unblock" )
				{
					// they unblocked the user
				}
				else if( commandIn == "Connection.Success" )
				{
					// client successfully connected to server
				}
				else if( commandIn == "Connection.Failure" )
				{
					// client was disconnected from server
				}
				else if( commandIn == "Session.Timeout" )
				{
					// client's session was timed out
				}
				else if( commandIn == "Game.Open" )
				{
					openGameWindow( valueIn );
				}
			}
		}

		function focusIt()
		{
			window.focus();

			var wmObject = getWMObject();

			if( wmObject != null && wmObject.focus != undefined )
			{
				wmObject.focus();
			}
		}

		function getWMObject()
		{
			if(document.all)
			{
				return document.all["wm"];
			}
			else if(document.layers)
			{
				return document.wm;
			}
			else if(document.getElementById)
			{
				return document.getElementById("wm");
			}

			return null;
		}

		function wm_DoFSCommand( command, args )
		{
		}
		function openGameWindow( qs )
		{
			var newWindow = window.open("http://www.userplane.com/chatlite/games/?" + qs,"game","width=600,height=555,scrollbars=yes,resizable=yes,menubar=yes,location=no,status=no,directories=no,toolbar=no");

			if (newWindow == null)
			{
				alert( "Your popup blocker stopped the game window from opening" );
			}
		}

	//-->
	</script>

	<script language="VBScript">
	<!--
		//  Map VB script events to the JavaScript method - Netscape will ignore this...
		//  Since FSCommand fires a VB event under ActiveX, we respond here
		Sub wm_FSCommand(ByVal command, ByVal args)
	  		call wm_DoFSCommand(command, args)
		end sub
	-->
	</script>
</head>
<body onLoad="javascript: focusIt();" bgcolor="#ffffff" bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0">

	<?php
		if( $strDestinationUserID != "" )
		{
			$strSwfServer = "swf.userplane.com";
			$strApplicationName = "Webmessenger";
			$strLocale = "english";
			?>

			<script type="text/javascript" src="flashobject.js"></script>

			<!---
				The content of this div should hold whatever HTML you would like to show in the case that the
				user does not have Flash installed.  Its contents get replaced with the Flash movie for everyone
				else.
			--->
			<div id="flashcontent">
				<strong>You need to upgrade your Flash Player by clicking <a href="http://www.macromedia.com/go/getflash/" target="_blank">this link</a>.</strong><br><br><strong>If you see this and have already upgraded we suggest you follow <a href="http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=tn_14157" target="_blank">this link</a> to uninstall Flash and reinstall again.</strong>
			</div>

			<script type="text/javascript">
				// <![CDATA[

				var fo = new FlashObject("http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/ic.swf", "wm", "100%", "100%", "6", "#ffffff", false, "best");
				fo.addParam("scale", "noscale");
				fo.addParam("menu", "false");
				fo.addParam("salign", "LT");
				fo.addParam("allowScriptAccess", "always");
				fo.addVariable("server", "<?php echo( $strFlashcomServer ); ?>");
				fo.addVariable("swfServer", "<?php echo( $strSwfServer ); ?>");
				fo.addVariable("applicationName", "<?php echo( $strApplicationName ); ?>");
				fo.addVariable("domainID", "<?php echo( $strDomainID ); ?>");
				fo.addVariable("instanceID", "<?php echo( $strInstanceID ); ?>");
				fo.addVariable("sessionGUID", "<?php echo( $strSessionGUID ); ?>");
				fo.addVariable("key", "<?php echo( $strKey ); ?>");
				fo.addVariable("locale", "<?php echo( $strLocale ); ?>");
				fo.addVariable("destinationMemberID", "<?php echo( $strDestinationUserID ); ?>");
				fo.addVariable("resizable", "true");
				fo.write("flashcontent");

				// COPYRIGHT Userplane 2006 (http://www.userplane.com)
				// WM version 1.8.13

				// ]]>
			</script>

			<?php
		}
	?>

</div>

</body>
</html>
