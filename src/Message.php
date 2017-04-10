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
        $sql = "SELECT m.id, m.authorId, u1.userName AS authorName, m.recipientId,
                u2.userName AS recipientName, m.text, m.creationDate, m.status
               FROM messages m 
               LEFT JOIN users u1 ON m.authorId=u1.id 
               LEFT JOIN users u2 ON m.recipientId=u2.id 
               WHERE m.id=$messageId";
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
    
    static public function loadMessageByAuthorId(mysqli $conn, $authorId) {
        $sql = "SELECT m.id, m.authorId, u1.userName AS authorName, m.recipientId,
                u2.userName AS recipientName, m.text, m.creationDate, m.status
               FROM messages m 
               LEFT JOIN users u1 ON m.authorId=u1.id 
               LEFT JOIN users u2 ON m.recipientId=u2.id 
               WHERE authorId=$authorId";
        $result = $conn->query($sql);
        $messages = [];
        
        if($result == TRUE && $result->num_rows>0) {            
            foreach($result as $row) {
                $loadedMessage = new Message;
                $loadedMessage->id = $row['id'];
                $loadedMessage->authorId = $row['authorId'];
                $loadedMessage->authorName = $row['authorName'];
                $loadedMessage->recipientId = $row['recipientId'];
                $loadedMessage->recipientName = $row['recipientName'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creationDate = $row['creationDate'];
                $loadedMessage->status = $row['status'];

                $messages[] = $loadedMessage;
            }
        }
        return $messages;
    }
    
    static public function loadMessageByRecipientId(mysqli $conn, $recipientId) {
        $sql = "SELECT m.id, m.authorId, u1.userName AS authorName, m.recipientId,
                u2.userName AS recipientName, m.text, m.creationDate, m.status
               FROM messages m 
               LEFT JOIN users u1 ON m.authorId=u1.id 
               LEFT JOIN users u2 ON m.recipientId=u2.id 
               WHERE recipientId=$recipientId";
        $result = $conn->query($sql);
        $messages = [];
        
        if($result == TRUE && $result->num_rows>0) {        
            foreach($result as $row) {
                $loadedMessage = new Message;
                $loadedMessage->id = $row['id'];
                $loadedMessage->authorId = $row['authorId'];
                $loadedMessage->authorName = $row['authorName'];
                $loadedMessage->recipientId = $row['recipientId'];
                $loadedMessage->recipientName = $row['recipientName'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creationDate = $row['creationDate'];
                $loadedMessage->status = $row['status'];

                $messages[] = $loadedMessage;
            }
        }
        return $messages;
    }
    
    static public function changeStatusOfaMessage(mysqli $conn, $messageId) {
        $message = self::loadMessageById($conn, $messageId);
        $status = $message->getStatus();
        if($status == 0) {
            $message->setStatus(1);
            $message->saveToDb($conn);
            return true;
        }
        return false;
    }
}