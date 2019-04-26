create table comments (
  comid int auto_increment primary key,
  username varchar(40),
  date datetime ,
  comment text
);

create table vocabulary (
vid int auto_increment primary key,
vocabulary varchar(32) unique
);

create table terms (
tid int auto_increment primary key,
vid int,
name varchar(64)
);

create table field_categories (
entity_id int,
entity_type varchar(32),
term_id int,
primary key (entity_id, entity_type, term_id)
);

create table field_authors (
entity_id int,
entity_type varchar(32),
term_id int,
primary key (entity_id, entity_type, term_id)
);

insert into vocabulary (vocabulary)
values ('categories'), ('authors');

create table books(
id int not null auto_increment,
title varchar(256),
description text,
rating decimal(10,2),
ISBN_13 varchar(13),
ISBN_10 varchar(10),
image varchar(255),
language varchar(32),
price decimal(10,2),
currency varchar(32),
buy_link varchar(255),
primary key(id)
);

CREATE TABLE admin(
  admin_id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(128),
  password VARCHAR(64),
  primary key (admin_id)
);

create table configuration (
name varchar (255) ,
value LONGBLOB,
primary key (name)
);

CREATE TABLE users (
  user_id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(40) NULL,
  password VARCHAR(32) NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE (username)
);

create table wish_books(
  user_id INT not null ,
  ISBN_13 varchar(13),
  PRIMARY key (user_id , ISBN_13)
);
