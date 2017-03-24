<?php

require_once '../Model/User.php';

require_once 'config.php';

$tweetPierwszyDB = new User; 
$tweetPierwszyDB ->setUsername('alexjaku');
$tweetPierwszyDB ->setEmail('alexjaku@mm.pl');
$tweetPierwszyDB ->setPassword('nieogarniam');
$tweetPierwszyDB ->saveToDB($conn);

//$ja2 = new User; 
//$ja2 ->setUsername('kasiex');
//$ja2 ->setEmail('kasiex@mm.pl');
//$ja2 ->setPassword('nieogarniam');
//$ja2 ->saveToDB($conn);

//var_dump(User::loadUserById($conn, 1));
//
//User::loadUserById($conn, 1) -> setUserName('alexj') -> saveToDB($conn); 
//
//var_dump(User::loadUserById($conn, 1));
//var_dump(User::loadAllUsers($conn));
//
//User::loadUserById($conn, 1) -> delete($conn);