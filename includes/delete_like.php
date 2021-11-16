<?

include_once "functions.php";

//debug($_POST, true);


if(!loggeed_in()) redirect();

if(isset($_GET['id']) && !empty($_GET['id'])) {
    //если был текст сообщения. проверяем на ошибку добавления 
    if(!delete_like($_GET['id'])) {
        $_SESSION['error'] ='Во время удаления лайка, что то пошло не так.';
    }
}

redirect();