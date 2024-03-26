<?php

declare(strict_types=1);

class User{
    public int $user_id;
    public string $name;
    public string $username;
    public string $email;
    public  string $role;

    public function __construct(int $user_id, string $name, string $username,string $email, string $role){
      $this->user_id = $user_id;
      $this->name = $name;
      $this->username = $username;
      $this->email = $email;
      $this->role = $role;
    }

    public static function getIDByUsername(PDO $db, string $username) : ?int {
      $query = $db->prepare('SELECT * FROM users WHERE username = ?');
      $query->execute(array($username));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      return (int)$row['user_id'];
    }

    public static function getIDByEmail(PDO $db, string $email) : ?int {
      $query = $db->prepare('SELECT * FROM users WHERE email = ?');
      $query->execute(array($email));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      return (int)$row['user_id'];
    }


    public static function getIDByName(PDO $db, string $name) : ?int {
      $query = $db->prepare('SELECT * FROM users WHERE name = ?');
      $query->execute(array($name));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      return (int)$row['user_id'];
    }
    
    public static function getUsernameByID(PDO $db,int $userID){
      $query = $db->prepare('SELECT * FROM users WHERE user_id = ?');
      $query->execute(array($userID));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      return $row['username'];
    }

    public static function getUserRoleByID(PDO $db,int $userID){
      $query = $db->prepare('SELECT * FROM users WHERE user_id = ?');
      $query->execute(array($userID));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      return $row['role'];
    }

    public static function getNameByID(PDO $db,int $userID){
      $query = $db->prepare('SELECT * FROM users WHERE user_id = ?');
      $query->execute(array($userID));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      
      return $row['name'] == null ? "Unassigned" : $row['name'];
    }

    public static function getEmailByID(PDO $db,int $userID){
      $query = $db->prepare('SELECT * FROM users WHERE user_id = ?');
      $query->execute(array($userID));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      return $row['email'];
    }
    
    public static function getDepartmentByID(PDO $db,int $userID){
      $query = $db->prepare('SELECT * FROM users WHERE user_id = ?');
      $query->execute(array($userID));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      $dept = $row['department_id'];

      $query = $db->prepare('SELECT * FROM departments WHERE department_id = ?');
      $query->execute(array($dept));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      return $row['department_name'];
    }

    public static function getUsers(PDO $db) : array {
      $query = $db->prepare('SELECT * FROM users');
      $query->execute();
      $users = array();
      while($user = $query->fetch(PDO::FETCH_ASSOC)){
          $users[] = new User((int) $user['user_id'], $user['name'] , $user['username'], $user['email'], $user['role']);
      }
      return $users;
    }

    public static function getAgents(PDO $db) : array {
      $query = $db->prepare('SELECT * FROM users WHERE role = "agent"');
      $query->execute();
      $agents = array();
      while($agent = $query->fetch(PDO::FETCH_ASSOC)){
          $agents[] = new User((int) $agent['user_id'], $agent['name'], $agent['username'], $agent['email'], $agent['role']);
      }
      return $agents;
    }

    public static function getAssignees(PDO $db) : array {
      $query = $db->prepare('SELECT * FROM users WHERE role = "agent" OR role = "admin"');
      $query->execute();
      $assignees = array();
      while($assignee = $query->fetch(PDO::FETCH_ASSOC)){
          $assignees[] = new User((int) $assignee['user_id'], $assignee['name'], $assignee['username'], $assignee['email'], $assignee['role']);
      }
      return $assignees;
    }
        
    public static function getUserWithPassword(PDO $db, string $email, string $password) : ?User {
      $query = $db->prepare('SELECT user_id, username, name, password, email, role
        FROM users 
        WHERE lower(email) = ?
      ');

      $query->execute(array(strtolower($email)));

      $user = $query->fetch();

      if($user && password_verify($password, $user['password'])) {
        return new User(
          (int)$user['user_id'],
          $user['name'],
          $user['username'],
          $user['email'],
          $user['role'],
        );
      } else return null;
    }

    public static function newUser(PDO $db, string $username, string $name,  $password, string $email)
    {

        $stmt = $db->prepare('INSERT INTO "users" VALUES (NULL, ? , ? , ? , ? , "client", NULL)');

        $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        $stmt->execute(array($username, $name, $password, strtolower($email)));
    }

    public static function userExists(PDO $db, string $email): bool
    {
        $stmt = $db->prepare('SELECT user_id
            FROM users 
            WHERE lower(email) = ? 
      ');

        $stmt->execute(array(strtolower($email)));

        if ($owner = $stmt->fetch()) {
            if (empty($owner)) {
                return false;
            }
            return true;
        } else return false;
    }

    public static function updateUserInfo(PDO $db, string $username, string $name, string $email, int $user_id)
    {
        $stmt = $db->prepare('UPDATE users SET username = ?, name = ?, email = ? WHERE user_id = ?');

        $stmt->execute(array($username, $name, $email, $user_id));
    }

    public static function updateUserRole(PDO $db, int $user_id, string $role)
    {
        $stmt = $db->prepare('UPDATE users SET role = ? WHERE user_id = ?');

        $stmt->execute(array($role, $user_id));
    }

    public static function updateUserDept(PDO $db, int $user_id, string $department)
    {
      $stmt = $db->prepare('SELECT * FROM departments WHERE department_name = ?');
      $stmt->execute(array($department));
      $dept = $stmt->fetch(PDO::FETCH_ASSOC);
      $dept_id = $dept['department_id'];

        $stmt = $db->prepare('UPDATE users SET department_id = ? WHERE user_id = ?');

        $stmt->execute(array($dept_id, $user_id));
    }

    public static function changePassword(PDO $db, string $password, int $user_id)
    {
        $stmt = $db->prepare('UPDATE users SET password = ? WHERE user_id = ?');

        $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        $stmt->execute(array($password, $user_id));
    }

    public static function getAdminsCount(PDO $db) : int {
      $query = $db->prepare('SELECT COUNT(*) FROM users WHERE role = "admin"');
      $query->execute();
      $row = $query->fetch(PDO::FETCH_ASSOC);
      return (int)$row['COUNT(*)'];
    }


}   
?>