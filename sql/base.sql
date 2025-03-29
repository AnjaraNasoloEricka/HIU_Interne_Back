create database HIU;
use HIU;

create table User
(
    id_user int auto_increment primary key,
    name varchar(100),
    password varchar(100)
);

create table Article
(
    id_article int auto_increment primary key,
    title varchar(255),
    description text,
    date_create Date,
    date_modification Date
);