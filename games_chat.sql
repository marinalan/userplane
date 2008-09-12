CREATE DATABASE IF NOT EXISTS marincom_games CHARACTER SET utf8;
GRANT ALL PRIVILEGES ON marincom_games.* 
TO 'marincom_chat'@'localhost' IDENTIFIED BY 'cH_tT1';

use marincom_games;

create table users (
    user_id            int unsigned not null auto_increment PRIMARY KEY,
    username           varchar(60),
    pass               varchar(32) NOT NULL default '',
    networkid          bigint unsigned,
    networktypeid      int unsigned,
    email              varchar(100),
    name               varchar(255),
    phone              varchar(15),
    location           varchar(255),
    birthday           date,
    profile_url        varchar(255),
    thumb_url          varchar(255),
    picture            varchar(255),
    dateaddded         datetime, 
    active             tinyint
);

create table sessions (
    user_id         int unsigned not null,
    session_id      varchar(64)  NOT NULL default '',
    hostname        varchar(128) NOT NULL default '',
    timestamp       int NOT NULL default '0',
    `session`       text,
    PRIMARY KEY (session_id),
    INDEX  sessions_uid_idx (user_id)
);

-- to simulate games and game sessions
create table games (
    game_id      int unsigned not null auto_increment PRIMARY KEY,
    game_name    varchar(150),
    min_players  int unsigned default '1',
    max_players  int unsigned default '0',
    rules        text,
    cur_room_id  int unsigned not null default '1'
);

create table games_online (
    game_id      int unsigned not null,
    room_id      int unsigned not null default '1',
    players      int unsigned default '0',
    start_tm     datetime,
    end_tm       datetime, 
    PRIMARY KEY (game_id, room_id)
);

create table game_players (
    game_id      int unsigned not null,
    room_id      int unsigned not null default '1',
    user_id      int unsigned not null
);

create table channels (
    channel_id      int unsigned NOT NULL auto_increment PRIMARY KEY,
    name            varchar(150),
    description     varchar(255),
    channel_type    enum ('internal', 'social', 'game', 'private'),
    networktypeid   int unsigned,
    socialgroup     varchar(255),
    game_name       varchar(150),
    room_id         int unsigned,  
    parent_id       int unsigned NOT NULL default 0,
    INDEX  channel_name_idx (name),
    INDEX  game_room_idx (game_name, room_id),
    INDEX  channel_type_idx (channel_type),
    INDEX  social_idx (networktypeid, socialgroup)
);

create table chat_online (
    user_id      int unsigned not null,
    channel_id   int unsigned NOT NULL,
    nickname     varchar(150),
    dt_joined    datetime,
    dt_last      datetime,
    role         enum('guest', 'regular', 'moderator', 'admin') NOT NULL default 'guest',
    PRIMARY KEY (user_id, channel_id)
);

create table chat_messages (
    msg_id        bigint unsigned NOT NULL auto_increment PRIMARY KEY,
    user_id       int unsigned NOT NULL,
    channel_id    int unsigned NOT NULL,
    text          text,
    dt_sent       datetime,
    INDEX  msg_user_idx (user_id),
    INDEX  msg_channel_idx(channel_id),
    INDEX  msg_online_idx (user_id, channel_id),
    INDEX  msg_dt_idx (dt_sent)
);

create table chat_invitations (
    user_id       int unsigned NOT NULL,
    channel_id    int unsigned NOT NULL,
    from_user_id  int unsigned NOT NULL,
    dt_sent       datetime,
    msg           text,
    PRIMARY KEY (user_id, channel_id)
);

create table chat_bans (
    user_id        int unsigned NOT NULL,
    chanel_id      int unsigned NOT NULL,    
    nickname       varchar(150),
    dt_terminated  datetime,
    PRIMARY KEY (user_id, chanel_id)
);

insert into games ( game_name, rules, min_players, max_players )
values ('Chess', 'Who beats partner''s king - wins', 2, 2),
       ('Chines Checkers', 'You can move piece onto adjacent spot or skip over adjacent piece. Who is first to reach and place all his pieces in opposite end of board - wins', 2, 6),
       ('Bingo', 'A row in your cards should be covered by declared numbers', 1, 6),
       ('Backgammon', 'Two dices are thrown at each move. If the same number shows up, you can move pieces 4 times, else 2 times during one move.', 2, 2),
       ('Othello', 'A move consists of outflanking one or more of the opponent''s discs and then flipping them to your own color. To outflank means to put a disc in a square on the board so that one or more rows of discs of the opponent''s color are bordered at each end by your own discs. A row can be horizontically, vertically or diagonally.', 2, 2);

    
