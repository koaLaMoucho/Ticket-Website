<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/users.class.php');

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    $db = getDatabaseConnection();

    if (empty($_POST['email']) || empty($_POST['password'])) {
        $session->addMessage('error', 'Please fill all the fields');
        die(header('location: ' . $_SERVER['HTTP_REFERER']));
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) !== $email) {
        $session->addMessage('error', 'Invalid email');
        die(header('location: ' . $_SERVER['HTTP_REFERER']));
    }

    if (preg_match('^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$', $password) === 0) {
        $session->addMessage('error', 'Invalid password');
        die(header('location: ' . $_SERVER['HTTP_REFERER']));
    }

    $user = User::getUserWithPassword($db, $email, $password);

    if ($user !== null) {
        $session->setId($user->user_id);
        $session->setName($user->username);
        die(header("Location: ../pages/index.php"));
    } else {  
        $session->addMessage('error', 'Wrong email or password!');
    }

    die(header("Location: ../pages/login.php"));
?>