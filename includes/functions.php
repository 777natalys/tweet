<?php
include_once "config.php";

function debug($var, $stop = false)
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    if ($stop) die;
}

//функция формирование правильной ссылки
function get_url($page = '')
{
    return HOST . "/$page";
}

//функция формирования правильного заголовка
function get_page_title($title = '')
{
    if (!empty($title)) {
        return SITE_NAME . " - $title";
    } else {
        return SITE_NAME;
    }
}

//функция перенаправки
function redirect($link = HOST) {
    header("Location: $link");
    die;
}

//function подключения к бд
function db()
{
    try {
        return  new PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

//запрос k бд
function db_query($sql = '', $exec = false)
{
    if (empty($sql)) return false;

    if ($exec) {
        return db()->exec($sql);
    }

    return db()->query($sql);
}

//вывод твитов
function get_posts($user_id = 0)
{
    //вывод твитов выбранного пользователя
    if ($user_id > 0) return db_query("SELECT posts.*, users.name, users.login, users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id WHERE posts.user_id = $user_id;")->fetchAll();
    //запрс с соединением таблиц с определенным условием 
    return db_query("SELECT posts.*, users.name, users.login, users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id;")->fetchAll();
}

//функция возвращения информации о пользователе из бд
function get_user_info($login)
{
    return db_query("SELECT * FROM `users` WHERE `login` = '$login';")->fetch();
}

//добавление пользователя в бд
function add_user($login, $pass)
{
    $login = trim($login);
    $name = ucfirst($login);
    $password = password_hash($pass, PASSWORD_DEFAULT);
    return db_query("INSERT INTO `users` (`id`, `login`, `pass`, `name`) VALUES (NULL, '$login', '$password', '$name');", true); //так запрос ничего не возвращает exec в true
}

//различные проверки 
function register_user($auth_date)
{
    if (
        empty($auth_date) || !isset($auth_date['login']) || empty($auth_date['login']) ||
        !isset($auth_date['pass']) || empty($auth_date['pass']) ||
        !isset($auth_date['pass2']) || empty($auth_date['pass2'])
    ) return false;

    //обработка ошибки когда пользователь уже есть в бд   
    $user = get_user_info($auth_date['login']);
    if (!empty($user)) {
        $_SESSION['error'] = 'Пользователь ' . $auth_date['login'] . ' уже существует!';
        //header("Location: " . get_url('register.php'));
        //die;
        redirect(get_url('register.php'));
    }

    //обработка ошибки если пароли не совпадают
    if ($auth_date['pass'] !== $auth_date['pass2']) {
        $_SESSION['error'] = 'Пароли не совпадают!';
        //header("Location: " . get_url('register.php'));
       // die;
       redirect(get_url('register.php'));
    }

    if(add_user($auth_date['login'], $auth_date['pass'])) {
        //header("Location: " . get_url());
        //die;
        redirect(get_url());
    }

    // debug($auth_date, true);

}

//принимает информацию с формы
function login($auth_date)
{
    if(empty($auth_date) || !isset($auth_date['login']) || empty($auth_date['login']) ||
    !isset($auth_date['pass']) || empty($auth_date['pass'])) 
    return false;

    $user = get_user_info($auth_date['login']);
    //проверка а если такой пользователь
    if(empty($user)) {
        $_SESSION['error'] = 'Не правильно ввели логин или пароль!';
        //header("Location: " . get_url());
        //die;
        redirect(get_url());
    }

    //проверка пароля
    if(password_verify($auth_date['pass'], $user['pass'])) {
        $_SESSION['user'] = $user;
        $_SESSION['error'] = '';
        //header("Location: " . get_url('user_posts.php?id=' . $_SESSION['user']['id']));//$user['id']
        //die;
        redirect(get_url('user_posts.php?id=' . $user['id']));
    } else {
        $_SESSION['error'] = 'Не правильно ввели логин или пароль!';
        //header("Location: " . get_url());
        //die;
        redirect(get_url());
    }
   
}

//сообщенние об ошибке
function get_error_message() {
    $error = '';
    if(isset($_SESSION['error']) && !empty($_SESSION['error'])) {
        $error = $_SESSION['error'];
        $_SESSION['error'] = ''; //очищаем сессию, чтоб ошибка не висела
    }

    return $error;
}

function loggeed_in() {
    return isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']);
}

//добавление поста
function add_post($text, $image) {
    $text = trim($text);
    //проверка на длинну строку.текста в микробайтах
    if(mb_strlen($text) > 255) {
        $text = mb_substr($text, 0, 250) . '...';//вернет подстроку с 0 по 250
    }

    $user_id = $_SESSION['user']['id'];
    $sql = "INSERT INTO `posts` (`id`, `user_id`, `text`, `image`) 
    VALUES (NULL, '$user_id', '$text', '$image');";
    return db_query($sql, true);//так как запрос ничего не возвращает указываем true
    debug($sql, true);
}
