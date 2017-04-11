<?php

session_start();

//sprawdzenie czy użytkownik jest zalogowany 
if (!isset($_SESSION['loggedUserId'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
require_once './src/User.php';
require_once './src/Tweet.php';
require_once './src/Comment.php';

//sprawdzenie czy przesłane zostało zapytanie z id użytkownika
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['userId'])) {
        $userId = $_GET['userId'];
    } else {
        header('Location: index.php');
        exit();
    }
}

?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Twitter - strona użytkownika</title>
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
                <li 
                    <?php
                    if($userId != $_SESSION['loggedUserId']) {
                        echo 'class="nav-item menu-unact rounded-top">'
                            . '<a class="nav-link" href="user.php?userId='.
                            $_SESSION['loggedUserId'].'">Twoje tweety</a>';                     
                    } else {
                        echo 'class="nav-item menu-act rounded-top">'
                            . '<a class="nav-link active" href="user.php?userId='.
                            $_SESSION['loggedUserId'].'">Twoje tweety</a>';
                    }                 
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
            <div class="row">
                <div class="col-md-8">
                    <?php
                    if($userId != $_SESSION['loggedUserId']) {
                        $userName = User::getUserNameById($conn, $userId);
                        echo '<h5>Posty użytkownika '.$userName.'</h5>';
                    } else {
                        echo '<h5>Twoje posty</h5>';
                    }
                    ?>
                </div>
                <div class="col-md-4">
                    <?php
                    if($userId != $_SESSION['loggedUserId']) {
                    echo '<a class="btn float-md-right" href="send_message.php?recipientId='.$userId.'">'
                    . 'Wyślij wiadomość do '.$userName.'</a>';
                    }
                    ?>
                </div>
            </div><br/>
            <?php
            //wyświetlanie wszystkich tweetów użytkownika wraz z liczbą komentarzy
            $userTweets = Tweet::loadAllTweetsByUserId($conn, $userId);
            for($i=0; $i <count($userTweets); $i++) {
                $commentsNumber = Comment::getCommentsNumberByTweetId($conn, $userTweets[$i]->getId());
                echo '<div>'
                        . ' Tweet opublikowany ' . $userTweets[$i]->getCreationDate().'<br/>'
                        . '<blockquote class="blockquote">'
                        . '<a class="tweet-content" href="tweet.php?tweetId='.$userTweets[$i]->getId().'">'.$userTweets[$i]->getText().'<br/>'
                        . '<small>Liczba komentarzy: ' . $commentsNumber . '</small></a>'
                        . '</blockquote>'
                    . '</div><br/>';
            }
            ?>
        </div>

        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </body>
</html>