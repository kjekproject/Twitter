<?php

session_start();

//sprawdzamy, czy użytkownik nie jest przypadkiem już zalogowany, 
//jak jest to przekierowujemy go na stronę główną
if (isset($_SESSION['loggedUserId'])) {
    header('Location: index.php');
    exit();
}

require_once 'config.php';
require_once './src/User.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && strlen(trim($_POST['email'])) >= 5
        && isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6) {
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = User::login($conn, $email, $password);
        if($user) {
            $_SESSION['loggedUserId'] = $user->getId();
            header('Location: index.php');
        } else {
            $_SESSION['error'] = '<div class="form-control-feedback>Nieprawidłowy login '
                    . 'lub hasło</div>';
        }
    } else {
        $_SESSION['error'] = '<div class="form-control-feedback">Nieprawidłowy login '
                    . 'lub hasło</div>';  
    }
}

?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Twitter - logowanie</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    
    <body>
        
        <nav id="header" class="navbar fixed-top font-italic font-weight-bold">
            <span class="navbar-brand">KJ_TWITTER</span>
        </nav><br/>
        
        <div id="main-menu" class="container">
            <ul class="nav nav-tabs nav-justified font-weight-bold ">
                <li class="nav-item menu-act">
                  <a class="nav-link active rounded-top" href="login.php">Logowanie</a>
                </li>
                <li class="nav-item menu-unact rounded-top">
                    <a class="nav-link" href="registration.php">Rejestracja</a>
                </li>
            </ul>
        </div>
            
        <div id="main-content" class="container rounded"> 
            <!--formularz logowania-->
            <form method="POST" action="#">
                <fieldset class="form-group">
                    <legend><small>Zaloguj się lub przejdź do rejestracji</small></legend>
                    <div class="form-group">
                        <label>Email
                            <input name="email" type="text" class="form-control">
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Hasło
                            <input name="password" type="password" class="form-control">
                            <?php
                            if(isset($_SESSION['error'])) {
                                echo $_SESSION['error'];
                                unset ($_SESSION['error']);
                            }
                            ?>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-secondary">Zaloguj się</button>
                </fieldset>
            </form>            
        </div>
        
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </body>
</html>