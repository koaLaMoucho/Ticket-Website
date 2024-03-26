<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();


require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/faq.class.php');

$db = getDatabaseConnection();

$question = $_POST['question'];
if (empty($question)) {
    header('Location: ../pages/faq.php');
    exit;
}
$answer = $_POST['answer'];
if (empty($answer)) {
    header('Location: ../pages/faq.php');
    exit;
}

if(!preg_match('/^[^<>]+$/', $answer) || !preg_match('/^[^<>]+$/', $question)) {
    $session->addMessage('error', 'Question/Answer can only contain letters, numbers and spaces');
    http_response_code(400);
    die(header('Location: ../pages/faq.php'));
}

if(!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to add a FAQ');
    die(header('Location: ../pages/login.php'));
}

if($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'CSRF token is invalid');
    http_response_code(403);
    die(header('Location: ../pages/faq.php'));
}

$role = User::getUserRoleByID($db, $_SESSION['id']);
if($role === 'client') {
    $session->addMessage('error', 'You need to be an admin or an agent to add a FAQ');
    http_response_code(403);
    die(header('Location: ../pages/faq.php'));
}




try {
    FAQ::addFAQ($db, $question, $answer);
} catch (PDOException $e) {
    die($e->getMessage());
}

header('Location: ../pages/faq.php');
exit;
