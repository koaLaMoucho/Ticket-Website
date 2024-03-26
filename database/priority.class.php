<?php
    declare(strict_types=1);

    class Priority{
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

        public static function getPriorities(PDO $db) : array {
            $query = $db->query('SELECT * FROM priorities');
            $query->execute();

            $priorities = array();
            while($priority = $query->fetch(PDO::FETCH_ASSOC)){
                $priorities[] = new Priority((int) $priority['priority_id'], $priority['priority_name']);
            }
            return $priorities;
        }

        public static function getPriorityIDbyName($db, $name) {
            $stmt = $db->prepare('SELECT priority_id FROM priorities WHERE priority_name = :name');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['priority_id'];
        }

        public static function addPriority(PDO $db, string $priority_name){
            $stmt = $db->prepare('INSERT INTO priorities (priority_id, priority_name) VALUES (NULL, ?)');
            $stmt->execute(array($priority_name));
        }

        public static function deletePriority(PDO $db, int $prior_id){
            $stmt = $db->prepare('DELETE FROM priorities WHERE priority_id = ?');
            $stmt->execute(array($prior_id));
        }
    }
?>