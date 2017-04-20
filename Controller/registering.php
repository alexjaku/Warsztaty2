<?php

session_start();
 //sprawdzam czy każde pole jest wypełnione
if ((!isset($_POST['name']))
        || (!isset($_POST['email'])) 
        || (!isset($_POST['password']))
        || (!isset($_POST['password2']))
    ) {
        $_SESSION['error'] = '<br><span style="color:red">Brak czegoś! <br> Spróbuj ponownie</span>';
        header('Location: __dir__/../Views/register.php');
        exit();
} 

require_once(__dir__ . "/../Model/User.php");
require_once("config.php");

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$newUser = new User; 

if(($_POST['password'] === $_POST['password2']) &&
        // wczytuję usera o podanym loginie, jeśli jest null, to ten login jest nowy
    (($newUser ->loadUserByEmail($conn, $email)) == null) &&
        //sprawdzam czy nick jest dłuższy równy 4
    strlen($name) >= 4) {
     
    //tworzę usera o podanych danych
    $newUser ->setUsername($name);
    $newUser ->setEmail($email);
    $newUser ->setPassword($_POST['password']);
    $newUser ->saveToDB($conn);
    
    unset($_SESSION['registerError']);
    
    //$user = $tmp -> loadUserByEmail($conn, $email);
    $_SESSION['logged'] = true; 
    $_SESSION['id'] = $newUser ->getId();
    $_SESSION['username'] = $newUser ->getUsername();
    $_SESSION['email'] = $newUser ->getEmail();

        header('Location: ../index.php');
        exit();

    
    
} else {
    
    $_SESSION['registerError'] = '<br><span style="color:red">Błąd przy rejestracji!'
            . ' Albo podany użytkownik istnieje, albo hasła się różnią! <br> Spróbuj ponownie</span>';
    header('Location: ../Views/register.php');
    
}
        
$conn = null;
?>
    