<?php

require_once(__DIR__ . '/../Controller/config.php');

class Tweet {
    private $id, $userId, $text, $creationDate; 
    
    public function __construct() {
        $this -> id = -1;
        $this -> userId = '';
        $this -> text = '';
        $this -> creationDate = '';
    }
    
    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }

    function getText() {
        return $this->text;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

 //chyba nie muszę tak sprawdzać tutaj setUserId
    function setUserId($userId) { 
        if (is_numeric($userId) && (strlen($userId) <= 11)) {
            $this -> userId = $userId;
        } else {
            $this -> userId = false;
        }
        
    }

    function setText($text) {
        if(strlen($text) <=140){ 
             $this -> text = $text;
        } else {
            $this -> text = substr($text, 0, 139);
        }
       
    }

    function setCreationDate() {
        $date = new DateTime();
        $this->creationDate = ($date-> format('Y-m-d H:i:s'));
    }

    static function loadTweetById(PDO $conn, $id) {
        $stmt = $conn -> prepare('SELECT * FROM `Tweet` WHERE `id` = :id');
        $result = $stmt -> execute(['id' => $id]);
        
        if ($result === true && $stmt -> rowCount() > 0) {
            $row = $stmt -> fetch(); //było PDO::FETCH_ASSOC

            $loadedTweet = new Tweet();
            $loadedTweet -> id = $row['id'];
            $loadedTweet -> userId = $row['userId'];
            $loadedTweet -> text = $row['text'];
            $loadedTweet -> creationDate = $row['creationDate'];
        
            return $loadedTweet;
        }     
        return null;
    }
    
    static function loadAllTweetsByUserId(PDO $conn, $userId) {
        $stmt = $conn -> prepare('SELECT * FROM `Tweet` WHERE `userId` = :userId') ;
        $results = $stmt -> execute(['userId' => $userId]);
                                
            if ($results === true && $stmt -> rowCount() > 0) {
                $tweetArrObj = [];
                $rows = $stmt -> fetchAll();
                foreach ($rows as $row) {
                    $loadedUserTweet = new Tweet();
                    $loadedUserTweet -> id = $row['id'];
                    $loadedUserTweet -> userId = $row['userId'];
                    $loadedUserTweet -> text = $row['text'];       
                    $loadedUserTweet -> creationDate = $row['creationDate'];
                
                    $tweetArrObj[] = $loadedUserTweet;
                }
                return $tweetArrObj ;
            } else {
                return null;
            }
        
    }
    
    static function loadAllTweets(PDO $conn) {
        $sql = 'SELECT * FROM `Tweet`';
        $ret = [];
        
        $result = $conn -> query($sql); 
        if ($result !== false && $result -> rowCount() != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet -> id = $row['id'];
                $loadedTweet -> userId = $row['userId'];
                $loadedTweet -> text = $row['text'];
                $loadedTweet -> creationDate = $row['creationDate'];
            
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }
    
    function saveToDB(PDO $conn) {
        
        if ($this -> id = -1) {
           $stmt = $conn -> prepare( 
                    'INSERT INTO `Tweet`(`userId`, `text`, `creationDate`) 
                        VALUES (:userId, :text, :creationDate); '
                            );
            $result = $stmt -> execute(
                        ['userId' => $this -> userId, 
                        'text' => $this -> text, 
                        'creationDate' => $this -> creationDate]);
            if ($result !== false) {
                $this -> id = $conn ->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn -> prepare(
                    'UPDATE `Tweet` SET '
                    . ' `userId` = :userId,'
                    . ' `text` = :text,'
                    . ' `creationDate` = :creationDate'
                    . ' WHERE `id` = :id ; ');
            $result = $stmt -> execute(
                    ['userId' => $this -> userId, 
                    'text' => $this -> text, 
                    'creationDate' => $this -> creationDate,
                    'id' => $this -> id] );
            if ($result === true) {
                return true;
            }
        }
        return false;
    }
  
    
    
}
