<?php

require_once './src/User.php';
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