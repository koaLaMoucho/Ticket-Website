<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../templates/common.tpl.php');
    //require_once(__DIR__ . '/../templates/ticket.tpl.php');
    require_once(__DIR__ . '/../templates/login.tpl.php');
    // require_once(__DIR__ . "/../database/connection.php");

    if($session->isLoggedIn()){
        die(header('Location: ../pages/home.php'));
    }

    drawHeaderLoginRegister($session);
    drawLoginForm($session);

    drawFooter();



?>