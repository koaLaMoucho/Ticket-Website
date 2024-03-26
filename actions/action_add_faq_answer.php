<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/faq.class.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/status.class.php');
require_once(__DIR__ . '/../database/priority.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');

$db = getDatabaseConnection();

$question = $_POST['question'];
$ticket_id = (int)$_POST['ticketID'];
$answer = $_POST['answer'];
$user_id = (int)$session->getID();
$updated_at = new DateTime();

if (empty($answer)) {
    $session->addMessage('error', 'Please fill all the fields');
    header('Location: ../pages/ticket.php?id=' . $ticket_id);
    exit;
}

if(!preg_match('/^[^<>]+$/', $answer)|| !preg_match('/^[^<>]+$/', $question)) {
    $session->addMessage('error', 'Question/Answer can only contain letters, numbers and white spaces');
    header('Location: ../pages/ticket.php?id=' . $ticket_id);
    exit;
}

if (!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to add an answer');
    die(header('Location: ../pages/login.php'));
}

if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'CSRF token is invalid');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

$role = User::getUserRoleByID($db, $user_id);
if ($role === 'client') {
    $session->addMessage('error', 'You need to be an admin or an agent to add a FAQ Answer');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}


try {
    Ticket::editTicketStatusClosed($db, $ticket_id, $user_id, $updated_at);
    FAQ::addFAQ($db, $question, $answer);
} catch (PDOException $e) {
    die($e->getMessage());
}

header('Location: ../pages/faq.php');
exit;
