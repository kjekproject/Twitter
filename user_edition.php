<?php

require_once 'config.php';
require_once './src/User.php';

session_start();

if (!isset($_SESSION['loggedUserId'])) {
    header("Location: login.php");
    exit();
}
echo '<br/><br/><br/><br/>';
//obsługa formularza edcji danych użytkownika
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['newEmail']) &&
    isset($_POST['newUserName']) &&
    isset($_POST['newPassword'])) 
    {
        //zmienna pomocnicza 
        $flag_ok = true;
        
        //sprawdzenie poprawności adresu email
        $email1 = $_POST['newEmail'];
        $email2 = filter_var($_POST['newEmail'], FILTER_VALIDATE_EMAIL);
        if($email1 != $email2) {
            $flag_ok = FALSE;
            $_SESSION['errorEmail'] = '<div class="form-control-feedback">Nieprawidłowy adres email.</div>';
        }
        
        //sprawdzenie poprawności nazwy użytkownika
        $newUserName = $_POST['newUserName'];
        if(strlen(trim($newUserName)) < 3 || strlen(trim($newUserName)) > 30) {
        $flag_ok = false;
        $_SESSION['errorUserName'] = '<div class="form-control-feedback">Niepoprawna nazwa użytkownika. Nazwa powinna zawierać od 3 do 30 znaków.</div>';
        }
        
        
        //sprawdzenie poprawności hasła
        $newPassword = $_POST['newPassword'];
        if(strlen(trim($newPassword)) < 5 || strlen(trim($newPassword)) > 20) {
            $flag_ok = false;
            $_SESSION['errorPassword'] = '<div class="form-control-feedback">Niepoprawne hasło. Hasło powinno zawierać od 5 do 20 znaków.</div>';
        }
        
        //podsumowanie poprawności danych z formularza
        //jeśli wszystkie spełniają "wymogi formalne" to następuje dalszy etap zmiany danych
        if($flag_ok === TRUE) {
            
            //sprawdzenie newEmail nie jest przypisany do innego konta
            $user = User::loadUserById($conn, $_SESSION['loggedUserId']);
            $email = $user->getEmail();
            if($email1 != $email && User::loadUserByEmail($conn, $email1) !== NULL) {
                $_SESSION['errorEmail'] = '<div class="form-control-feedback">Istnieje już konto przypisane do podanego adresu email.</div>';
            } else {
                //po spełnieniu wszystkich warunków dane użytkownika są zmieniane
                $user->setEmail($email1);
                $user->setUserName($newUserName);
                $user->setPassword($newPassword);

                if($user->saveToDB($conn) == TRUE) {
                    $_SESSION['successfulChange'] = '<div class="alert alert-success" role="alert">Dane użytkownika zostały zmienione.</div><br/>';     
                }
            }
        }
    }
}

?>

<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Twitter - edycja konta</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    
    <body>
        
        <nav id="header" class="navbar fixed-top font-italic font-weight-bold">
            <span class="navbar-brand">KJ_TWITTER</span>
            <a class="btn navbar-togler navbar-toggler-right" href="logout.php">Wyloguj</a>
        </nav>
        
        <div id="main-menu" class="container">
            <ul class="nav nav-tabs nav-justified font-weight-bold">
                <li class="nav-item menu-unact rounded-top">
                  <a class="nav-link" href="index.php">Strona główna</a>
                </li>
                <li class="nav-item menu-unact rounded-top">
                    <?php
                    echo '<a class="nav-link" href="user.php?userId='.
                            $_SESSION['loggedUserId'].'">Twoje tweety</a>';
                    ?>
                </li>
                <li class="nav-item menu-unact rounded-top">
                    <a class="nav-link" href="messages.php">Wiadomości</a>
                </li>
                <li class="nav-item menu-act rounded-top">
                    <a class="nav-link active" href="user_edition.php">Twoje konto</a>
                </li>
            </ul>
        </div>
            
        <div id="main-content" class="container rounded">
            <?php
            if(isset($_SESSION['successfulChange'])) {
                echo $_SESSION['successfulChange'];
                unset($_SESSION['successfulChange']);
            }
            ?>
            <!--formularz edycji danych użytkownika-->
            <form method="POST" action="#">
                <fieldset class="form-group">
                    <legend><small>Edycja danych</small></legend>
                    <div class="form-group">
                        <label>Email
                            <?php
                            $userData = User::loadUserById($conn, $_SESSION['loggedUserId']);
                            echo '<input name="newEmail" type="text" class="form-control" value="'.$userData->getEmail().'" required>';
                            $userName = $userData->getUserName();
                            if(isset($_SESSION['errorEmail'])) {
                                echo $_SESSION['errorEmail'];
                                unset ($_SESSION['errorEmail']);
                            }
                            ?>                           
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Nazwa użytkownika
                            <?php
                            echo '<input name="newUserName" type="text" class="form-control" value="'.$userName.'" required>';
                            if(isset($_SESSION['errorUserName'])) {
                                echo $_SESSION['errorUserName'];
                                unset ($_SESSION['errorUserName']);
                            }
                            ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Wpisz nowe hasło (lub stare)
                            <input name="newPassword" type="password" class="form-control" required>
                            <?php
                            if(isset($_SESSION['errorPassword'])) {
                                echo $_SESSION['errorPassword'];
                                unset ($_SESSION['errorPassword']);
                            }
                            ?>
                        </label>
                    </div>
                    <button type="submit" class="btn">Zmień</button>
                </fieldset>
            </form> 
        </div>

        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </body>
</html>