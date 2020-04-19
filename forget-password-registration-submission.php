<?php
session_start();
include 'connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    unset($_SESSION['errors']);
    unset($_SESSION['data']);

    $answer = htmlspecialchars(filter_var($_POST['answer'], FILTER_SANITIZE_STRING));
    $QID = $_POST['QID'];


    if($QID !== "1" && $QID !== "2" && $QID !== "3" && $QID !== "4" ){
        $error_msg[] = 'Please select one of the security questions';
    }

    if(empty($answer) )
        $error_msg[] = 'Please enter your answer to the security question';


    $data = [
        'answer' => $answer,
    ];

    if(empty($error_msg)){
        $stmt = $con->prepare('SELECT `questionID`, `answer` FROM questionanswer WHERE username = ?');
        $stmt->execute(array($_SESSION['resetUser']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row['questionID'] == $QID && $row['answer'] == $answer){
            $_SESSION['resetStatus'] = true;
            header('Location: reset-password.php');
            exit();
        }else{
            $error_msg[] = 'Your question and your answer is not match';
            $_SESSION['errors'] = $error_msg;
            $_SESSION['data'] = $data;
            header('Location: forget-password-registration.php');
            exit();
        }
    }else{
        $_SESSION['errors'] = $error_msg;
        $_SESSION['data'] = $data;
        header('Location: forget-password-registration.php');
        exit();
    }


}else{
    header('Location: forget-password-registration.php');
    exit();
}

