<?php

class Comment { 
    
    private $id, $userId, $postId, $creationDate, $text; 
    
    public function __construct() {
        $this -> id = -1;
        $this -> userId = '';
        $this -> postId = '';
        $this -> creationDate ='' ;
        $this -> text = '';
    }
    
    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }

    function getPostId() {
        return $this->postId;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function getText() {
        return $this->text;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setPostId($postId) {
        $this->postId = $postId;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    function setText($text) {
        if(strlen($text) <=140) {
            $this -> text = $text;
        } else {
            $this->text = substr($text, 0, 139);
        }
    }

    static function loadCommentById(PDO $conn, $id) {
        $stmt = $conn -> prepare('SELECT * FROM `Comment` WHERE $id = :id');
        try {
            $result = $stmt -> execute(['id => $id']);

            if ($result === true && $stmt -> rowCount() > 0) {
                $row = $stmt -> fetch();

                $loadedCommnet = new Comment();
                $loadedComment -> id = $row['id'];
                $loadedComment -> userId = $row['userId'];
                $loadedComment -> postId = $row['postId'];
                $loadedComment -> creationDate = $row['creationDate'];
                $loadedComment -> text = $row['text'];

                return $loadedTweet;
            }
        } catch (Exception $ex) {
            echo 'Błąd ' . $ex -> getMessage();
        }
    return null;       
        
    }
    
    static function loadAllCommentsByPostId(PDO $conn, $postId) {
        $stmt = $conn -> prepare('SELECT * FROM `Tweet` WHERE `postId` = :postID');
        
        try {
            $results = $stmt -> execute(['postId' => $postId]);
            if ($results === true && $stmt -> rowCount() > 0) {
            $commentArrObj = [];
            $rows = $stmt -> fetchAll();
            foreach ($rows as $row) {
                $loadedPostComment = new Comment();
                $loadedPostComment -> id = $row['id'];
                $loadedPostComment -> userId = $row['userId'];
                $loadedPostComment -> postId = $row['postId'];
                $loadedPostComment -> creationDate = $row['creationDate'];
                $loadedPostComment -> text = $row['text'];

                $commentArrObj [] = $loadedPostComment;

            }
            return $commentArrObj;
            }
        } catch (Exception $ex) {
            echo "Błąd" .$ex -> getMessage();
        }
        
        return null;
    }
    
    
    function saveToDB(PDO $conn) {
        
        if ($this -> id =-1) {
            $stmt = $conn -> prepare(
                    'INSERT INTO `Comment` (`id`, `userId`, `postId`, `creationDate`, `text` 
                    VALUES (:id, :userId, :postId, :creationDate, :text)'
                    );
            
            $result = $stmt -> execute(
                        ['userId' => $this -> userId,
                        'postId' => $this -> postId,
                        'text' => $this -> text, 
                        'creationDate' => $this -> creationDate]
                        );
            if ($result !== false) {
                $this -> id = $conn ->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn -> prepare(
                    'UPDATE `Tweet` SET '
                    . ' `userId` = :userId,'
                    . ' `postId` = :postId,'
                    . ' `text` = :text,'
                    . ' `creationDate` = :creationDate'
                    . ' WHERE `id` = :id ; '
                    );
            $result = $stmt -> execute(
                    ['userId' => $this -> userId, 
                    'postId' => $this -> postId, 
                    'text' => $this -> text, 
                    'creationDate' => $this -> creationDate,
                    'id' => $this -> id] 
                    );
            if ($result === true) {
                return true;
            }
        }
        return false;
    }
        
        
    
    
    
}
