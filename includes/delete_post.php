<?php
include_once "functions.php";

//debug($_POST, true);


if(!loggeed_in()) redirect();

if(isset($_GET['id']) && !empty($_GET['id'])) {
    //если был текст сообщения. проверяем на ошибку добавления поста
    if(!delete_post($_GET['id'])) {
        $_SESSION['error'] ='Во время удаления поста, что то пошло не так.';
    }
}

redirect(get_url('user_posts.php'));