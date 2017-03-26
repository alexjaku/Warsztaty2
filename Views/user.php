<?php
session_start();
   
if ((!isset($_SESSION['logged'])) || ($_SESSION['logged']) == false) {
    header('Location: /../Views/log.php');
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

 
 echo 'Witaj <b> ' . $_SESSION['username'] . '</b> na swojej stronie użytkownika <br>';
 echo 'Przejrzyj wszystkie wiadomości: <br> <br>';

 
$tweets = Tweet::loadAllTweetsByUserId($conn, $_SESSION['id']);
// var_dump($tweets);
foreach($tweets as $oneTweet) {
    $date = ($oneTweet -> getCreationDate());
    $tweetId = ($oneTweet -> getId());
            
    echo '<table> <tr>';
    echo '<td> Dnia ' . $date . ' napisałaś/napisałeś: </td>' ;
    echo '<td>' . $oneTweet -> getText() . '<td>';
    echo '<td> <a href = "post.php?tweetId=' . $tweetId . '"> Szczegóły </a> </p> ';
    echo '</tr> </table> ';
   }
echo '<p> Wyleć z gniazda: ' .
         '<a href = "__dir__/../../Controller/logout.php"> Wyloguj się </a> </p> ';
//var_dump($tweets);

  ?>
    
    
</body>
</html>