<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();


require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/faq.class.php');

$db = getDatabaseConnection();

if(!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to remove FAQ');
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


$faq_id = (int)$_POST['faq_id'];


try {
    FAQ::removeFAQ($db, $faq_id);
} catch (PDOException $e) {
    die($e->getMessage());
}

header('Location: ../pages/faq.php');
exit;
