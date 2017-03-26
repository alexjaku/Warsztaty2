<?php

session_start();

if ((!isset($_POST['email'])) || (!isset($_POST['password']))) {
    header('Location: ../Views/log.php');
    exit();
} else if ($_POST['email'] == null || $_POST['password']) {
    $_SESSION['logged'] = false;
    $_SESSION['error'] = '<br><span style="color:red">Brak emailu lub hasła! <br> Spróbuj ponownie</span>';
    header('Location: ../Views/log.php');;;
}

require_once 'config.php';
require_once '../Model/User.php';

$email = $_POST['email'];
$password = $_POST['password'];


$tmp = new User();
$user = $tmp -> loadUserByEmail($conn, $email);

var_dump($user);
//var_dump($conn);
//var_dump($user ->getHashPass());
var_dump(password_verify($password, ($user ->getHashPass())));
   
if(is_object($user)) {
    if (password_verify($password, ($user ->getHashPass()))) {
        $_SESSION['logged'] = true; 
        $_SESSION['id'] = $user ->getId();
        $_SESSION['username'] = $user ->getUsername();
        $_SESSION['email'] = $user ->getEmail();

    
        unset($_SESSION['error']);
        header('Location: ../index.php');
        exit();

        } else {
            $_SESSION['logged'] = false; 
            $_SESSION['error'] = '<br><span style="color:red">Nieprawidłowy login lub hasło! <br> Spróbuj ponownie</span>';
            header('Location: ../Views/log.php');
        }
} else {
    $_SESSION['logged'] = false;
    $_SESSION['email'] = '<br><span style="color:red">Coś źle z obiektem! <br> Spróbuj ponownie</span>';
            header('Location: ../Views/log.php');
}

$conn = null;