<?php
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/ticket.tpl.php');
    require_once(__DIR__ . '/../templates/profile.tpl.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticket.class.php');
    require_once(__DIR__ . '/../database/users.class.php');

    //Requires login to access this page
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if(!$session->isLoggedIn()){
        die(header('Location: ../pages/login.php'));
    }

    $db = getDatabaseConnection();
    drawHeader($session, $db);

    $tickets = Ticket::getTicketsByID($db, $session->getID());
    $username = User :: getUsernameByID($db, $session->getID());
    $name = User:: getNameByID($db, $session->getID());
    $email = User:: getEmailByID($db, $session->getID());

    drawEditProfile($db, $username, $name, $email, $session->getID());
    drawTickets($tickets);


    drawFooter();
?>