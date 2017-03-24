<?php

session_start();

?>

<!DOCTYPE HTML
    <html lang="pl">
<head>
    <meta charset ="utf-8" />
    <link type ="text/css" rel ="stylesheet" href ="style.css">
    <title> Log - Zaloguj się do Tłitera! </title>
</head>
<body>
    <form action = "../Controller/logging.php" method = "POST"> 
        <div>
        Twój email jako login: <br> <input type="email" name ="email"> <br>
        Twoje hasło: <br> <input type ="password" name ="password"> <br>
        <input type ="submit" value ="Zaloguj się w swoim gniazdku!">
       
    </form>
    
<?php

if(isset($_SESSION['error'])) {
    echo $_SESSION['error'];
}
echo '<p> Pierwszy raz u nas? Utwórz konto: ' .
         '<a href = "register.php"> Rejestracja </a> </p> ';
?>
    
     </div>
</body>
</html>