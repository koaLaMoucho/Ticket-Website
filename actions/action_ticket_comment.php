<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to comment');
    die(header('Location: ../pages/login.php'));
}

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/comment.class.php');

$db = getDatabaseConnection();


$ticket_id = (int)$_POST['ticket_id'];
$user_id = (int)$session->getID();
$user_role = User::getUserRoleByID($db, $user_id);
$content = $_POST['content'];
$created_at = new DateTime();

if(!preg_match('/^[^<>]+$/', $content)){
    $session->addMessage('error', 'Content can only contain letters, numbers and spaces');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

if($_SESSION['csrf'] !== $_POST['csrf']){
    $session->addMessage('error', 'CSRF token is invalid');
    die(header('Location: ../pages/home.php'));
}

if(empty($content)){
    $session->addMessage('error', 'Please fill all the fields');
    die(header('Location: ../pages/home.php'));
}

try {
    Comment::addComment($db, $ticket_id, $user_id, $user_role, $content, $created_at);
} catch (PDOException $e) {
    die($e->getMessage());
}

header('Location: ../pages/ticket.php?id=' . $ticket_id);
exit;
