<?php

require_once '../Model/Tweet.php';

require_once 'config.php';

$tweetPierwszyDB = new Tweet; 
$tweetPierwszyDB ->setUserId('2'); // to musi się robić samo
$tweetPierwszyDB ->setText('Artificial tweet from TweetController');
//tworzy obiekt do zmiennej, na której robi metodę format podającą datę w formacie pasującym do mojej DB
$tweetPierwszyDB ->setCreationDate(($date = new DateTime()) -> format('Y-m-d H:i:s')); 
$tweetPierwszyDB ->saveToDB($conn);