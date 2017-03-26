<?php

require_once(__DIR__ . '/../Controller/config.php'); 

class User {
    
    private $id, $username, $password, $email; 
    
    public function __construct() {
        $this -> id = -1; 
        $this -> username = ''; 
        $this -> hashPass = ''; 
        $this -> email = '';
    }
    
    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }
    
    function getHashPass() {
        return $this -> hashPass;
    }

    function getEmail() {
        return $this->email;
    }

   
    function setUsername($username) {
        if (4 <= strlen($username) && strlen($username) <=20) {
            $this -> username = $username;
        }
        else {
            $this -> username = false;
        } 
        return $this;
    }

    function setPassword($newPass) {
        $options = ['cost' => 11];
        $newHashedPassword = password_hash($newPass, PASSWORD_BCRYPT, $options);
    
        $this -> hashPass = $newHashedPassword;
        return $this;
    }

    function setEmail($email) {
        if(is_string($email) && strstr($email, '@')) {
            $this -> email = $email;
        } else {
            $this -> email = false;
        }
        return $this;
    }
    
    function saveToDB(PDO $conn) {
        
        if ($this -> id == -1) {
            $stmt = $conn -> prepare( 
                    'INSERT INTO `User`(`username`, `email`, `hash_pass`) 
                        VALUES (:username, :email, :pass); '
                            );
            $result = $stmt -> execute(
                        ['username' => $this -> username, 
                        'email' => $this -> email, 
                        'pass' => $this -> hashPass]);
            if ($result !== false) {
                $this -> id = $conn ->lastInsertId();
                return true;
            }
        } else {
            $stmt = $conn -> prepare(
                    'UPDATE `User` SET '
                    . ' `username` = :username,'
                    . ' `email` = :email,'
                    . ' `hash_pass` = :hash_pass'
                    . ' WHERE `id` = :id ; ');
            $result = $stmt -> execute(
                    ['username' => $this -> username, 
                     'email' => $this -> email,
                     'hash_pass' => $this -> hashPass,
                     'id' => $this -> id] );
            if ($result === true) {
                return true;
            }
        }
        return false;
    }
    static function loadUserById(PDO $conn, $id) {
        $stmt = $conn -> prepare('SELECT * FROM `User` WHERE `id`=:id');
        $result = $stmt -> execute(['id' => $id]);
        
        if ($result === true && $stmt ->rowCount() > 0) {
            $row = $stmt -> fetch(); //było PDO::FETCH_ASSOC
            
            $loadedUser = new User();
            $loadedUser -> id = $row['id'];
            $loadedUser -> username = $row['username'];
            $loadedUser -> hashPass = $row['hash_pass'];
            $loadedUser -> email = $row['email'];
            
            return $loadedUser;
        }
        return null;
    }
    
    static function loadUserByEmail(PDO $conn, $email) {
        $stmt = $conn -> prepare('SELECT * FROM `User` WHERE `email`=:email');
        $result = $stmt -> execute(['email' => $email]);
        
        if ($result === true && $stmt ->rowCount() > 0) {
            $row = $stmt -> fetch(); // było PDO::FETCH_ASSOC
            
            $loadedUser = new User();
            $loadedUser -> id = $row['id'];
            $loadedUser -> username = $row['username'];
            $loadedUser -> hashPass = $row['hash_pass'];
            $loadedUser -> email = $row['email'];
            
            return $loadedUser;
        }
        return null;
    }
    
    static function loadAllUsers(PDO $conn) {
        $sql = 'SELECT * FROM  `User`';
        $ret = []; 
        
        $result = $conn -> query($sql);
        if ($result !== false && $result ->rowCount() != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser -> id = $row['id'];
                $loadedUser -> username = $row['username'];
                $loadedUser -> hashPass = $row['hash_pass'];
                $loadedUser -> email = $row['email'];
                
                $ret[] = $loadedUser;
                }
        } 
        return $ret;
        
       
        }
         function delete(PDO $conn) {
            if ($this -> id !=-1) {
                $stmt = $conn -> prepare('DELETE FROM `User` WHERE `id` = :id');
                $result = $stmt -> execute(['id' => $this -> id]);
                
                if ($result === true) {
                    $this -> id = -1; 
                    
                    return true;
                }
                return false;
            }
            return true; 
    }
}


