CREATE TABLE `users` (
  id int(3) NOT NULL AUTO_INCREMENT,
  name varchar(10) NOT NULL,
  password varchar(15) NOT NULL,
  photo varchar(10),
  datetime datetime,
  status enum("online","offline") 
  PRIMARY KEY (id)
)


CREATE TABLE `chatrooms` (
    id int(3) NOT NULL AUTO_INCREMENT,
    room_id varchar(5),
    type enum("oneByone", "group") NOT NULL,
    unique(room_id),
    PRIMARY KEY (id)
);

CREATE TABLE `paticipants` (
    uid int(3) NOT NULL,
    room_id int(5) NOT NULL
);

CREATE TABLE `messages` (
    id int(10) NOT NULL AUTO_INCREMENT,
    text varchar(120) NOT NULL,
    sentbyuid int(3) NOT NULL,
    datetime datetime NOT NULL,
    beread tinyint,
    PRIMARY KEY (id)
);


