<?php
    require_once 'database.inc';
    require_once 'game.inc';

    $sql = "select game_id, game_name, rules, min_players, max_players from games";
    $result = db_query($sql);    
    $rows = array();
    while ($row = db_fetch_array($result)) {
        $rows[] = $row;
    }

    $data = array('data' => $rows, 'results' => db_num_rows($result) );
    echo json_encode($data);


