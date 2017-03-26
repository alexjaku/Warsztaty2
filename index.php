<?php

session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true){
    header("Location: Views/main.php");
    exit();
} else {
    header("Location: Views/log.php");
    exit();
}