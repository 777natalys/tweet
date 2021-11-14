<?php 

define('SITE_NAME', 'Twitter');
define('HOST', "http://" . $_SERVER['HTTP_HOST']); //""адрес нашего хоста "http://localhost"ДЕЛАЕМ ЕЕ универсальной

define('DB_HOST', '127.0.0.1');// или'localhost' urlhost myadmin db 
define('DB_NAME', 'twitter'); //name db
define('DB_USER', 'root');
define('DB_PASS', '');

session_start();