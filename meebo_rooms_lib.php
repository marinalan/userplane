<?php
require_once("curl.php");

define("MEEBO_API_KEY", "3a88bdf6dfd06862c3e0bbb2c32fe80713a2b523"); //Secret. Never give this out.
define("MEEBO_PARTNER_ID", "roomsapi_rudeboy@meebo.org@meebo.org");

define("MEEBO_API_BASE", "https://api.meebo.com/api/rooms/v1/");
/**
 * generateMeeboRoomEmbed
 *
 * Returns html embed code for a room taking into account any special
 * partner enabled parameters including setting and locking the users 
 * nickname and buddy icon.
 *
 * $room_id - The widget hash identifier for a room. http://widget.meebo.com/mcr.swf?id=##########
 * $width - While the config file saves the width, you still determine width via html layout
 * $height - same as above
 * $partner_params - A key/value hash of partner enabled user settings
 * $api_key - Secret API key that is given to you by meebo. Never give this out
 * $partner_id - In the format of 'your_partner_id@meebo.org' , this is used to identify which
 *               you, the api key holder, so we can verify the parameters you are passing to us
 *               against your api key.
 **/

function generateMeeboRoomEmbed($room_id, $width, $height, $partner_params, $api_key, $partner_id) {
	$partner_params["id"] = $room_id;
	$partner_params["partner_id"] = $partner_id;
	$partner_params["timestamp"] = (string)time();
	$partner_params["secret"] = $api_key;
	ksort($partner_params);
	$verification_string = meeboBuildQuery($partner_params);
	unset($partner_params["secret"]); //obviously don't pass the shared secret
	$partner_params["hash"] = sha1($verification_string); //add in the hash to be verified
	$widget_args = meeboBuildQuery($partner_params);
	$movieurl = "http://widget.meebo.com/mcr.swf";
	$embed_code = "<object width=\"$width\" height=\"$height\"><param name=\"movie\" value=\"$movieurl?$widget_args\"></param><embed src=\"$movieurl?$widget_args\" type=\"application/x-shockwave-flash\" width=\"$width\" height=\"$height\" /></object>";
	return $embed_code;
}

function meeboBuildQuery($params) {
	//for php4
	$query_string = "";
	foreach($params as $key=>$value) {
		if($query_string != "") {
			$query_string .= "&";
		}
		$query_string .= urlencode($key) . "=" . urlencode($value);
	}
	return $query_string;
}

function create_room($room_name, $h, $w){
  $url = MEEBO_API_BASE."create?apikey=".MEEBO_API_KEY."&room=".urlencode($room_name).
    "&h=$h&w=$w";

  echo "create_room: calling $url\n";
  $result = open_https_url($url,false,true); 
  echo "create_room: result=$result\n";
  return json_decode($result);
}

function list_rooms(){
  $url = MEEBO_API_BASE."list?apikey=".MEEBO_API_KEY;

  echo "list_rooms: calling $url\n";
  $result = open_https_url($url,false,true); 
  echo "list_rooms: result=$result\n";
  return json_decode($result);
}

function check_room($node){
  $url = MEEBO_API_BASE."check?apikey=".MEEBO_API_KEY."&node=$node";

  echo "check_room: calling $url\n";
  $result = open_https_url($url,false,true); 
  echo "check_room: result=$result\n";
  return json_decode($result);
}

function get_configuration($node){
  $url = MEEBO_API_BASE."getconfiguration?apikey=".MEEBO_API_KEY."&node=$node";

  echo "get_configuration: calling $url\n";
  $result = open_https_url($url,false,true); 
  echo "get_configuration: result=$result\n";
  return json_decode($result);
}

function configure_room($node, $params){
  $url = MEEBO_API_BASE."configure?apikey=".MEEBO_API_KEY."&node=$node";
  $qs = meeboBuildQuery($params);
  if ($qs != "") $url .= "&$qs";

  echo "configure_room: calling $url\n";
  $result = open_https_url($url,false,true); 
  echo "configure_room: result=$result\n";
  return json_decode($result);
}

function get_widget_embed_code($node){
  $url = MEEBO_API_BASE."getwidgetembedcode?apikey=".MEEBO_API_KEY."&node=$node";

  echo "get_widget_embed_code: calling $url\n";
  $result = open_https_url($url,false,true); 
  echo "get_widget_embed_code: result=$result\n";
  return json_decode($result);
}

function delete_room($node){
  $url = MEEBO_API_BASE."delete?apikey=".MEEBO_API_KEY."&node=$node";

  echo "delete_room: calling $url\n";
  $result = open_https_url($url,false,true); 
  echo "delete_room: result=$result\n";
  return json_decode($result);
}

function get_participants($nodes){
  $url = MEEBO_API_BASE."getparticipants?apikey=".MEEBO_API_KEY;
  $num = count($nodes);
  $url .= '&num='.$num;
  for( $i = 1; $i <= $num; $i++ ){
    $url .= '&'.$i.'node='.$nodes[$i-1];
  }

  echo "get_participants: calling $url\n";
  $result = open_https_url($url,false,true); 
  echo "get_participants: result=$result\n";
  return json_decode($result);
}
?>
