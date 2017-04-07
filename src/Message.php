<?php

class Message {
    private $id;
    private $authorId;
    private $authorName;
    private $recipientId;
    private $recipientName;
    private $text;
    private $creationDate;
    private $status;
    
    function __construct() {
        $this->id = -1;
        $this->authorId = "";
        $this->authorName = "";
        $this->recipientId = "";
        $this->recipientName = "";
        $this->text = "";
        $this->creationDate = "";
        $this->status = 0;
    }
    
    function getId() {
        return $this->id;
    }

    function getAuthorId() {
        return $this->authorId;
    }

    function getAuthorName() {
        return $this->authorName;
    }

    function getRecipientId() {
        return $this->recipientId;
    }
    
    function getRecipientName() {
        return $this->recipientName;
    }

        function getText() {
        return $this->text;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function getStatus() {
        return $this->status;
    }
    
    function setAuthorId($authorId) {
        $this->authorId = $authorId;
    }

    function setRecipientId($recipientId) {
        $this->recipientId = $recipientId;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    
    public function saveToDb(mysqli $conn) {
        if ($this->id == -1) {
            $sql = "INSERT INTO messages (authorId, recipientId, text, creationDate, status)
                   VALUES ($this->authorId, $this->recipientId, '$this->text', '$this->creationDate', $this->status)";
            $result = $conn->query($sql);
            
            if ($result == TRUE) {
                $this->id = $conn->insert_id;
                return TRUE;
            }
        }
        return FALSE;
    }
    
    static public function loadMessageById(mysqli $conn, $messageId) {
        $sql = "SELECT m.id, m.authorId, u.
               FROM messages m 
               LEFT JOIN user u ON m.authorId=u.id 
               LEFT JOIN user u ON m.recipientId=u.id 
               WHERE id=$messageId";
        $result = $conn->query($sql);
        
        if($result == TRUE && $result->num_rows==1) {
            $row = $result->fetch_assoc();
            
            $loadedMessage = new Message;
            $loadedMessage->id = $row['id'];
            $loadedMessage->authorId = $row['authorId'];
            $loadedMessage->authorName = $row['authorName'];
            $loadedMessage->recipientId = $row['recipientId'];
            $loadedMessage->recipientName = $row['recipientName'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->creationDate = $row['creationDate'];
            $loadedMessage->status = $row['status'];
            
            return $loadedMessage;
        }
        return NULL;
    }
}