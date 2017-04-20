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
 
 // jeśli GETem to wyświetlamy danego użytkownika
if(isset($_GET['id'])) {
    $authorOfComment = User::loadUserById($conn, $_GET['id']);
    echo 'Witaj na stronie użytkownika <b> ' . $authorOfComment -> getUsername() . '</b>  <br>';
    echo 'Przejrzyj wszystkie ptasie koncerty tego użytkowika: <br> <br>';

    $tweets = Tweet::loadAllTweetsByUserId($conn, $authorOfComment ->getId());
    
    if(count($tweets) > 0){ 
        foreach($tweets as $oneTweet) {
           $date = ($oneTweet -> getCreationDate());
           $tweetId = ($oneTweet -> getId());

           echo '<table> <tr>';
           echo '<td> Dnia ' . $date . ' napisał: </td>' ;
           echo '<td>' . $oneTweet -> getText() . '<td>';
           echo '<td> <a href = "post.php?tweetId=' . $tweetId . '"> Szczegóły </a> </p> ';
           echo '</tr> </table> ';
        }
    } else {
        echo "Brak tweetów do wyświetlenia!";
    }
   
    echo '<br> <p> Zalogowany jako: ' 
    . '<a href = "user.php">' . $_SESSION['email'] . '</a>  '
    . '<-Zobacz swoją stronę profilową </p>' ;
}

// --- jeśli nie GETem to wyświetlam zalogowanego użytkownika
else {
    echo 'Witaj <b> ' . $_SESSION['username'] . '</b> na swojej stronie użytkownika <br>';
    echo 'Przejrzyj wszystkie wszystkie swoje ptasie koncerty: <br> <br>';

   $tweets = Tweet::loadAllTweetsByUserId($conn, $_SESSION['id']);
     
    if(count($tweets) > 0){ 
        foreach($tweets as $oneTweet) {
            $date = ($oneTweet -> getCreationDate());
            $tweetId = ($oneTweet -> getId());

            echo '<table> <tr>';
            echo '<td> Dnia ' . $date . ' napisałaś/napisałeś: </td>' ;
            echo '<td>' . $oneTweet -> getText() . '<td>';
            echo '<td> <a href = "post.php?tweetId=' . $tweetId . '"> Szczegóły </a> </p> ';
            echo '</tr> </table> ';
           }
    } else {
        "Brak tweetów do wyświetlenia!";
    }
    
}

echo '<p> Powrót do głównego gniazda: '
        . '<a href = "main.php"> Strona główna </a> ';
echo '<p> Opuść gniazdo: ' .
        '<a href = "__dir__/../../Controller/logout.php"> Wyloguj się </a> </p> ';


?>
    
    
</body>
</html>