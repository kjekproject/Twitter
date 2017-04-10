<?php

session_start();

if (!isset($_SESSION['loggedUserId'])) {
    header('Location: login.php');
    exit();
}

require_once 'config.php';
require_once 'src/Message.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['recipientName'])
        && isset($_POST['recipientId'])
        && isset($_POST['text'])) 
    {
        $recipientId = $_POST['recipientId'];
        $text = $_POST['text'];
        
        $newMessage = new Message();
        $newMessage->setAuthorId($_SESSION['loggedUserId']);
        $newMessage->setRecipientId($recipientId);
        $newMessage->setText($text);
        $newMessage->setCreationDate(Date('Y-m-d H:i:s'));
        
        if($newMessage->saveToDb($conn) == TRUE) {
            $_SESSION['sendMessageMsg'] = '<div class="alert alert-success" role="alert">Wiadomość została wysłana.</div><br/>';
        } else {
            $_SESSION['sendMessageMsg'] = '<div class="alert alert-danger" role="alert">Wystąpił bład podczas wysyłania wiadomości.</div><br/>';
        }     
    }
}
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Twitter - wiadomości</title>
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
                <li class="nav-item menu-act rounded-top">
                    <a class="nav-link active" href="messages.php">Wiadomości</a>
                </li>
                <li class="nav-item menu-unact rounded-top">
                    <a class="nav-link" href="user_edition.php">Twoje konto</a>
                </li>
            </ul>
        </div>
            
        <div id="main-content" class="container rounded">
            <?php
            if(isset($_SESSION['sendMessageMsg'])) {
                echo $_SESSION['sendMessageMsg'];
                unset($_SESSION['sendMessageMsg']);
            }
            ?>          
            <div class="row">
                <div class="col-md-6">
                    <?php
                    $sentMsgs = Message::loadMessageByAuthorId($conn, $_SESSION['loggedUserId']);
                    if($sentMsgs != NULL) {
                        echo '<h5>Wysłane wiadomości:</h5><br>';
                        foreach($sentMsgs as $msg) {
                            $text = substr($msg->getText(), 0, 30);
                            echo '<div>'
                                . 'Wiadomość wysłana '.$msg->getCreationDate()
                                .' do <a class="user-link" href="user.php?userId='.$msg->getRecipientId().'">'.$msg->getRecipientName().'</a><br/>'
                                .'<a class="msgStat'.$msg->getStatus().'" href="message.php?messageId='.$msg->getId().'">'.$text.'</a>'
                                .'</div><br/>';
                        }
                    } else {
                        echo '<h5>Brak wysłanych wiadomości.</h5>';
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    $receivedMsgs = Message::loadMessageByRecipientId($conn, $_SESSION['loggedUserId']);
                    if($receivedMsgs != NULL) {
                        echo '<h5>Otrzymane wiadomości:</h5><br>';
                        foreach($receivedMsgs as $msg) {
                            $text = substr($msg->getText(), 0, 30);
                            echo '<div>'
                                . 'Wiadomość otrzymana '.$msg->getCreationDate()
                                .' od <a class="user-link" href="user.php?userId='.$msg->getAuthorId().'">'.$msg->getAuthorName().'</a><br/>'
                                .'<a class="msgStat'.$msg->getStatus().'" href="message.php?messageId='.$msg->getId().'">'.$text.'</a>'
                                .'</div><br/>';
                        }
                    } else {
                        echo '<h5>Brak otrzymanych wiadomości.</h5>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </body>
</html>
