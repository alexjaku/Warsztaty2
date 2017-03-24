<?php

//session_start();
//   
//if ((!isset($_SESSION['logged'])) || ($_SESSION['logged']) == false) {
//    header('Location: Views/log.php');
//    exit();
// 
   
?>

<!DOCTYPE HTML
    <html lang="pl">
<head>
    <meta charset ="utf-8" />
    <link type ="text/css" rel ="stylesheet" href ="Views/style.css">
    <title> Index Tłitera Alexa - strona główna'.' </title>
</head>
<body>
    Witaj na Tłiterze! <br>
    Zobacz co nowego piszczy w trawie u znajomych: <br> <br>
 <?php
 
 require_once 'Controller/config.php';
 require_once 'Model/Tweet.php'; 
 require_once 'Model/User.php';

 

 
$tweets = Tweet::loadAllTweets($conn);
foreach($tweets as $oneTweet) {
    $user = User::loadUserById($conn, ($oneTweet -> getUserId()) ) -> getUsername();
    echo '<table> <tr>';
    echo '<td> Użytkownik <b>' . $user . '</b> napisał: </td>' ;
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
     $newTweet = $_POST['newTweet'];
     
 }


 ?>           
</body>
</html>