<?php
declare(strict_types=1);

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/faq.tpl.php');
require_once(__DIR__ . '/../templates/dashboard_users.tpl.php');

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/faq.class.php');
require_once(__DIR__ . '/../database/users.class.php');

//Requires login to access this page
require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if(!$session->isLoggedIn()){
    die(header('Location: ../pages/login.php'));
}

$db = getDatabaseConnection();

if((User:: getUserRoleByID($db, $session->getID())) !== "admin"){
    die(header('Location: ../pages/home.php'));
}

$users = User::getUsers($db);


drawHeader($session, $db);
drawUsers($users, $db, $session);


drawFooter();

?>