<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    echo json_encode(array('error' => 'You must be logged in to perform this action'));
    http_response_code(401); // Unauthorized
    die(header('Location: ../pages/login.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['query'])) {
        $db = getDatabaseConnection();
        $tag = htmlentities($_GET['query']);

        $tags[] = array();
        if (!empty($tag)) {
            $tags = Tag::searchTags($db, $tag);
            
        }

        http_response_code(200); // OK
        echo json_encode($tags);
        die();

    } else if(isset($_GET['exists'])){
        $db = getDatabaseConnection();
        $tag = htmlentities($_GET['exists']);

        if (!empty($tag)) {
            $tags = Tag::searchTags($db, $tag);
            if(array_search($tag, $tags) !== false){
                $exists = true;
            } else {
                $exists = false;
            }
        }

        http_response_code(200); // OK
        echo json_encode($exists);
        die();
    }
}

    if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
        if(isset($_GET['id'])){
            $db = getDatabaseConnection();
            $id = htmlentities($_GET['id']);
            
            $session = new Session();
            if(!$session->isLoggedIn()) {
                echo json_encode(array('error' => 'You must be logged in to perform this action'));
                http_response_code(401); // Unauthorized
                die(header('Location: ../pages/login.php'));
            }

            if(!empty($id)){
                Tag::deleteNewTag($db, $id);
            } 

            http_response_code(200); // OK
            echo json_encode("Success");
            die();
        }

    }

    header("HTTP/1.1 400 Bad Request");


