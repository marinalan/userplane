<?php
 
	$strDestinationUserID = $_GET["strDestinationUserID"];
 
	$strZoneIDBanner = "4079";
	$strZoneIDText = "193";
	$strDomainID = "YOUR_DOMAIN_ID_HERE";
/*	These zoneID's will be assigned to you by Userplane.  You will receive them in the same email as the flashcom info.
*/
?>

		<frameset rows="*,108" framespacing="0" frameborder="no" border="0">
			<frame src="wm.php?strDestinationUserID=<?php echo( $strDestinationUserID ); ?>" name="Webmessenger_Frame"  scrolling="NO" noresize>
			<frame src="http://subtracts.userplane.com/mmm/bannerstorage/ch_int_frameset.html?app=wm&zoneID=<?php echo( $strZoneIDBanner ); ?>&textZoneID=<?php echo( $strZoneIDText ); ?>&domainid=<?php echo( $strDomainID ); ?>" name="Ad_Frame" scrolling="NO" noresize>
 
		</frameset>
