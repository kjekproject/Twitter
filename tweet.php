<?php

session_start();

if (!isset($_SESSION['loggedUserId'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';

//obługa dodawania komentarza
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['text']) & strlen(trim($_POST['text'])) > 0) {
        $newComment = new Comment();
        $newComment->setUserId($_SESSION['loggedUserId']);
        $newComment->setTweetId($_GET['tweetId']);
        $newComment->setText($_POST['text']);
        $newComment->setCreationDate(date('Y-m-d H:i:s'));
        $newComment->saveToDB($conn);
    }
}

//sprawdzenie czy podano id tweeta
if(isset($_GET['tweetId'])) {
    $tweetId = $_GET['tweetId'];
} else {
    header('Location: index.php');
    exit();
}
var_dump($tweetId);
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Twitter - tweet</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    
    <body>
        
        <nav id="header" class="navbar fixed-top font-italic font-weight-bold">
            <span class="navbar-brand">KJ_TWITTER</span>
            <a class="btn btn-primary navbar-togler navbar-toggler-right" href="logout.php">Wyloguj</a>
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
                <li class="nav-item menu-unact rounded-top">
                    <a class="nav-link" href="user_edition.php">Twoje konto</a>
                </li>
            </ul>
        </div>
            
        <div id="main-content" class="container rounded">
                <?php
                //wyświetlanie tweeta
                $tweet = Tweet::loadTweetById($conn, $tweetId);
                    echo '<div>'
                            . '<a class="user-link" href="user.php?userId=' . $tweet->getUserId() . '">'
                            . $tweet->getUserName() . '</a> ' . $tweet->getCreationDate()
                            . ' napisał/a: <br/>' 
                            . '<blockquote class="blockquote">'.$tweet->getText().'</blockquote>'
                        . '</div><br/>';
                ?>
            <!--formularz dodawania nowego komentarza-->
            <form method="POST" action="#">
                <fieldset class="form-group">
                    <legend><small>Tu możesz dodać komentarz do tego tweeta</small></legend>
                    <div class="form-group">
                        <label>Twój komentarz (maksymalnie 60 znaków)</label><br />
                        <textarea type="text" name="text" maxlength="60" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-secondary" value="newTweet">Dodaj komentarz</button>
                </fieldset>
            </form>
            
            <div>
                <!--wyświetlanie wszystkich komentarzy do tweeta-->
                <h5>Komentarze:</h5>
                <?php
                $comments = Comment::loadCommentsByTweetId($conn, $tweetId);
                for($i = 0; $i < count($comments); $i++) {
                    echo '<div>'
                            . '<a class="user-link" href="user.php?userId=' . $comments[$i]->getUserId() . '">' . $comments[$i]->getUserName().'</a> '
                            . $comments[$i]->getCreationDate().' napisał/a: <br/>'
                            . $comments[$i]->getText() . '<br/>'
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
