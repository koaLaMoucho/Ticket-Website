<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    $session->logout();

    if($_SESSION['csrf'] !== $_POST['csrf']){
        $session->addMessage('error', 'CSRF token is invalid');
        die(header('Location: ../pages/home.php'));
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>