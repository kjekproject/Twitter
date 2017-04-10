<?php

require_once 'config.php';
require_once './src/User.php';
require_once './src/Tweet.php';

session_start();

//sprawdzenie czy użytkownik jest zalogowany 
if (!isset($_SESSION['loggedUserId'])) {
    header("Location: login.php");
    exit();
} 

//obsługa dodawania postów wraz z generowaniem komunikatu zwrotnego
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['text']) & strlen(trim($_POST['text'])) > 0) {
        $newTweet = new Tweet();
        $newTweet->setText($_POST['text']);
        $newTweet->setCreationDate(date('Y-m-d H:i:s'));
        $newTweet->setUserId($_SESSION['loggedUserId']);
        if ($newTweet->saveToDB($conn) == true) {
            $_SESSION['addingTweetMsg'] = '<div class="alert alert-success" role="alert">Twój tweet został dodany.</div><br/>';
        } else {
            $_SESSION['addingTweetMsg'] = '<div class="alert alert-danger" role="alert">Wystąpił błąd podczas dodawania tweeta.</div><br/>';
        }
    }  
}

?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Twitter - strona główna</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    
    <body>
        
        <nav id="header" class="navbar fixed-top font-italic font-weight-bold">
            <span class="navbar-brand">KJ_TWITTER</span>
            <a class="btn navbar-togler navbar-toggler-right" href="logout.php">Wyloguj</a>
        </nav><br/>
        
        <div id="main-menu" class="container">
            <ul class="nav nav-tabs nav-justified font-weight-bold">
                <li class="nav-item menu-act rounded-top">
                  <a class="nav-link active" href="index.php">Strona główna</a>
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
                <li class="nav-item menu-unact rounded-top">
                    <a class="nav-link" href="user_edition.php">Twoje konto</a>
                </li>
            </ul>
        </div>
            
        <div id="main-content" class="container rounded">
            
            <?php
            //komunikaty dotyczące pomyślnej rejestracji oraz pomyślnego dodania tweeta
            if(isset($_SESSION['successfulRegistration'])) {
                echo $_SESSION['successfulRegistration'];
                unset($_SESSION['successfulRegistration']);
            }
            
            if(isset($_SESSION['addingTweetMsg'])) {
                echo $_SESSION['addingTweetMsg'];
                unset($_SESSION['addingTweetMsg']);
            }
            ?>
            
            <!--formularz dodawania nowego tweeta-->
            <form method="POST" action="#">
                <fieldset class="form-group">
                    <legend><small>Podziel się z swoimi opiniami</small></legend>
                    <div class="form-group">
                        <label>Treść do 140 znaków</label><br />
                        <textarea type="text" name="text" maxlength="140" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-secondary">Dodaj tweeta</button>
                </fieldset>
            </form> <br/>
            
            <div>
                <!--wyświetlanie wszystkich tweetów-->
                <?php
                $tweets = Tweet::loadAllTweets($conn);
                for($i=0; $i < count($tweets); $i++) {
                    echo '<div>'
                            . '<a class="user-link" href="user.php?userId='.$tweets[$i]->getUserId().'">'.$tweets[$i]->getUserName().' </a>'
                            . ' Post opublikowany ' . $tweets[$i]->getCreationDate().'</a><br/>' 
                            . '<a class="tweet-content" href="tweet.php?tweetId='.$tweets[$i]->getId().'">'.$tweets[$i]->getText() . '</a>'
                        . '</div><br/>';
                }
                ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </body>
</html>
    

            

 

