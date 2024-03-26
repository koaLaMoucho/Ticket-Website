<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');


$db = getDatabaseConnection();

$ticket_id = (int)$_POST['ticketID'];

if(!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to delete a ticket');
    die(header('Location: ../pages/login.php'));
}

if($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'CSRF token is invalid');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}   

$role = User::getUserRoleByID($db, $_SESSION['id']);
if($_SESSION['id'] != $_POST['user_id'] && $role === 'client') {
    $session->addMessage('error', 'You can only delete your own tickets');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

try {
    Ticket::deleteTicket($db, $ticket_id);
} catch(PDOException $e) {
    die($e->getMessage());
}

header('Location: ../pages/home.php');
exit;



?>