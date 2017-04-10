<?php

require_once './src/User.php';
require_once './src/Tweet.php';
require_once './src/Comment.php';
require_once './src/Message.php';
require_once 'config.php';

/*
**sprawdzanie User

$email = 'user@mail.com';
$name = 'user1';
$pass = 'qwerty';

$user = new User();
$user->setEmail($email);
$user->setUserName($name);
$user->setPassword($pass);
var_dump($user);
$user->saveToDB($conn);
$us = User::loadUserByEmail($conn, $email);
var_dump($us);
$u = User::login($conn, $email, $pass);
var_dump($u);


$user1 = new User();
$user1->setUserName('michal');
$user1->setEmail('michal@mail.com');
$user1->setPassword('qwerty');
var_dump($user1);
$user1->saveToDB($conn);

$user2 = new User();
$user2->setUserName('romek');
$user2->setEmail('romek@mail.com');
$user2->setPassword('qwerty');
var_dump($user2);
$user2->saveToDB($conn);

$b = User::loadAllUsers($conn);
var_dump($b);

$uu = User::loadUserById($conn, 3);
var_dump($uu);
$uu->setUserName('kuba');
$uu->setEmail('kuba@gmail.com');
$uu->setPassword('qwerty');
echo '<br/>'.$uu->saveToDB($conn);

$uu = User::loadUserById($conn, 3);
var_dump($uu);
$uu->delete($conn);
echo '<br/>'.$uu->getId();
 * 
 */

/*
$newTweet = new Tweet();
$newTweet->setUserId(1);
$newTweet->setText('To jest mój pierwszy tweet. Hello world!');
$newTweet->setCreationDate('2017-04-04 12:44:12');
$newTweet->saveToDB($conn);

$newTweet = new Tweet();
$newTweet->setUserId(2);
$newTweet->setText('Hello world!');
$newTweet->setCreationDate('2017-04-04 12:47:12');
$newTweet->saveToDB($conn);

$allTweets = Tweet::loadAllTweets($conn);
var_dump($allTweets);

$firstTweet = Tweet::loadTweetById($conn, 3);
var_dump($firstTweet);

$firstUserTweet = Tweet::loadAllTweetsByUserId($conn, 1);
var_dump($firstUserTweet);

$firstTweet = Tweet::loadTweetById($conn, 3);
var_dump($firstTweet);
$text = $firstTweet->getText().' Hello!! hello!!';
$firstTweet->setText($text);
$firstTweet->saveToDB($conn);
$firstTweetChanged = Tweet::loadTweetById($conn, 3);
var_dump($firstTweetChanged);
 * 
 */
/*
$comment = new Comment();
$comment->setUserId(15);
$comment->setTweetId(1);
$comment->setCreationDate('2017-04-05 21:47:12');
$comment->setText('Hello!');
$comment->saveToDB($conn);

$comment = new Comment();
$comment->setUserId(15);
$comment->setTweetId(1);
$comment->setCreationDate('2017-04-05 21:48:12');
$comment->setText('Witam!!');
$comment->saveToDB($conn);

$comments = Comment::loadCommentsByTweetId($conn, 1);
var_dump($comments);

$comment = Comment::loadCommentById($conn, 1);
var_dump($comment);

$commentsNumber = Comment::getCommentsNumberByTweetId($conn, 1);
echo $commentsNumber;
*/

/*
$message = new Message();
$message->setAuthorId(17);
$message->setRecipientId(16);
$message->setText('Witam, co u ciebie słychać?');
$message->setCreationDate(date('Y-m-d H:i:s'));
var_dump($message);
$message->saveToDb($conn);
 * 
 */

$message1 = Message::loadMessageById($conn, 1);
var_dump($message1);
$message2 = Message::loadMessageByAuthorId($conn, 16);
$message3 = Message::loadMessageByAuthorId($conn, 17);
var_dump($message2);
var_dump($message3);
$message4 = Message::loadMessageByRecipientId($conn, 16);
$message5 = Message::loadMessageByRecipientId($conn, 17);
var_dump($message4);
var_dump($message5);