<?php
session_start();
   
if ($_SESSION['logged'] !=true) {
    header("Location: log.php");
    exit();
}
?>

<!DOCTYPE HTML
    <html lang="pl">
<head>
    <meta charset ="utf-8" />
     <link type ="text/css" rel ="stylesheet" href ="style.css">
    <title> user - strona użytkownika </title>
</head>
<body>
    
 <?php
 
 require_once(__DIR__ . '/../Controller/config.php');
 require_once(__DIR__ . '/../Model/Tweet.php'); 
 require_once(__DIR__ . '/../Model/User.php');

if ('GET' === $_SERVER['REQUEST_METHOD']) {
    if(isset($_GET['tweetId']) === true) {
        
        echo 'Post o ID ' . $_GET['tweetId'] . ':';
        $tweet = Tweet::loadTweetById($conn, $_GET['tweetId']);

        $text = $tweet ->getText();
        $date = $tweet ->getCreationDate();

        echo '<br> Treść Twojego świergotania: ' . $text;
        echo '<br> Data i czas publikacji: ' . $date;
    }
        
}
 else {
    header('Location: /../index.php');
    exit();
}

 
echo '<p> Wyleć z gniazda: ' .
         '<a href = "__dir__/../../Controller/logout.php"> Wyloguj się </a> </p> ';
//var_dump($tweets);

  ?>
    
    
</body>
</html>