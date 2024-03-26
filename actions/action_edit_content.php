<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();


require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/status.class.php');
require_once(__DIR__ . '/../database/priority.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');

$db = getDatabaseConnection();

$user_id = (int)$session->getID();
$ticket_id = (int)$_POST['ticketID'];
$new_content = htmlspecialchars($_POST['content']);
$updated_at = new DateTime();

if(!preg_match('/^[^<>]+$/', $new_content)) {
    $session->addMessage('error', 'Content can only contain letters, numbers and spaces');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

if (!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to edit a ticket');
    die(header('Location: ../pages/login.php'));
}

if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'CSRF token is invalid');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

if ($_SESSION['id'] != $_POST['user_id']) {
    $session->addMessage('error', 'You can only edit your own tickets');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

try {
    Ticket::editTicketContent($db, $ticket_id, $new_content, $updated_at, $user_id);
} catch (PDOException $e) {
    die($e->getMessage());
}

header('Location: ../pages/ticket.php?id=' . $ticket_id);
exit;
