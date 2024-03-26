<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/faq.class.php');
require_once(__DIR__ . '/../utils/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['query'])) {
        $db = getDatabaseConnection();
        $faq = htmlentities($_GET['query']);

        $session = new Session();
        if (!$session->isLoggedIn()) {
            http_response_code(401); // Unauthorized
            echo json_encode(array('error' => 'You must be logged in to perform this action'));
            exit;
        }

        if (!empty($faq)) {
            $faqs = FAQ::searchFAQs($db, $faq);
        } else {
            $faqs = array(); // Empty array if no query is provided
        }

        http_response_code(200); // OK
        header('Content-Type: application/json');
        echo json_encode($faqs);
        exit;
    }
}

http_response_code(400); // Bad Request
