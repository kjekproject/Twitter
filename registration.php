<?php

session_start();

//weryfikacja danych rejestracyjnych użytkownika
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    //zmienna pomocnicza do monitorowania poprawności danych z formularza
    $flag_ok = TRUE;
    
    //sprawdzenie poprawności maila
    $email1 = $_POST['email'];
    $email2 = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if($email1 != $email2) {
        $flag_ok = false;
        //zmienna do przesyłania komunikatu o błędzie
        $_SESSION['errorEmail'] = '<div class="form-control-feedback">Nieprawidłowy adres email.</div>';
    }
    
    //sprawdzenie poprawności nazwy użytkownika
    $name = $_POST['userName'];
    if(strlen(trim($name)) < 3 || strlen(trim($name)) > 30) {
        $flag_ok = false;
        $_SESSION['errorUserName'] = '<div class="form-control-feedback">Nazwa użytkownika powinna zawierać od 3 do 30 znaków.</div>';
    }
    
    //sprawdzenie poprawności hasła
    $password = $_POST['password'];
    if(strlen(trim($password)) < 5 || strlen(trim($password)) > 20) {
        $flag_ok = false;
        $_SESSION['errorPassword'] = '<div class="form-control-feedback">Hasło powinno zawierać od 5 do 20 znaków.</div>';
    }
    
    //sprawdzenie przy użyciu zmiennej pomocniczej czy dane z formularza były poprawne
    if($flag_ok === TRUE) {
        
        //sprawdzenie czy nie ma w bazie danych użytkownika o danym adresie email
        require_once 'config.php';
        require_once './src/User.php';
        
        $user = User::loadUserByEmail($conn, $email1);
        if($user !== NULL) {
            $_SESSION['errorEmail'] = '<div class="form-control-feedback">Istnieje już konto przypisane do tego adresu email.</div>';
        } else {
            
            //tworzymy nowego użytkownika i dodajemy do bazy danych
            $newUser = new User;
            $newUser->setEmail($email1);
            $newUser->setUserName($name);
            $newUser->setPassword($password);
            
            if($newUser->saveToDB($conn) == TRUE) {
                $_SESSION['loggedUserId'] = $newUser->getId();
                $_SESSION['successfulRegistration'] = '<div>Rejestracja udana. Witamy!</div><br/>';
                header('Location: index.php');
            }
        }
        $conn->close();
        $conn = NULL;
    }
}

?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Twitter - rejestracja</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    
    <body>
        
        <nav id="header" class="navbar fixed-top font-italic font-weight-bold">
            <span class="navbar-brand">KJ_TWITTER</span>
        </nav>
        
        <div id="main-menu" class="container">
            <ul class="nav nav-tabs nav-justified font-weight-bold ">
                <li class="nav-item menu-unact rounded-top">
                  <a class="nav-link" href="login.php">Logowanie</a>
                </li>
                <li class="nav-item menu-act rounded-top">
                    <a class="nav-link active" href="registration.php">Rejestracja</a>
                </li>
            </ul>
        </div>
            
        <div id="main-content" class="container rounded">
            <!--formularz rejestracji nowego użytkownika-->
            <form method="POST" action="#">
                <fieldset class="form-group">
                    <legend><small>Załóż konto</small></legend>
                    <div class="form-group">
                        <label>Email
                            <input name="email" type="text" class="form-control" required>
                            <?php
                            if(isset($_SESSION['errorEmail'])) {
                                echo $_SESSION['errorEmail'];
                                unset ($_SESSION['errorEmail']);
                            }
                            ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Nazwa użytkownika
                            <input name="userName" type="text" class="form-control" required>
                            <?php
                            if(isset($_SESSION['errorUserName'])) {
                                echo $_SESSION['errorUserName'];
                                unset ($_SESSION['errorUserName']);
                            }
                            ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Hasło
                            <input name="password" type="password" class="form-control" required>
                            <?php
                            if(isset($_SESSION['errorPassword'])) {
                                echo $_SESSION['errorPassword'];
                                unset ($_SESSION['errorPassword']);
                            }
                            ?>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-secondary">Zarejestruj się</button>
                </fieldset>
            </form> 
        </div>

        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </body>
</html>
