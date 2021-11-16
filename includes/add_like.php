<?php
//if (isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])) {
//    $id = $_SESSION['user']['id']; //если авторизован пользователь, берем ид через сессию
//}

include_once "functions.php";

//debug($_POST, true);


if(!loggeed_in()) redirect();

if(isset($_GET['id']) && !empty($_GET['id'])) {
    //если был текст сообщения. проверяем на ошибку добавления 
    if(!add_like($_GET['id'])) {
        $_SESSION['error'] ='Во время добавления лайка, что то пошло не так.';
    }
}

redirect();