<?php
	// This is the primary method of communication between Userplane and your servers.  

	header( "Content-Type: text/xml; charset=utf-8" );

	echo( "<?xml version='1.0' encoding='utf-8'?>" );
	echo( "<!-- COPYRIGHT Userplane 2006 (http://www.userplane.com) -->" );
	echo( "<!-- WM version 1.8.13 -->" );
	echo( "<icappserverxml>" );

	$strDomainID = isset($_GET['domainID']) ? $_GET['domainID'] : null;
	$strFunction = isset($_GET['function']) ? $_GET['function'] : (isset($_GET['action']) ? $_GET['action'] : null);
	$strCallID = isset($_GET['callID']) ? $_GET['callID'] : null;

	if( $strFunction != null && $strDomainID != null )
	{
		$strSessionGUID = isset($_GET['sessionGUID']) ? $_GET['sessionGUID'] : null;
		$strKey = isset($_GET['key']) ? $_GET['key'] : null;
		$strUserID = isset($_GET['memberID']) ? $_GET['memberID'] : null;
		$strTargetUserID = isset($_GET['targetMemberID']) ? $_GET['targetMemberID'] : null;

		if( $strFunction == "getDomainPreferences" )
		{
			// get the value from your database
			echo( "<allowCalls>setBlockedStatus,sendConnectionList,startConversation</allowCalls>" );
			echo( "<characterlimit>200</characterlimit>" );
			echo( "<forbiddenwordslist>ass,bitch</forbiddenwordslist>" );
			echo( "<automaticTranslation>false</automaticTranslation>" ); 
			echo( "<translationAllowedDomains></translationAllowedDomains>" ); 
			echo( "<translatingMessage>translating...</translatingMessage>" ); 
			echo( "<translationTimeout>60</translationTimeout>" ); 
			echo( "<waitToShowTranslatingMessage>5</waitToShowTranslatingMessage>" );
			echo( "<smileys>" );
				echo( "<smiley>" );
					echo( "<name>Ultra Angry</name>" );
					echo( "<image>http://images.yourCompany.userplane.com/images/smiley/UltraAngry.jpg</image>" );
					echo( "<codes>" );
						echo( "<code><![CDATA[>>:O]]></code>" );
						echo( "<code><![CDATA[>>:-O]]></code>" );
					echo( "</codes>" );
				echo( "</smiley>" );
				echo( "<smiley>" );
					echo( "<name>Angry</name>" );
					echo( "<image>DELETE</image>" );
				echo( "</smiley>" );
			echo( "</smileys>" );
			echo( "<maxvideobandwidth>20000</maxvideobandwidth>" );
			echo( "<domainlogolarge alpha=\"10\"></domainlogolarge>" );
			echo( "<line1>Age</line1>" );
			echo( "<line2>Sex</line2>" );
			echo( "<line3>Location</line3>" );
			echo( "<line4></line4>" );
			echo( "<avEnabled>true</avEnabled>" );
			echo( "<clickableUserName>true</clickableUserName>" );
			echo( "<clickableTextUserName>false</clickableTextUserName>" );
			echo( "<buddyListButton>true</buddyListButton>" );
			echo( "<preferencesButton>true</preferencesButton>" );
			echo( "<smileyButton>true</smileyButton>" );
			echo( "<blockButton>true</blockButton>" );
			echo( "<gameButton>true</gameButton>" );
			echo( "<soundButton>true</soundButton>" );
			echo( "<sliderButton>true</sliderButton>" );
			echo( "<addBuddyEnabled>true</addBuddyEnabled>" );
			echo( "<connectionTimeout>60</connectionTimeout>" );
			echo( "<sendConnectionListInterval>0</sendConnectionListInterval>" );
			echo( "<sendArchive>false</sendArchive>" );
			echo( "<sendTextToImages>false</sendTextToImages>" );
			echo( "<buttonBarColor></buttonBarColor>" );
			echo( "<inputAreaColor></inputAreaColor>" );
			echo( "<inputAreaBorderColor></inputAreaBorderColor>" );
			echo( "<hideDropShadows>false</hideDropShadows>" );
			echo( "<hideHelp>false</hideHelp>" );
			echo( "<showLocalUserIcon>false</showLocalUserIcon>" );
			echo( "<conferenceCallEnabled>-1</conferenceCallEnabled>" );
			echo( "<maxxmlretries>5</maxxmlretries>" );
			echo( "<receiveSoundURL></receiveSoundURL>" );
			echo( "<sendSoundURL></sendSoundURL>" );
			echo( "<buttonIcons>" );
				echo( "<action></action>" );
				echo( "<add></add>" );
				echo( "<block></block>" );
				echo( "<bold></bold>" );
				echo( "<buddyList></buddyList>" );
				echo( "<italic></italic>" );
				echo( "<preferences></preferences>" );
				echo( "<print></print>" );
				echo( "<smiley></smiley>" );
				echo( "<soundOn></soundOn>" );
				echo( "<soundOff></soundOff>" );
				echo( "<underline></underline>" );
				echo( "<resize></resize>" );
			echo( "</buttonIcons>" );
			echo( "<systemMessages>" );
				echo( "<waiting>true</waiting>" );
				echo( "<open>true</open>" );
				echo( "<closed>true</closed>" );
				echo( "<blocked>true</blocked>" );
				echo( "<away>true</away>" );
				echo( "<nonDeliveryMessage timeout='30' sendOnClose='true' sendOnTimeout='false' promptUser='false'>If [[DISPLAYNAME]] doesn't receive this message, they will be emailed when you close this window</nonDeliveryMessage>" );
				echo( "<nonDeliveryConfirm></nonDeliveryConfirm>" );
				echo( "<conferenceCallInvitation>Join me in a private anonymous phone call: [[NUMBER]]</conferenceCallInvitation>" );
				echo( "<conferenceCallReminder>Join a private anonymous phone call: [[NUMBER]]</conferenceCallReminder>" );
				echo( "<conferenceCallRetrievingNumber>Creating a private anonymous phone number...</conferenceCallRetrievingNumber>" );
			echo( "</systemMessages>" );
			echo( "<quickMessageList>" );
				echo( "<message>" );
					echo( "<title>Standard greeting</title>" );
					echo( "<body>Welcome! How can I help you today?</body>" );
				echo( "</message>" );
			echo( "</quickMessageList>" );
		}
		else if( $strFunction == "getMemberID" )
		{
			if( $strSessionGUID != null && $strSessionGUID != "" )
			{
				// get the value from your database

				echo( "<memberid>" . $strSessionGUID . "</memberid>" );
			}
		}
		else if( $strFunction == "startIC" )
		{
			if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
			{
				echo( "<member>" );
					echo( "<displayname>Joe Schmoe</displayname>" );
					echo( "<imagepath>http://images.yourcompany.userplane.com/pathToUserImage.jpg</imagepath>" );
					echo( "<avEnabled>true</avEnabled>" );
					echo( "<kissSmackEnabled>true</kissSmackEnabled>" );
					echo( "<showerrors>true</showerrors>" );
					echo( "<sound>true</sound>" );
					echo( "<focus>true</focus>" );
					echo( "<autoOpenAV>false</autoOpenAV>" );
					echo( "<autoStartAudio>false</autoStartAudio>" );
					echo( "<autoStartVideo>false</autoStartVideo>" );
					echo( "<backgroundColor></backgroundColor>" );
					echo( "<fontColor></fontColor>" );
					echo( "<quickMessageList ignoreNoTextEntry='false'>" );
						echo( "<message>" );
							echo( "<title>Standard Greeting</title>" );
							echo( "<body>I'm happy to be here!</body>" );
						echo( "</message>" );
					echo( "</quickMessageList>" );
					echo( "<noTextEntry>false</noTextEntry>" );
					echo( "<sessionTimeout>-1</sessionTimeout>" );
					echo( "<sessionTimeoutMessage>Your session has timed out</sessionTimeoutMessage>" );
				echo( "</member>" );
				echo( "<targetMember>" );
					echo( "<displayname>Jill Jackson</displayname>" );
					echo( "<line1>24</line1>" );
					echo( "<line2>Female</line2>" );
					echo( "<line3>San Francisco, CA</line3>" );
					echo( "<line4></line4>" );
					echo( "<imagepath>http://images.yourcompany.userplane.com/pathToUserImage.jpg</imagepath>" );
					echo( "<avEnabled>false</avEnabled>" );
					echo( "<blocked>false</blocked>" );
					echo( "<backgroundColor></backgroundColor>" );
					echo( "<fontColor></fontColor>" );
				echo( "</targetMember>" );
			}
		}
		else if( $strFunction == "addFriend" )
		{
			if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
			{
				// handle the request, no response required
			}
		}
		else if( $strFunction == "sendConnectionList" )
		{
			$strXmlData = isset($_POST['xmlData']) ? stripslashes($_POST['xmlData']) : null;

			if( $strXmlData != null )
			{
				/*
				EXAMPLE:

				<?xml version='1.0' encoding='iso-8859-1'?>
					<connectionList>
					<server>flashcom.yourserver.userplane.com</server>
					<c><f type="m">21</f><t>1</t></c>
					<c><f type="m">1</f><t>8</t></c>
					<c><f type="s">a6d5fe44</f><t>1</t></c>
					<c><f type="m">1</f><t>21</t></c>
				</connectionList>
				*/

				// update your database and no need to return anything
			}
		}
		else if( $strFunction == "setBlockedStatus" )
		{
			if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
			{
				$bBlocked = isset($_GET['trueFalse']) ? $_GET['trueFalse'] : null;
				$bBlocked = $bBlocked == "true" || $bBlocked == "1";

			// handle the request, no response required
			}
		}
		else if( $strFunction == "startConversation" )
		{
			// handle the request, no response required
		}
		else if( $strFunction == "notifyConnectionClosed" )
		{
				// in addition, you can also use the strXmlData variable to get any messages that were never delivered to the targetUser.
				$strXmlData = isset($_POST['xmlData']) ? stripslashes($_POST['xmlData']) : null;
		}
		else if( $strFunction == "getConfPhoneNumber" )
		{
			// This function is not on by default, use allowCalls in getDomainPreferences to turn it on
			$strConferenceID = isset($_GET['conferenceID']) ? $_GET['conferenceID'] : null;
			echo( "<confPhoneNumber><![CDATA[800-555-1212 x12345]]></confPhoneNumber>" );
		}
		else if( $strFunction == "sendPendingMessages" )
		{
			if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
			{
				// you can use the strXmlData variable to get any messages that were never delivered to the targetUser.
				$strXmlData = isset($_POST['xmlData']) ? stripslashes($_POST['xmlData']) : null;
			}
		}
		else if( $strFunction == "sendArchive" )
		{
			$strXmlData = isset($_POST['xmlData']) ? stripslashes($_POST['xmlData']) : null;
		}
	}
	echo( "</icappserverxml>" );
?>

