<?php 
    declare(strict_types=1);

    //Requires login to access this page
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if(!$session->isLoggedIn()){
        die(header('Location: ../pages/login.php'));
    }

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/ticket.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/department.class.php');

    $db = getDatabaseConnection();

    drawHeader($session, $db);

    drawNewTicketActions();
    drawNewTicketContent($session);

    drawFooter();

?>