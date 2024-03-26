<?php
    declare(strict_types=1);

    class Status{
        private $id;
        private $name;

        public function __construct(int $id, string $name){
            $this->id = $id;
            $this->name = $name;
        }

        public function getId() : int {
            return $this->id;
        }

        public function getName() : string {
            return $this->name;
        }

        public static function getStatuses(PDO $db) : array {
            $query = $db->query('SELECT * FROM statuses');
            $query->execute();

            $statuses = array();
            while($status = $query->fetch(PDO::FETCH_ASSOC)){
                $statuses[] = new Status((int) $status['status_id'], $status['status_name']);
            }
            return $statuses;
        }

        public static function getStatusIDbyName($db, $name) {
            $stmt = $db->prepare('SELECT status_id FROM statuses WHERE status_name = :name');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['status_id'];
        }

        public static function addStatus(PDO $db, string $status_name){
            $stmt = $db->prepare('INSERT INTO statuses (status_id, status_name) VALUES (NULL, ?)');
            $stmt->execute(array($status_name));
        }

        public static function deleteStatus(PDO $db, int $status_id){
            $stmt = $db->prepare('DELETE FROM statuses WHERE status_id = ?');
            $stmt->execute(array($status_id));
        }
        
    }
?>