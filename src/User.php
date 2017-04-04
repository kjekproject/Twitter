<?php

class User 
{  
    private $id;
    private $user_name;
    private $hashed_password;
    private $email;
    
    function __construct() {
        $this->id = -1;
        $this->user_name = "";
        $this->hashed_password = "";
        $this->email = "";
    }

    function getId() {
        return $this->id;
    }

    function getUser_name() {
        return $this->user_name;
    }

    function getHashed_password() {
        return $this->hashed_password;
    }

    function getEmail() {
        return $this->email;
    }

    function setUser_name($user_name) {
        $this->user_name = $user_name;
    }

    function setHashed_password($new_password) {
        $this->hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    }

    function setEmail($email) {
        $this->email = $email;
    }

    public function saveToDB(mysqli $conn) {
        if ($this->id == -1) {
            $sql = "INSERT INTO Users VALUES (null, '$this->user_name',
                    '$this->email', '$this->hashed_password')";
            $result = $conn->query($sql);
            
            if ($result == true) {
                $this->id = $conn->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE Users SET user_name='$this->user_name',
                  email='$this->email', hashed_password='$this->hashed_password'
                  WHERE id=$this->id";
            $result = $conn->query($sql);
            
            if ($result == TRUE) {
                return true;
            }
        }
        return FALSE;
    }
    
    static public function loadUserById(mysqli $conn, $id) {
        $sql = "SELECT * FROM Users WHERE id=$id";
        $result = $conn->query($sql);
        
        if ($result == TRUE && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->user_name = $row['user_name'];
            $loadedUser->hashed_password = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            
            return $loadedUser;
        }
        return NULL;
    }
    
    static public function loadAllUsers(mysqli $conn) {
        $sql = "SELECT * FROM Users";
        $result = $conn->query($sql);
        $ret = [];        
        
        if ($result == TRUE && $result->num_rows != 0) {
            foreach($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->user_name = $row['user_name'];
                $loadedUser->hashed_password = $row['hashed_password'];
                $loadedUser->email = $row['email'];
                
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }
    
    public function delete(mysqli $conn) {
        if($this->id != -1) {
            $sql = "DELETE FROM Users WHERE id=$this->id";
            $result = $conn->query($sql);
            
            if($result == TRUE) {
                $this->id = -1;
                return true;
            }
            return FALSE;
        }
        return TRUE;
    }
    
    static public function loadUserByEmail(mysqli $conn, $email) {
        $sql = 'SELECT * FROM Users '
                . 'WHERE email="'.$conn->real_escape_string($email).'";';
        
        $result = $conn->query($sql);
        
        if($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->user_name = $row['user_name'];
            $loadedUser->hashed_password = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return NULL;
    }
    
    static public function login(mysqli $conn, $email, $password) {
        $user = self::loadUserByEmail($conn, $email);
        if($user!=null && password_verify($password, $user->getHashed_password())) {
            return $user;
        } else {
            return FALSE;
        }
    }
    
    static public function getUserNameById(mysqli $conn, $user_id) {
        $user = User::loadUserById($conn, $user_id);
        $name = $user->getUser_name();
        return $name;
    }
    
    static public function getUserIdByName(mysqli $conn, $user_name) {
        $sql = 'SELECT id FROM Users WHERE user_name="'.$user_name.'";';
        $result = $conn->query($sql);

        if($result == TRUE && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $user_id = $row['id'];
            return $user_id;
        } else {
            echo $conn->error;
            return false;
        }
    }
}