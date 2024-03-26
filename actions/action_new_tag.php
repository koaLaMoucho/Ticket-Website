<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/faq.class.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/status.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/priority.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');

$db = getDatabaseConnection();

$new_tag = $_POST['new_tags'];

if($new_tag !== strip_tags($new_tag)){
    $session->addMessage('error', 'Tags can only contain letters, numbers and spaces');
    http_response_code(403);
    die(header('Location: ../pages/dashboard_edit.php'));
}

if (!$session->isLoggedIn()) {
    $session->addMessage('error', 'You must be logged in to add new tags');
    die(header('Location: ../pages/dashboard_edit.php'));
}

if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'CSRF token is invalid');
    die(header('Location: ../pages/home.php'));
}

$role = User::getUserRoleByID($db, $_SESSION['id']);
if ($role !== 'admin') {
    $session->addMessage('error', 'You must be an admin or an agent to add new tags');
    die(header('Location: ../pages/home.php'));
}

if (strpos($new_tag, '#') === 0) {
    $new_tag = $new_tag;
} else {
    $new_tag = '#' . $new_tag;
}

try{
    Tag:: addNewTag($db, $new_tag);  
    
} catch(PDOException $e){
    $session->addMessage('error', 'That hashtag already exists!');
}
die(header('Location: ../pages/dashboard_edit.php')); 


?>