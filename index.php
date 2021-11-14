<?php
include_once "includes/functions.php";
$posts = get_posts();
$title = 'Главная страница';

$error_message = get_error_message();

include_once "includes/header.php";
if (loggeed_in()) include "includes/tweet_form.php";
include_once "includes/posts.php";
include_once "includes/footer.php";
