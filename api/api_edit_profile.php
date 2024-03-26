<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if(!$session->isLoggedIn()) {
	echo json_encode(array('error' => 'You need to be logged in perform this action'));
	http_response_code(401); // Unauthorized
	die(header('Location: ../pages/login.php'));
}


if ($_SERVER['REQUEST_METHOD'] === 'PUT'){

	$db = getDatabaseConnection();
	
	$requestBody = file_get_contents('php://input');
	
	parse_str($requestBody, $data);
	
	$user_id = intval($data['user_id']);

	$username = $data['username'];
	if(preg_match('^[a-zA-Z0-9_-]{3,16}$', $username) === 0){
		http_response_code(401); // OK
		echo json_encode(array('error' => 'Invalid username!'));
		die();
	}

	$email = $data['email'];
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);  
	if(filter_var($email, FILTER_VALIDATE_EMAIL) !== $email){
		http_response_code(401); // OK
		echo json_encode(array('error' => 'Invalid email!'));
		die();
	}

	$name = $data['name'];
	if(filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS) !== $name){
		http_response_code(401); // OK
		echo json_encode(array('error' => 'Invalid name!'));
		die();
	}

	$password = $data['password'];
	$confirm_password = $data['confirm_password'];


	if (!empty($password) && !empty($confirm_password)){

		if(preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password) === 0){
			http_response_code(401); // OK
			echo json_encode(array('error' => 'Invalid password!'));
			die();
		}
		if(preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $confirm_password) === 0){
			http_response_code(401); // OK
			echo json_encode(array('error' => 'Invalid password!'));
			die();
		}

		if ($password === $confirm_password){
			User:: changePassword($db, $password, $user_id);
		}
		else{
			http_response_code(401); // OK
	
			echo json_encode(array('error' => 'Passwords did not match!'));
	
			die();
		}
	}
	
	User::updateUserInfo($db, $username, $name, $email, $user_id);

	// $tickets = Ticket::getTicketsByID($db, $user_id);
	
	// header('Location: ' . $_SERVER['HTTP_REFERER']);
	http_response_code(200); // OK
	
	echo json_encode(array('success' => 'User info updated successfully'));
	
	die();
}

	header("HTTP/1.0 400 Bad Request");
?>