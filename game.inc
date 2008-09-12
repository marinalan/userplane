<?php
function assign_room( $game_id, $game_name ){
    global $user;    
    $room_id = 1;

    // some very crude imitation of room assignment....
    $result = db_query(
        "select game_name, min_players, max_players, cur_room_id from games where game_id=%d", 
        $game_id);
    $game = db_fetch_array($result);
    if ($game && $user->user_id > 0) {
        if (isset($game->cur_room_id)) { $room_id = $game->cur_room_id; }
        $players = db_result(db_query(
            "select count(*) from game_players where game_id = %d and room_id = %d",
            $game->game_id, $room_id));
        if ($players >= $game->max_players){
            $room_id++;
            db_query("update games set cur_room_id = %d where game_id = %d", $room_id, $game->game_id);
        }
        db_query("insert into game_players (game_id, room_id, user_id) 
                  values (%d, %d, %d)", $game->game_id, $room_id, $user->user_id);
        $players++;
        if ($players >= $game->min_players){
            // start "game session"? It will last 1 hour?
            db_query("insert into games_online (game_id, room_id, players, start_tm, end_tm)
                values (%d, %d, %d, now(), ADDTIME(now(), '01:00:00'))",  
                $game->game_id, $room_id, $players);
        }
    }
    return $room_id;
}
