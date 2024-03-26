<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/users.class.php');

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    $db = getDatabaseConnection();

    $user_id = (int) $_POST['user_id'];
	$role = $_POST['role'];
    $dept = $_POST['department'];

    if (!$session->isLoggedIn()) {
        $session->addMessage('error', 'You must be logged in to edit a user');
        die(header('Location: ../pages/login.php'));
    }

    if ($_SESSION['csrf'] !== $_POST['csrf']) {
        $session->addMessage('error', 'CSRF token is invalid');
        http_response_code(403);
        die(header('Location: ../pages/dashboard_users.php'));
    }

    if ($dept === "-"){
        $dept = "";
    }
	
	if ($role === "client"){
        if (!empty($dept)){
            $session->addMessage('error', "You cannot add a department to a client!");

            die(header("Location: ../pages/dashboard_users.php"));
        }
    }

    if ($user_id === $session->getID()){
    
        if ($role!=="admin"){
            if (User::getAdminsCount($db) === 1){
                $session->addMessage('error', "You cannot remove the only admin!");

                die(header("Location: ../pages/dashboard_users.php"));
            }
            else{
                User::updateUserRole($db, $user_id, $role);
                User::updateUserDept($db, $user_id, $dept);
                
                die(header("Location: ../pages/home.php"));

            }
        }
    }

	User::updateUserRole($db, $user_id, $role);
    User::updateUserDept($db, $user_id, $dept);
    $session->addMessage('success', "Update successful!");

    die(header("Location: ../pages/dashboard_users.php"));
?>