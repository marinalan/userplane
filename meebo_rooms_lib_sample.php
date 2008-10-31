<?php
/**
 * Include the meebo rooms library and a generateMeeboRoomEmbed function is exposed
 * This will give you the bare embed html code for a room.
 * You can specify specific partner enabled parameters such as a locked nickname
 * and buddy icon via an associated array.
 * 
 * Then simply call the generateMeeboRoomEmbed function with the hash id of the room,
 * the desired width, height and pass in the associative array and print out the
 * the result to wherever the meebo room goes in your layout.
 **/

require_once("meebo_rooms_lib.php");
  
/*        
$ret = create_room("Othello 3", 300, 500);
echo "meebo created room: ".print_r($ret, true)."\n";
                     
$ret = list_rooms();
echo "your meebo rooms: ".print_r($ret, true)."\n";
    
$ret = get_configuration('prescodb71278ee');
echo "room configuration: ".print_r($ret, true)."\n";
      
$ret = check_room('chessroom1h20055109be7');
echo "if room is your own: ".print_r($ret, true)."\n";
                        
$ret = configure_room('othello35bcba0cf', array(
  'room' => 'Othello 3',
  'desc' => 'Othello, Room 3',
  'persistent' => true,
  'blc' => 'dbdbdc',
  'dtc' => '458B74',
  'ibfc' => 'f0f8ff',
  'btn_fill' => '7fffd4',
  'partner_url' => 'http://hockey-proxy.dyndns.org:5000/userplane/meebo_nickname.php',
  'h' => 300,
  'w' => 500
  )
);
echo "room's new configuration: ".print_r($ret, true)."\n";
   
$ret = get_configuration('othello35bcba0cf');
echo "room configuration: ".print_r($ret, true)."\n";
 */                
$ret = get_widget_embed_code('prescodb71278ee');
echo "widget_embed_code for room: ".print_r($ret, true)."\n";
/*   
$ret = delete_room('olcupw943b0604');
echo "widget_embed_code for room: ".print_r($ret, true)."\n";
   
$ret = get_participants(
  array('chessroom1h20055109be7','bingoroom118578131','prescodb71278ee')
);
echo "get_participants for room: ".print_r($ret, true)."\n";

 */
$params = array("nickname" => "meebo buddy",
				"iconurl" => "http://icons.meebo.com/stock/meebo_bubble.png",
				"profile_url" => "http://icons.meebo.com/profile/meebo_profile.html",
				"profile_url_text" => "Label for Profile URL button",
				"profile_message" => "Display optional text info about user here"
				);

//echo generateMeeboRoomEmbed("your_room_hash", 640, 500, $params, MEEBO_API_KEY, MEEBO_PARTNER_ID);
?>
