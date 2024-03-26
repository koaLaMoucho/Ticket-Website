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

$ticket_id = (int)$_POST['ticket_id'];
$status_name = $_POST['status'];
$priority_name = $_POST['priority'];
$department_name = $_POST['department'];
$assignee_name = $_POST['new_assignee'];
$db = getDatabaseConnection();


if (!preg_match('/^[^<>]+$/', $status_name)) {
    $session->addMessage('error', 'Status can only contain letters, numbers and spaces!');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

if (!preg_match('/^[^<>]+$/', $priority_name)) {
    $session->addMessage('error', 'Priority can only contain letters, numbers and spaces!');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

if (!preg_match('/^[^<>]+$/', $department_name)) {
    $session->addMessage('error', 'Department can only contain letters, numbers and spaces!');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

if (!preg_match('/^[^<>]+$/', $assignee_name)) {
    $session->addMessage('error', 'Assignee can only contain letters, numbers and spaces!');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

if (!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to edit a ticket');
    die(header('Location: ../pages/login.php'));
}

if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'CSRF token is invalid');
    die(header('Location: ../pages/home.php'));
}

$role = User::getUserRoleByID($db, $_SESSION['id']);
if ($role === 'client') {
    $session->addMessage('error', 'You must be an admin or an agent to edit a ticket');
    die(header('Location: ../pages/home.php'));
}

try {
    $updated_at = new DateTime();

    $status_id = Status::getStatusIDbyName($db, $status_name);
    $priority_id = Priority::getPriorityIDbyName($db, $priority_name);
    $department_id = Department::getDepartmentID($db, $department_name);
    $assignee_id = User::getIDbyName($db, $assignee_name);

    Ticket::editTicket($db, $ticket_id, $_SESSION['id'], $status_id, $priority_id, $department_id, $assignee_id, $updated_at);
} catch (PDOException $e) {
    die($e->getMessage());
}

header('Location: ../pages/ticket.php?id=' . $ticket_id);
exit;
