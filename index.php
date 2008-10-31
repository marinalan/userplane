<?php
    error_reporting(E_ALL);      
    require_once 'database.inc';
    require_once 'user.inc';
    require_once 'session.inc';
    require_once 'game.inc';
    require_once 'meebo_rooms_lib.php';

	/*
	 *	You need to set these variables to be appropriate for your site and user.
	 */
	$strFlashcomServer = "flashcom.marinalan.userplane.com";
	$strDomainID = "marinalan.com";
    $strInstanceID = ""; 				// Optional identifier for the instance, used when you are running more than one instance of the application on a single domainID.
	$strSessionGUID = session_id();			// The session identifier for the currently logged in user
	$strKey = "";						// Additional login information you may need passed
	$strInitialRoom = "";				// Optional room name of the room the user will start in.  This overrides the similar setting in the XML
	// Do not change anything below here
	$strSwfServer = "swf.userplane.com";
	$strApplicationName = "CommunicationSuite";
	$strLocale = "english";
?>
<html>
<head>
	<title>Game Simulator Userplane evaluation</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="../ext-2.2/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="game_chat.css" />
 	<script type="text/javascript" src="../ext-2.2/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../ext-2.2/ext-all-debug.js"></script>
    <script type="text/javascript" src="game_chat.js"></script>
    <script type="text/javascript" src="flashobject.js"></script>
	<script language="JavaScript">
	<!--
function csEvent( strEvent, strParameter1, strParameter2 )
{
	if( strEvent == "InstantCommunicator.StartConversation" )
	{
		var strUserID = strParameter1;
		var bServer = strParameter2;
		// open up an InstantCommunicator window.  For example:
		launchWM( "<?php echo( $strSessionGUID ); ?>", strUserID );
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
}

function launchWM( userID, destinationUserID )
{
	var popupWindowTest = window.open( "wm.php?strDestinationUserID=" + destinationUserID, "WMWindow_" + replaceAlpha(userID) + "_" + replaceAlpha(destinationUserID), "width=468,height=595,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1" );
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

show_chat = function(){
	var fo = new FlashObject("http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/ch.swf", "ch", "100%", "100%", "6", "#ffffff", false, "best");
	fo.addParam("scale", "noscale");
	fo.addParam("menu", "false");
	fo.addParam("salign", "LT");
	fo.addParam("allowScriptAccess", "always");
	fo.addVariable("strServer", "<?php echo( $strFlashcomServer ); ?>");
	fo.addVariable("strSwfServer", "<?php echo( $strSwfServer ); ?>");
	fo.addVariable("strApplicationName", "<?php echo( $strApplicationName ); ?>");
	fo.addVariable("strDomainID", "<?php echo( $strDomainID ); ?>");
	fo.addVariable("strInstanceID", "<?php echo($strInstanceID); ?>" );
	fo.addVariable("strSpawnID", "" );
	fo.addVariable("strSessionGUID", "<?php echo( $strSessionGUID ); ?>");
	fo.addVariable("strKey", "<?php echo( $strKey ); ?>");
	fo.addVariable("strLocale", "<?php echo( $strLocale ); ?>");
	fo.addVariable("strInitialRoom", "<?php echo( $strInitialRoom ); ?>");
    fo.useExpressInstall("expressinstall.swf");
	fo.write("flashcontent");

	// COPYRIGHT Userplane 2006 (http://www.userplane.com)
	// CS version 2.0.2
    Ext.get('flashcontent').replaceClass('step', 'step_active');
  }
<?php
    $step = 'login';
    global $user;
    
    if (!isset($user) || $user->user_id == 0){ ?> game.chat.step = 'login';
<?php
    }
    else if (!isset($_SESSION['game'])){ ?>    game.chat.step = 'pick_channel';
<?php
    }
    else { ?>                                  game.chat.step = 'talk'; 
<?php 
    }
?>
	//-->
	</script>
</head>

<body>
	<h3 id="title">Chat:</h3>
    <div id="authorized" class="tiny"><span id="greeting"></span>
        <a href="javascript:void(0)" id="logout">Sign Out</a></div>
    <div style="text-align:center; margin-bottom: 15px;" align="center"> 
    <iframe id="ads" src="http://cache.static.userplane.com/subtracts/adframes/int_frameset.html?app=wc&zoneID=4078&textZoneID=193&clickID=a0f27b3d&t=364237&domainid=<?php echo( $strDomainID ); ?>&asl=ENTER_ASL_HERE" name="Banner" scrolling="NO" width="728" height="130" frameborder="0"></iframe></div>
<div id="login" class="step">
   <div class="table-box middle">
     <h3>Please login or <a style="color:blue;" href="#" id="register">sign-up</a> for an account 
     </h3>
     <div id="logincnt"></div>
   </div>
</div>
<div id="sign_on" class="step">
   <div class="table-box middle">
     <h3>Please <a style="color:blue;" href="#" id="login_link">login</a> or sign-up for an account
     </h3>
     <div id="signoncnt"></div>
   </div> 
</div>
<div id="pick_game" class="step">
    <div id="game_form">
    <table><tr>
    <td width="250px"><input type="text" id="games-with-qtip" size="30"/></td><td><span id="start_btn"></span></td>
    </tr></table>
    </div>
</div>
<div id="pick_channel" class="step">
    <div id="channel_form">
    <table><tr>
    <td width="250px"><input type="text" id="channels-with-qtip" size="30"/></td>
    <td><span id="join_talk_btn"></span></td>
    </tr></table>
    </div>
</div>
<div id="flashcontent" class="step">
	<strong>You need to upgrade your Flash Player by clicking <a href="http://www.macromedia.com/go/getflash/" target="_blank">this link</a>.</strong><br><br><strong>If you see this and have already upgraded we suggest you follow <a href="http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=tn_14157" target="_blank">this link</a> to uninstall Flash and reinstall again.</strong>
</div>
<div id="meebo_chat" class="step">
</div>
</body>
</html>
