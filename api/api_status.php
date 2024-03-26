<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/status.class.php');
    require_once(__DIR__ . '/../utils/session.php');
    

    if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
        if(isset($_GET['id'])){
            $db = getDatabaseConnection();
            $id = (int) htmlentities($_GET['id']);
            
            $session = new Session();
            if(!$session->isLoggedIn()) {
                echo json_encode(array('error' => 'You must be logged in to perform this action'));
                http_response_code(401); // Unauthorized
                die(header('Location: ../pages/login.php'));
            }

            if(!empty($id)){
                Status::deleteStatus($db, $id);
            } 

            http_response_code(200); // OK
            echo json_encode("Success");
            die();
        }

    }
    header("HTTP/1.1 400 Bad Request");

?>