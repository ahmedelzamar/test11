<?php
session_start();
include 'connection.php';


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    unset($_SESSION['errors']);
    unset($_SESSION['data']);

    $username = htmlspecialchars(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $password = htmlspecialchars(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $error_msg = [];

    if(empty($username)){
        $error_msg[] = 'Please Enter username';
    } else{
        $stmt = $con->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute(array($username));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userCount = $stmt->rowCount();
        if($userCount == 1 ){
            $hashedPasswordCheck = password_verify($password, $row['password']);
            if($hashedPasswordCheck){
                $_SESSION['username'] = $row['username'];
                header('Location: inbox.php');
                exit();
            }else
                $error_msg[] = 'Username Or Password Not Matched';
        }else
            $error_msg[] = 'Username Or Password Not Matched';
    }
    $data = [
        'username' => $username
    ];

    $_SESSION['errors'] = $error_msg;
    $_SESSION['data'] = $data;
    header('Location: login.php');
    exit();

}else{
    header('Location: login.php');
    exit();
}