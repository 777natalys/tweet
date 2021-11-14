<?php
include_once 'functions.php';

session_destroy();//удаляем сессию о пользоваателе

header("Location: " . get_url());
die;