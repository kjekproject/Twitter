<?php

class Comment {
    private $id;
    private $userId;
    private $userName;
    private $tweetId;
    private $text;
    private $creationDate;

    function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->userName = "";
        $this->tweetId = "";
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

    function getTweetId() {
        return $this->tweetId;
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

    function setTweetId($tweetId) {
        $this->tweetId = $tweetId;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function saveToDB(mysqli $conn) {
        if ($this->id == -1) {
            $sql = "INSERT INTO comments (userId, tweetId, text, creationDate)
                    VALUES ($this->userId, '$this->tweetId', '$this->text', '$this->creationDate')";
            $result = $conn->query($sql);
            
            if ($result == TRUE) {
                $this->id = $conn->insert_id;
                return TRUE;
            }
        } else {
            $sql = "UPDATE tweets SET text='$this->text', creationDate='$this->creationDate'
                   WHERE id=$this->id";
            $result = $conn->query($sql);
            
            if($result == TRUE) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    static public function loadCommentsByTweetId(mysqli $conn, $tweetId) {
        $sql = "SELECT * FROM comments c JOIN users u ON c.userId=u.id
                WHERE tweetId=$tweetId ORDER BY creationDate DESC";
        $result = $conn->query($sql);
        $comments = [];
        
        if($result == TRUE && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedComment = new Comment;
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->tweetId = $row['tweetId'];
                $loadedComment->text = $row['text'];
                $loadedComment->creationDate = $row['creationDate'];
                
                $comments[] = $loadedComment;
            }
        }
        return $comments;
    }
    
    static public function getCommentsNumberByTweetId(mysqli $conn, $tweetId) {
        $sql = "SELECT * FROM comments WHERE tweetId=$tweetId";
        $result = $conn->query($sql);
        $commentsNumber = $result->num_rows;
        return $commentsNumber;
    }
}