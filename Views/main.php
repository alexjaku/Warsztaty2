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
 echo 'Witaj <b> ' . $_SESSION['username'] . '</b> na Tłiterze <br>';
 echo 'Zobacz co nowego piszczy w trawie u znajomych: <br> <br>';
 
//--- pętla do wyświetlenia tweetów/postów ---
 
$tweets = Tweet::loadAllTweets($conn);
foreach($tweets as $oneTweet) {
    $user = User::loadUserById($conn, ($oneTweet -> getUserId()) ) -> getUsername();
    $tweetId = ($oneTweet -> getId());
    echo '<table> <tr>';
    echo '<td> Użytkownik </td>' ;
    echo '<td> <a href = "user.php?id=' . $oneTweet -> getUserId() 
            . '">' . $user . '</a> </td>' ;
    echo '<td> <a href = "post.php?tweetId=' . $tweetId . '"> wyśpiewał: </a> </td>';
    echo '<td> "' .  $oneTweet -> getText() . '" </td> ';
    
    //-------Komentarze--------
    
    // do zmiennej wrzucam HTML z formularzem komentarza do tweeta i staram się go przypiąć
    $commentForm = 
<<<EOD
            
    <div> <form action ="" method="POST">
        <fieldset>
        <label>
            Komentarz:
            <textarea name ="newComment" cols ="22" rows ="1" maxlength="140" 
                      placeholder ="Odćwierkaj!"> 
            </textarea>
            <input type ="submit" value ="Wyślij!">
            <input type="hidden" name="tweetId" value="$tweetId">
                
        </label>
        </fieldset>
    </div> </form> 
EOD;
    
       
    echo '<tr> Komentarze do tego posta: <br> </tr>';
    
    echo ' <tr>';    
    $allcomments = Comment::loadAllCommentsByPostId($conn, $tweetId);
    
    if(count($allcomments) > 0){
        foreach($allcomments as $comment){
            $authorOfComment = User::loadUserById($conn, $comment->getUserId());
            echo '<div><span> Author:<a href="user.php?id='.$authorOfComment->getId().'">'.$authorOfComment->getUsername().'</a>  ';
            echo $comment->getCreationDate() . '</span><br>';
            echo $comment->getText().'</div>';
            
        }
    } else {
        echo "Brak komentarzy do wyświetlenia";
    }
    
    echo '</tr> <br>';
    echo '<tr> <td>' . $commentForm . '</td> </tr> </table>';
  
}
// --------------- nowy tweet w HTML-----------
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