<?php
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/ticket.tpl.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticket.class.php');
    require_once(__DIR__ . '/../database/users.class.php');
    require_once(__DIR__ . '/../database/status.class.php');
    require_once(__DIR__ . '/../database/department.class.php');
    require_once(__DIR__ . '/../database/hashtag.class.php');

    //Requires login to access this page
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if(!$session->isLoggedIn()){
        die(header('Location: ../pages/login.php'));
    }

    $db = getDatabaseConnection();
    $tickets = Ticket::getTickets($db);

    $statuses = Status::getStatuses($db);
    $departments = Department::getDepartments($db);
    $requesters = User::getUsers($db);
    $agents = User::getAssignees($db);
    $tags = Tag::getAllTags($db);

    drawHeader($session, $db);

    drawTickets($tickets);
    drawTicketFilters($statuses, $agents, $departments, $requesters, $tags);

    drawFooter();
?>