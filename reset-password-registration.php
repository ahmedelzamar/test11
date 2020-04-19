<?php
session_start();
include 'connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    unset($_SESSION['errors']);
    unset($_SESSION['data']);

    $password = htmlspecialchars(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $confirmPassword = htmlspecialchars(filter_var($_POST['confirm-password'], FILTER_SANITIZE_STRING));


    $error_msg = [];

    if(empty($password) || strlen($password) < 5)
        $error_msg[] = 'password must be more than 5 char';

    if($password!==$confirmPassword){
        $error_msg[] = 'password not match';
    }

    if(empty($error_msg)){

        $stmt = $con->prepare('UPDATE `users` SET `password` = ? WHERE `username` = ? ');
        $stmt->execute(array(password_hash($password, PASSWORD_DEFAULT), $_SESSION['resetUser']));
        unset($_SESSION['resetUser']);
        unset($_SESSION['resetStatus']);
        header('Location: login.php');
        exit();
    }else{
        $_SESSION['errors'] = $error_msg;
        header('Location: reset-password.php');
        exit();
    }


}else{
    header('Location: reset-password.php');
    exit();
}

