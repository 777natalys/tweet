<?php
include_once "includes/functions.php";

if(loggeed_in()) redirect(get_url());

$title = 'Регистрация';
$error_message = get_error_message();

include_once "includes/header.php";
include_once "includes/register_form.php";
include_once "includes/footer.php";
