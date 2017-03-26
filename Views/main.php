<?php

session_start();
   
if ((!isset($_SESSION['logged'])) || ($_SESSION['logged']) == false) {
    header('Location: log.php');
    exit();
}
// var_dump($_SESSION);
?>

<!DOCTYPE HTML>
    <html lang="pl">
<head>
    <meta charset ="utf-8" />
    <link type ="text/css" rel ="stylesheet" href ="style.css">
    <title> Main Tłitera Alexa - strona główna'.' </title>
</head>
<body>
    
 <?php
 
 require_once '__dir__/../../Controller/config.php';
 require_once '__dir__/../../Model/Tweet.php'; 
 require_once '__dir__/../../Model/User.php';

 
 echo 'Witaj <b> ' . $_SESSION['username'] . '</b> na Tłiterze <br>';
 echo 'Zobacz co nowego piszczy w trawie u znajomych: <br> <br>';

 
$tweets = Tweet::loadAllTweets($conn);
foreach($tweets as $oneTweet) {
    $user = User::loadUserById($conn, ($oneTweet -> getUserId()) ) -> getUsername();
    $tweetId = ($oneTweet -> getId());
    echo '<table> <tr>';
    echo '<td> Użytkownik <b>' . $user . '</b> </td>' ;
    echo '<td> <a href = "post.php?tweetId=' . $tweetId . '"> napisał </a> </p>';
    echo '<td>' . $oneTweet -> getText() . '<td>';
    echo '</tr> </table> ';
    
}

//var_dump($tweets);

  ?>
    
    <form action ="" method="POST">
        <fieldset>
        <label>
            Co chcesz dziś zaśpiewać? <br>
            <textarea name ="newTweet" cols ="30" rows ="5" maxlength="140" 
                      placeholder ="Zaćwierkaj!"> 
            </textarea>
            <br>
            <input type ="submit" value ="Zapisz swój trel!">
    
        </label>
        </fieldset>
    </form>
    
 <?php
 if(isset($_POST['newTweet'])){ 
     $newTweetText = $_POST['newTweet'];
     $newTweet = new Tweet();
     $newTweet -> setUserId($_SESSION['id']);
     $newTweet -> setCreationDate(); //-> format('Y-m-d H:i:s');
     $newTweet -> setText($_POST['newTweet']);
     $newTweet -> saveToDB($conn);
     header ("Refresh: 0"); 
 }

 
 echo '<br> <p> Zalogowany jako: ' 
        . '<a href = "user.php">' . $_SESSION['email'] . '</a>  '
        . '<-Zobacz swoją stronę profilową </p>' ;
  echo '<p> Wyleć z gniazda: ' .
         '<a href = "../Controller/logout.php"> Wyloguj się </a> </p> ';
 ?>           
</body>
</html>