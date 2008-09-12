?php
/*
	You need to set these variables to be appropriate for your site and user.

	$strFlashcomServer - The flashcom server: flashcom.yourcompany.userplane.com
	$strDomainID - The domain ID of this site: yourdomain.com
	$strSessionGUID - The session identifier for the currently logged in user
	$strKey - Additional login information you may need passed
    $strInstanceID - Optional identifier for the instance, used when you are running more than one instance of the application on a single domainID.
	$strUpdateRedirectURL - If the user does not have the correct version of Flash installed, this is the URL that they are sent to after the auto-upgrade.  Should be the URL of this page (including query string) in most cases.
*/
$strFlashcomServer = "flashcom.yourcompany.userplane.com";
$strDomainID = "yourdomain.com";
$strSessionGUID = "aaaa";
$strKey = "";
$strInstanceID = "";
$strUpdateRedirectURL = "ul.php";
?>
<html>
<head>
	<meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">
	<title>Userplane AV UserList</title>

	<script language="JavaScript">
	<!--
		function csEvent( strEvent, strParameter1, strParameter2 )
		{
			if( strEvent == "InstantCommunicator.StartConversation" )
			{
				var strUserID = strParameter1;
				var bServer = strParameter2;
				// open up an InstantCommunicator window.  For example:
				launchWM( "<?php echo($strSessionGUID); ?>", strUserID );
			}
			else if( strEvent == "User.ViewProfile" )
			{
				var strUserID = strParameter1;
			}
			else if( strEvent == "User.Block" )
			{
				var strBlockedUserID = strParameter1;
				var bBlocked = strParameter2;
			}
			else if( strEvent == "User.AddFriend" )
			{
				var strFriendUserID = strParameter1;
				var bFriend = strParameter2;
			}
			else if( strEvent == "Chat.Help" )
			{
			}
			else if( strEvent == "User.NoTextEntry" )
			{
			}
			else if( strEvent == "Connection.Success" )
			{
			}
			else if( strEvent == "Connection.Failure" )
			{
				if( strParameter1 == "Session.Timeout" )
				{
					//handle timeout here, both inactivity and session timeouts
				}
				if( strParameter1 == "User.Banned" )
				{
					//handle ban event here
				}
			}
			else if( strEvent == "Custom.UserList.Logout" )
			{
				window.close();
			}
			else if( strEvent == "Custom.Userplane" )
			{
				window.open( "http://www.userplane.com" );
			}
		}
		function launchWM( userID, destinationUserID )
		{
			var popupWindowTest = window.open( "wm.php?strDestinationUserID=" + destinationUserID, "WMWindow_" + replaceAlpha(userID) + "_" + replaceAlpha(destinationUserID), "width=360,height=397,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1" );
			if( popupWindowTest == null )
			{
				alert( "Your popup blocker stopped an IM window from opening" );
			}
		}

		function replaceAlpha( strIn )
		{
			var strOut = "";
			for( var i = 0 ; i < strIn.length ; i++ )
			{
				var cChar = strIn.charAt(i);
				if( ( cChar >= 'A' && cChar <= 'Z' )
					|| ( cChar >= 'a' && cChar <= 'z' )
					|| ( cChar >= '0' && cChar <= '9' ) )
				{
					strOut += cChar;
				}
				else
				{
					strOut += "_";
				}
			}

			return strOut;
		}

		function getLocaleData()
		{
			var data = new Object();
			data.downloadingLabel = "Loading ...";
			data.initializingLabel = "Connecting";
			data.onlineUsersTitle = "Users Online";
			data.applicationTitle = "Userplane Userlist";
			data.buddyListTitle = "My Friends";
			data.onlineStatusLabel = "Here";
			data.awayStatusLabel = "Away";
			data.offlineStatusLabel = "Offline";
			data.searchButtonLabel = "Search";
			data.chatWithUserLabel = "IM";
			data.viewProfileLabel = "View Profile";
			data.addBuddyLabel = "Add Friend";
			data.removeBuddyLabel = "Remove Friend";
			data.sortByNameLabel = "Sort by Name";
			data.sortByStatusLabel = "Sort by Status";
			data.connectingStatusMessage = "Connecting ...";
			data.connectedStatusMessage = "Connected";
			data.authorizingStatusMessage = "Authorizing ...";
			data.authorizedStatusMessage = "Authorized";
			data.disconnectedStatusMessage = "Disconnected";
			data.authorizationDeniedStatusMessage = "Not Authorized";
			data.defaultAwayMessage = "I'm away!";
			data.defaultAvailableMessage = "I'm here!";
			data.noUsersOnlineMessage = "No users online.";
			data.noBuddiesMessage = "Your Friends list is empty.";
			data.reconnectingStatusMessage = "You were disconnected.\nReconnecting ...";
			data.statusWindowTitle = "Status";
			data.customJSCommands =
			[
				{label:"Logout", data:"Custom.UserList.Logout"},
				{type:"separator"},
				{label:"Userplane", data:"Custom.Userplane"}
			];
			return data;
		}

	//-->
	</script>
</head>
<body bgcolor="#ffffff" bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0">




<?php
/* Do not change anything below here */
$strSwfServer = "swf.userplane.com";
$strApplicationName = "CommunicationSuite";
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

		var fo = new FlashObject("http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/ul2.swf", "ul", "100%", "100%", "9", "#ffffff", false, "best");
		fo.addParam("scale", "noscale");
		fo.addParam("menu", "false");
		fo.addParam("salign", "LT");
		fo.addParam("allowScriptAccess", "always");
		fo.addVariable("strServer", "<?php echo($strFlashcomServer); ?>");
		fo.addVariable("strSwfServer", "<?php echo($strSwfServer); ?>");
		fo.addVariable("strApplicationName", "<?php echo($strApplicationName); ?>" );
		fo.addVariable("strDomainID", "<?php echo($strDomainID); ?>" );
		fo.addVariable("strInstanceID", "<?php echo($strInstanceID); ?>" );
		fo.addVariable("strSessionGUID", "<?php echo($strSessionGUID); ?>" );
		fo.addVariable("strKey", "<?php echo($strKey); ?>" );
		fo.useExpressInstall('expressinstall.swf');
  		fo.setAttribute('xiRedirectUrl', "<?php echo($strUpdateRedirectURL); ?>");
		fo.write("flashcontent");

		// COPYRIGHT Userplane 2006 (http://www.userplane.com)
		// CS version 2.0.2

		// ]]>
	</script>
</body>
</html>