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
 require_once '__dir__/../../Model/Comment.php';

 
 echo 'Witaj <b> ' . $_SESSION['username'] . '</b> na Tłiterze <br>';
 echo 'Zobacz co nowego piszczy w trawie u znajomych: <br> <br>';

 // do zmiennej wrzucam HTML z formularzem komentarza do tweeta i staram się go przypiąć
 $tweetId = 0;
$commentForm = 
<<<EOD

         <form action ="" method="POST">
        <fieldset>
        <label>
            Komentarz:
            <textarea name =" . $tweetId . " cols ="22" rows ="1" maxlength="140" 
                      placeholder ="Odćwierkaj!"> 
            </textarea>
            <input type ="submit" value ="Wyślij!">
                
        </label>
        </fieldset>
    </form> 
EOD;

//pętla do wyświetlenia tweetów/postów
$tweets = Tweet::loadAllTweets($conn);
foreach($tweets as $oneTweet) {
    $user = User::loadUserById($conn, ($oneTweet -> getUserId()) ) -> getUsername();
    $tweetId = ($oneTweet -> getId());
    echo '<table> <tr>';
    echo '<td> Użytkownik <b>' . $user . '</b> </td>' ;
    echo '<td> <a href = "post.php?tweetId=' . $tweetId . '"> napisał </a> </p>';
    echo '<td>' . $oneTweet -> getText() . '</td>';
    echo '<td>' . $commentForm . '</td>';
    echo '</tr> </table> ';
        
    var_dump($_POST["$tweetId"]);
  
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

 //if do tworzenia nowych komentarzy
 
 if(isset($_POST[$tweetId])){ 
     //$newCommentText = $_POST[$tweetId];
     $newComment = new Comment();
     $newComment -> setUserId($_SESSION['id']);
     $newComment -> setPostId($_POST[$tweetId]);                //co mu tutaj przypisać?
     $newComment -> setCreationDate(); //-> format('Y-m-d H:i:s');
     $newComment -> setText($_POST["$tweetId"]);
     $newComment -> saveToDB($conn);
     header ("Refresh: 0"); 
 }
 
 // if do tworzenia nowych tweetów
 if(isset($_POST['newTweet'])){ 
     //$newTweetText = $_POST['newTweet'];
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