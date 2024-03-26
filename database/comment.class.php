<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../database/users.class.php');
  

    class Comment{
        public int $user_id;
        public int $ticket_id;
        public string $username;
        public string $content;
        public DateTime $created_at;
        public  string $role;
        
        public function __construct(int $user_id, int $ticket_id, string $username, string $content, string $created_at, string $role){
            $this->user_id = $user_id;
            $this->ticket_id = $ticket_id;
            $this->username = $username;
            $this->content = $content;
            $this->created_at = new DateTime($created_at);
            $this->role = $role;
        }

        static function getComments(PDO $db, int $id) : array{
            $query = $db->prepare('SELECT * FROM ticket_replies where ticket_id = ?');
            $query->execute(array($id));
            $comments = [];
            while($comment = $query->fetch()){
                $comments[] = Comment::createComment($db,$comment);
        
            }
            
            return $comments;
        }

        static private function createComment(PDO $db, array $comment){    
            return new Comment(
                intval($comment['user_id']),
                intval($comment['ticket_id']),
                User::getNameByID($db,(int)($comment['user_id'])),
                $comment['content'],
                $comment['created_at'],
                $comment['user_role']

            );
        }

        public static function addComment(PDO $db, int $ticket_id, int $user_id, string $user_role, string $content, DateTime $created_at) {
            $stmt = $db->prepare('INSERT INTO ticket_replies (ticket_id, user_id, user_role, content, created_at) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(array($ticket_id, $user_id, $user_role, $content, $created_at->format('Y-m-d H:i:s')));
        }
        
    }
?>