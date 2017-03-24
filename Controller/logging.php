<?php

session_start();

if ((!isset($_POST['email'])) || (!isset($_POST['password']))) {
    header('Location: ../Views/log.php');
    exit();
}

require_once 'config.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = 'SELECT `email`, `password` FROM `Users` WHERE '
        . 'email = :email AND password = :password' ;
    $stmt = $conn -> prepare($sql);
    
    $stmt -> execute();
    $row = $stmt -> fetch();
    if ($stmt -> rowCount = 1 && 
            (password_verify($password, $row['password']) == true)) {
        $_SESSION['logged'] = true; 
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['id'] = $row['id'];
        
        unset($_SESSION['error']);
        header('Location: ../index.php');
    } else {
        $_SESSION['logged'] = false; 
        $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło! <br> Spróbuj ponownie</span>';
        header('Location: ../Views/log.php');
    }

$conn = null;