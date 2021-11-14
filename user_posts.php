<?php
//вывод твитов выбранного пользователя
include_once "includes/functions.php";

$error_message = get_error_message();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id']; //если не авторизован, то берем из урла
} else if (isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])) {
    $id = $_SESSION['user']['id']; //если авторизован пользователь, берем ид через сессию
} else {
    $id = 0;
}// $id = $_GET['id'] ?? $_SESSION['user']['id'] ?? 0; короткая запись
$posts = get_posts($id);

$title = 'Твиты пользователя';

if (!empty($posts)) $title = 'Твиты пользователя @' . $posts[0]['login'];

include_once "includes/header.php";
if(loggeed_in()) include 'includes/tweet_form.php';
include_once "includes/posts.php";
include_once "includes/footer.php";
