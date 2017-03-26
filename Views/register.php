<?php

session_start();

if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
   header("Location: __dir__/../index.php");
   exit();
} 

?>

<!DOCTYPE HTML
    <html lang="pl">
<head>
    <meta charset ="utf-8" />
    <link type ="text/css" rel ="stylesheet" href ="style.css">
    <title> Rejestracja w Tłiterze! </title>
</head>
<body>
    <p> <h4> Załóż nowe konto! </h4> <p>
    <form action = "__dir__ . /../../Controller/registering.php" method = "POST"> 
        <div>
            Twoja nazwa użytkownika: <br> <input type="text" name ="name"> <br>
            Twój email jako login: <br> <input type="email" name ="email"> <br>
            Twoje hasło: <br> <input type ="password" name ="password"> <br>
            Powtórz hasło: <br> <input type ="password" name ="password2"> <br>
        <input type ="submit" value ="Uwij swoje gniazdko!">
       
    </form>
    
     </div>
        
<?php
echo 'Świetnie, że chcesz się u nasz zagnieździć!';

if(isset($_SESSION['registerError'])) {
    echo $_SESSION['registerError'];
    
}
?>
</body>
</html>