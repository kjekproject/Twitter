<?php

class User 
{  
    private $id;
    private $email;
    private $userName;
    private $password;
    
    function __construct() {
        $this->id = -1;
        $this->email = "";
        $this->userName = "";
        $this->password = "";
    }

    function getId() {
        return $this->id;
    }
    
    function getEmail() {
        return $this->email;
    }

    function getUserName() {
        return $this->userName;
    }

    function getPassword() {
        return $this->password;
    }        

    function setEmail($email) {
        $this->email = $email;
    }
    
    function setUserName($userName) {
        $this->userName = $userName;
    }

    function setPassword($password) {
        $options = [
        'cost' => 11,
        ];
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);
    }

    public function saveToDB(mysqli $conn) {
        if ($this->id == -1) {
            $sql = "INSERT INTO users VALUES (null, '$this->email',
                    '$this->userName', '$this->password')";
            $result = $conn->query($sql);
            
            if ($result == true) {
                $this->id = $conn->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE users SET email='$this->email', userName='$this->userName',
                    password='$this->password' WHERE id=$this->id";
            $result = $conn->query($sql);
            
            if ($result == TRUE) {
                return true;
            }
        }
        return FALSE;
    }
    
    static public function loadUserById(mysqli $conn, $id) {
        $sql = "SELECT * FROM users WHERE id=$id";
        $result = $conn->query($sql);
        
        if ($result == TRUE && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->email = $row['email'];
            $loadedUser->userName = $row['userName'];
            $loadedUser->password = $row['password'];
            
            return $loadedUser;
        }
        return NULL;
    }
    
    static public function loadAllUsers(mysqli $conn) {
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        $returned = [];        
        
        if ($result == TRUE && $result->num_rows != 0) {
            foreach($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->email = $row['email'];
                $loadedUser->userName = $row['userName'];
                $loadedUser->password = $row['password'];
                
                $returned[] = $loadedUser;
            }
        }
        return $returned;
    }
    
    public function delete(mysqli $conn) {
        if($this->id != -1) {
            $sql = "DELETE FROM users WHERE id=$this->id";
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
        $sql = 'SELECT * FROM users '
                .'WHERE email="'.$conn->real_escape_string($email).'";';  
        $result = $conn->query($sql);
        
        if($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->email = $row['email'];
            $loadedUser->userName = $row['userName'];
            $loadedUser->password = $row['password'];

            return $loadedUser;
        }
        return NULL;
    }
    
    static public function login(mysqli $conn, $email, $password) {
        $user = self::loadUserByEmail($conn, $email);
        if($user!=null && password_verify($password, $user->getPassword())) {
            return $user;
        } else {
            return null;
        }
    }

}