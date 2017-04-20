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

 // --- nagłówek ---
 echo '<p> Witaj <b> ' . $_SESSION['username'] . '</b> na Tłiterze <br>';
 echo 'Zobacz co nowego piszczy w trawie u znajomych: <br> <br> </p>';
 
//--- pętla do wyświetlenia tweetów/postów ---
 
$tweets = Tweet::loadAllTweets($conn);
foreach($tweets as $oneTweet) {
    $userName = User::loadUserById($conn,($oneTweet -> getUserId()) ) -> getUsername();
    $tweetId = ($oneTweet -> getId());
    echo '<table> '
        . '<tr> <td>'
        . 'Użytkownik <a href = "user.php?id=' . $oneTweet -> getUserId() 
            . '">' . $userName . '</a>'
        . ' <a href = "post.php?tweetId=' . $tweetId . '"> wyśpiewał: </a>'
        . ' </td> </tr>';
    echo '<tr> <td>' .  $oneTweet -> getText() . '</td> </tr>';
    
    //--- Komentarze ---
    
     // do zmiennej wrzucam HTML z formularzem komentarza do tweeta i staram się go przypiąć
    $commentForm = 
<<<EOD
            
    <tr> <td> <form action ="" method="POST">
        <fieldset>
        <label>
            Wytrelaj komentarz:
            <input type="text" name ="newComment" maxlength=140 
                      placeholder ="Odćwierkaj!"> 
            
            <input type ="submit" value ="Wyślij!">
            <input type="hidden" name="tweetId" value="$tweetId">
                
        </label>
        </fieldset>
    </td> </tr> </form> 
EOD;
    
    echo '<br> <tr> <td> <br> Komentarze do tego posta: </td> </tr>';
    $allcomments = Comment::loadAllCommentsByPostId($conn, $tweetId);
    
    if(count($allcomments) > 0) {
        foreach($allcomments as $comment) {
            $authorOfComment = User::loadUserById($conn, $comment -> getUserId());
            echo '<tr> <td> <br> Autor <a href="user.php?id='.$authorOfComment->getId().'">'
                    .$authorOfComment->getUsername().'</a>  odśpiewał: </td> </tr>';
            echo '<tr> <td> "' . $comment->getText() . '" </td> </tr>';
        }
    } else {
        echo "<tr> <td> Cisza w lesie! </td> </tr>";
    }
    
    echo '<tr> <td>' . $commentForm . '</td> </tr>';
    echo '</table>';
     
}

// --- nowy tweet w HTML ---
 
?>
    <p>
      <form action ="" method="POST">
        <fieldset>
        <label>
            Co chcesz dziś zaśpiewać? <br>
            <textarea name ="newTweet" cols ="30" rows ="5" maxlength=140 
                      placeholder ="Zaćwierkaj!"> 
            </textarea>
            <br>
            <input type ="submit" value ="Zapisz swój trel!">
    
        </label>
        </fieldset>
    </form>  
    </p>
        
 <?php
 
 //if do tworzenia nowych komentarzy
 
 if(isset($_POST['newComment'])){ 
     //$newCommentText = $_POST[$tweetId];
     $newComment = new Comment();
     $newComment -> setUserId($_SESSION['id']);
     $newComment -> setPostId($_POST['tweetId']);      //co mu tutaj przypisać?
     $newComment -> setCreationDate(); //-> format('Y-m-d H:i:s');
     $newComment -> setText($_POST['newComment']);
     $newComment -> saveToDB($conn);
     // odświeżanie strony po uploadzie tweeta
     echo "<meta http-equiv='refresh' content='0'>";
 }
 
 // if do tworzenia nowych tweetów
 if(isset($_POST['newTweet'])){ 
     //$newTweetText = $_POST['newTweet'];
     $newTweet = new Tweet();
     $newTweet -> setUserId($_SESSION['id']);
     $newTweet -> setCreationDate(); //-> format('Y-m-d H:i:s');
     $newTweet -> setText($_POST['newTweet']);
     $newTweet -> saveToDB($conn);
     // odświeżanie strony po uploadzie tweeta
     echo "<meta http-equiv='refresh' content='0'>";
 }

 
 echo '<br> <p> Zalogowany jako: ' 
        . '<a href = "user.php">' . $_SESSION['email'] . '</a>  '
        . '<-Zobacz swoją stronę profilową </p>' ;
  echo '<p> Wyleć z gniazda: ' .
         '<a href = "../Controller/logout.php"> Wyloguj się </a> </p> ';
 ?>           
</body>
</html>