<?php
include_once "includes/functions.php";
$posts = get_liked_posts();
$title = 'Понравившиеся твиты.';

$error_message = get_error_message();

include_once "includes/header.php";
include_once "includes/posts.php";
include_once "includes/footer.php";