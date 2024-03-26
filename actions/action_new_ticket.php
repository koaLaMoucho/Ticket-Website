<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/ticket.class.php');
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/department.class.php');

    $session = new Session();

    if(!$session->isLoggedIn()){
        $session->addMessage('error', 'You must be logged in to create a ticket');
        die(header('Location: ../pages/login.php'));
    }

    $db = getDatabaseConnection();

    $subject = $_POST['subject'];
    $description = $_POST['description'];
    
    if (!empty($_POST['department'])){
        $department_id = (int) Department::getDepartmentID($db, $_POST['department']);
    }
    else{
        $department_id = 0;
    }
    $tags = $_POST['tags'];
    $user_id = $session->getID();

    if(!preg_match('/^[^<>]+$/', $subject)){
        $session->addMessage('error', 'Subject can only contain letters, numbers and spaces');
        http_response_code(403);
        die(header('Location: ../pages/home.php'));
    }

    if(!preg_match('/^[^<>]+$/', $description)){
        $session->addMessage('error', 'Description can only contain letters, numbers and spaces');
        http_response_code(403);
        die(header('Location: ../pages/home.php'));
    }

    if(!preg_match('/^[^<>]+$/', $tags)){
        $session->addMessage('error', 'Tags can only contain letters, numbers and spaces');
        http_response_code(403);
        die(header('Location: ../pages/home.php'));
    }

    if($_SESSION['csrf'] !== $_POST['csrf']){
        $session->addMessage('error', 'CSRF token is invalid');
        http_response_code(403);
        die(header('Location: ../pages/home.php'));
    }

    if(empty($subject) || empty($description)){
        $session->addMessage('error', 'Please fill all the fields');
        http_response_code(403);
        die(header('Location: ../pages/home.php'));
    }


    try{
        Ticket::addTicket($db, $user_id, $subject, $description, $department_id, new DateTime(), $tags);
    } catch(PDOException $e){
        die($e->getMessage());
    }
    
    //latter change this to the new ticket page
    header('Location: ../pages/home.php');
