<?php
session_start();
include 'connection.php';


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    unset($_SESSION['errors']);
    unset($_SESSION['data']);

    $toUser = htmlspecialchars(filter_var($_POST['toUser'], FILTER_SANITIZE_STRING));
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    $error_msg = [];

    if(empty($toUser)){
        $error_msg[] = 'Please Enter username to send the message to';
    } else{
        $stmt = $con->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute(array($toUser));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userCount = $stmt->rowCount();
        if(!$userCount == 1 ){
            $error_msg[] = 'Username Not Matched';
        }
    }
    if(empty($content)){
        $error_msg[] = 'Please Enter The content Of Your Message';
    }
    $data = [
        'toUser' => $toUser,
        'content' => $content
    ];
    if(empty($error_msg)){
        $stmt = $con->prepare('INSERT INTO messages (`fromUser`, `toUser`, `content`, `readingStatus`, `date`) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['username'], $toUser, $content, 0, date("Y-m-d H:i:s")]);
        $_SESSION['message'] = "Your Message sent successfully";
        header('Location: inbox.php');
        exit();
    }else{
        $_SESSION['errors'] = $error_msg;
        $_SESSION['data'] = $data;
        header('Location: send-message.php');
        exit();
    }

}else{
    header('Location: send-message.php');
    exit();
}