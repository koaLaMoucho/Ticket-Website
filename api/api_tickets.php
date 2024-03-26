<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticket.class.php');
    require_once(__DIR__ . '/../utils/session.php');
    
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if(isset($_GET['search'])){
            $db = getDatabaseConnection();
            
            $session = new Session();
            if(!$session->isLoggedIn()) {
                echo json_encode(array('error' => 'You must be logged in to edit a ticket'));
                http_response_code(401); // Unauthorized
                die(header('Location: ../pages/login.php'));
            }
            
            $search = htmlentities($_GET['search']);
            if(empty($search)){
                $tickets = Ticket::getTickets($db);
            } else {
                $tickets = Ticket::searchTickets($db, $search);
            }

            http_response_code(200); // OK
            echo json_encode($tickets);
            die();
        }

        // filter
        else {
            $db = getDatabaseConnection();

            $status = $_GET['status'];
            $assignee = $_GET['assignee'];
            $department = $_GET['department'];
            $requester = $_GET['requester'];
            $tag = $_GET['tag'];
            $order = $_GET['order'];

            $session = new Session();
            if(!$session->isLoggedIn()) {
                echo json_encode(array('error' => 'You must be logged in to edit a ticket'));
                http_response_code(401); // Unauthorized
                die(header('Location: ../pages/login.php'));
            }

            if($status == "all" && $assignee == "all" && $department == "all" && $requester == "all" && $tag == "" && $order == "DESC"){
                $tickets = Ticket::getTickets($db);
            } else {
                $tickets = Ticket::filterTickets($db, (int) $status, (int) $assignee, (int) $department, (int) $requester, $tag, $order);
            }

            http_response_code(200); // OK
            echo json_encode($tickets);
            die();
        }


    }

    header("HTTP/1.1 400 Bad Request");
    
?>