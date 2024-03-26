<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to perform this action');
    die(header('Location: ../pages/login.php'));
}


if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'CSRF token is invalid');
    die(header('Location: ../pages/home.php'));
}

$db = getDatabaseConnection();

$user_id = (int)$session->getID();
$ticket_id = (int)$_POST['ticket_id'];
$new_tags = $_POST['tags'];
$current_tags = implode(',', array_map(function ($tag) {
    return $tag->name;
}, Tag::getTagsByTicketID($db, $ticket_id)));

$updated_at = new DateTime();

if ($current_tags === $new_tags) {
    $session->addMessage('success', 'No changes were made, tags are the same');
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

if ($new_tags != strip_tags($new_tags)) {
    $session->addMessage('error', 'Tags can only contain letters, numbers and spaces');
    http_response_code(403);
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

try {
    Tag::updateTicketTags($db, $ticket_id, $new_tags, $user_id, $updated_at);
} catch (PDOException $e) {
    // $session->addMessage('error', 'Failed to update ticket tags');
    $session->addMessage('error', $e->getMessage());
    http_response_code(500); // Internal Server Error
    die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
}

$session->addMessage('success', 'Ticket tags updated successfully');
die(header('Location: ../pages/ticket.php?id=' . $ticket_id));
