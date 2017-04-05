<?php

class Tweet {   
    private $id;
    private $userId;
    private $userName;
    private $text;
    private $creationDate;
    
    function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->userName = "";
        $this->text = "";
        $this->creationDate = "";
    }
    
    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }
    
    function getUserName() {
        return $this->userName;
    }

    function getText() {
        return $this->text;
    }

    function getCreationDate() {
        return $this->creationDate;
    }
    
    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function saveToDB (mysqli $conn) {
        if($this->id == -1) {
            $sql = "INSERT INTO tweets (userId, text, creationDate)
                    VALUES ($this->userId, '$this->text', '$this->creationDate')";
            $result = $conn->query($sql);
            
            if($result == TRUE) {
                $this->id = $conn->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE tweets SET text='$this->text', creationDate='
                    $this->creationDate' WHERE id=$this->id";
            $result = $conn->query($sql);
            
            if($result == TRUE) {
                return TRUE;
            }
        }
        return FALSE;
    }

    static public function loadAllTweets(mysqli $conn) {
        $sql = "SELECT * FROM tweets t JOIN users u ON t.userId = u.id ORDER BY creationDate DESC";
        $result = $conn->query($sql);
        $tweets = [];
        
        if($result == TRUE && $result->num_rows != 0) {
            foreach($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->userName = $row['userName'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                
                $tweets[] = $loadedTweet;
            }
        }
        return $tweets;        
    }
    
    static public function loadTweetById(mysqli $conn, $id) {
        $sql = "SELECT * FROM tweets WHERE id=$id";
        $result = $conn->query($sql);
        
        if($result == TRUE && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];
            
            return $loadedTweet;
        }
        return NULL;
    }
    
    static public function loadTweetsByUserId(mysqli $conn, $userId) {
        $sql = "SELECT * FROM tweets WHERE userId=$userId ORDER BY creationDate DESC";
        $result = $conn->query($sql);
        $tweets = [];
        
        if($result == TRUE && $result->num_rows != 0) {
            foreach($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                
                $tweets[] = $loadedTweet;
            }
        }
        return $tweets;
    }
}