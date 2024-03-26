<?php 
    declare(strict_types=1);

    class Tag{
        public $tag_id;
        public $ticket_id;
        public $name;

        public function __construct(int $ticket_id, string $name){
            $this->ticket_id = $ticket_id;
            $this->name = $name;
        }

        public function getTicketID() : int {
            return $this->ticket_id;
        }

        public function getName() : string {
            return $this->name;
        }

        public static function getAllTags(PDO $db) : array {
            $query = $db->prepare('SELECT * FROM ticket_hashtags');
            $query->execute();
            $tags = [];
    
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $row_columns = explode(',', $row['hashtags']);
                foreach($row_columns as $column){
                    $tags[] = new Tag((int) $row['ticket_id'], $column);
                }
            }
    
            return array_unique($tags);
        }

        public static function getTagsByTicketID(PDO $db, int $ticketID) : ?array {
            $query = $db->prepare('SELECT * FROM ticket_hashtags WHERE ticket_id = ?');
            $query->execute(array($ticketID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            
            if(!empty($row)){
                $row_columns = explode(',', $row['hashtags']);
                foreach($row_columns as $column){
                    $tags[] = new Tag((int) $row['ticket_id'], $column);
                }
                return $tags;
            }else{
                return null;
            }  
        }

        // This function is only used for array_unique() to work properly
        public function __toString() {
            return $this->name;
        }

        public static function searchTags(PDO $db, string $search) : array {
            $query = $db->prepare('SELECT * FROM ticket_hashtags WHERE hashtags LIKE ?');
            $query->execute(array("%$search%"));
            $tags = [];

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $row_columns = explode(',', $row['hashtags']);
                foreach($row_columns as $column){
                    $tags[] = new Tag((int) $row['ticket_id'], $column);
                }
            }

            return array_unique($tags);
        }

        public static function addTag(PDO $db, int $ticket_id, string $tags){
            $query = $db->prepare('INSERT INTO ticket_hashtags (hashtag_id, ticket_id, hashtags) VALUES (NULL, ?, ?)');
            $query->execute(array($ticket_id, $tags));
        }

        public static function addNewTag(PDO $db, string $tag_name){
            $stmt = $db->prepare('INSERT INTO ticket_hashtags (hashtag_id, ticket_id, hashtags) VALUES (NULL, NULL, ?)');
            $stmt->execute(array($tag_name));
        }

        public static function deleteNewTag(PDO $db, string $tag){
            $stmt = $db->prepare('DELETE FROM ticket_hashtags WHERE hashtags = ?');
            $stmt->execute(array($tag));
        }

        public static function updateTicketTags(PDO $db, int $ticket_id, string $tags, $user_id, $updated_at){
            $query = $db->prepare('UPDATE ticket_hashtags SET hashtags = ? WHERE ticket_id = ?');
            $query->execute(array($tags, $ticket_id));

            $stmt = $db->prepare('SELECT * FROM tickets WHERE ticket_id = :id');
            $stmt->bindValue(':id', $ticket_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $oldStatus = $row['status_id'];
            $oldPriority = $row['priority_id'];
            $oldDepartment = $row['department_id'];
            $oldAssignedTo = $row['assigned_agent_id'];

            $stmt = $db->prepare('INSERT INTO ticket_history (ticket_id, user_id, time_of_change, old_status_id, new_status_id, old_assigned_agent_id, new_assigned_agent_id, old_priority_id, new_priority_id, old_department_id, new_department_id, content_change, tags_change) VALUES (:ticket_id, :user_id, :time_of_change, :old_status_id, :new_status_id, :old_assigned_agent_id, :new_assigned_agent_id, :old_priority_id, :new_priority_id, :old_department_id, :new_department_id, :content_change, :tags_change)');
            $stmt->bindValue(':ticket_id', $ticket_id);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':time_of_change', $updated_at->format('Y-m-d H:i:s'));
            $stmt->bindValue(':old_status_id', $oldStatus);
            $stmt->bindValue(':new_status_id', $oldStatus);
            $stmt->bindValue(':old_assigned_agent_id', $oldAssignedTo);
            $stmt->bindValue(':new_assigned_agent_id', $oldAssignedTo);
            $stmt->bindValue(':old_priority_id', $oldPriority);
            $stmt->bindValue(':new_priority_id', $oldPriority);
            $stmt->bindValue(':old_department_id', $oldDepartment);
            $stmt->bindValue(':new_department_id', $oldDepartment);
            $stmt->bindValue(':content_change', 0);
            $stmt->bindValue(':tags_change', 1);
            $stmt->execute();

            
        }
}