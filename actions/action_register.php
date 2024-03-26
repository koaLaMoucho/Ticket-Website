<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

$db = getDatabaseConnection();


if(empty($_POST['username']) || empty($_POST['name']) || empty($_POST['password']) || empty($_POST['email'])){
    $session->addMessage('error', 'Please fill all the fields');
    die(header('location: ' . $_SERVER['HTTP_REFERER']));
}

$name = $_POST['name'];
$username = $_POST['username'];
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];

if(preg_match('^[a-zA-Z0-9_-]{3,16}$', $username) === 0){
    $session->addMessage('error', 'Invalid username');
    die(header('location: ' . $_SERVER['HTTP_REFERER']));
}

if(preg_match('^(?:[A-Z][a-z]* ?)+$', $name) === 0){
    $session->addMessage('error', 'Invalid name');
    die(header('location: ' . $_SERVER['HTTP_REFERER']));
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $session->addMessage('error', 'Invalid email');
    die(header('location: ' . $_SERVER['HTTP_REFERER']));
}

if(preg_match('^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$', $password) === 0){
    $session->addMessage('error', 'Invalid password');
    die(header('location: ' . $_SERVER['HTTP_REFERER']));
}

// register error and go back with the information
if (User::userExists($db, $email)) {
    $session->addMessage('error', 'Account with that email already exists');
    die(header('location: ' . $_SERVER['HTTP_REFERER']));
}

$user_id = User::getIDByUsername($db, (string)$username);
if ($user_id !== 0) {    
    $session->addMessage('error', 'Account with that username already exists');
    die(header('location: ' . $_SERVER['HTTP_REFERER']));
}

User::newUser($db, $username, $name, $password, $email);

die(header("Location: ../pages/index.php"));
?>