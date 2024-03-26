<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/faq.tpl.php');
    
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/faq.class.php');
    require_once(__DIR__ . '/../database/users.class.php');

    //Requires login to access this page
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    $id = $session->getId();

    if(!$session->isLoggedIn()){
        die(header('Location: ../pages/login.php'));
    }

    $db = getDatabaseConnection();
    $user_role = User::getUserRoleByID($db,$id);

    $faqs = FAQ::getFAQs($db);
    drawHeader($session, $db);
    
    // drawTicketFilters();
    drawFAQs($faqs,$user_role);

    drawFooter();

?>