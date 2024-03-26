<?php 
  declare(strict_types=1);

    //Requires login to access this page
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    $id = $session->getId();
    if(!$session->isLoggedIn()){
        die(header('Location: ../pages/login.php'));
    }

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/ticket.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticket.class.php');
    require_once(__DIR__ . '/../database/comment.class.php');
    require_once(__DIR__ . '/../database/status.class.php');
    require_once(__DIR__ . '/../database/priority.class.php');
    require_once(__DIR__ . '/../database/department.class.php');
    require_once(__DIR__ . '/../database/ticket_history.class.php');
    require_once(__DIR__ . '/../database/faq.class.php');
  
  

    require_once(__DIR__ . '/../templates/ticket.tpl.php');
    require_once(__DIR__ . '/../templates/comments.tpl.php');

    $db = getDatabaseConnection();
    
    $ticket = Ticket::getTicket($db,intval($_GET['id']));
    $comments = Comment::getComments($db,intval($_GET['id']));
    $statuses = Status::getStatuses($db);
    $priorities = Priority::getPriorities($db);
    $departments = Department::getDepartments($db);
    $agents = User::getAgents($db);
    $ticket_history = Ticket_History::getTicketHistoryByID($db,intval($_GET['id']));
    $FAQs = FAQ::getFAQs($db);

    $user_role = User::getUserRoleByID($db,$id);
    
    drawHeader($session, $db);
    
    drawTicketPage($ticket,$comments,$statuses,$priorities,$departments,$agents,$ticket_history,$FAQs,$user_role);
    
    drawFooter();

?>