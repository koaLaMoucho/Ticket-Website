<?php 
    declare(strict_types=1);

    class Department {
        public $id;
        public $name;

        public function __construct(int $id, string $name){
            $this->id = $id;
            $this->name = $name;
        }

        public function getID() : int {
            return $this->id;
        }

        public function getName() : string {
            return $this->name;
        }

        public static function getDepartments(PDO $db) : array {
            $stmt = $db->prepare('SELECT * FROM departments');
            $stmt->execute();
            
            $departments_array = array();
            while($department = $stmt->fetch(PDO::FETCH_ASSOC)){
                $departments_array[] = new Department((int) $department['department_id'], $department['department_name']);
            }
            return $departments_array;
        }

        public static function searchDepartment(PDO $db, string $search) : array {
            $stmt = $db->prepare('SELECT * FROM departments WHERE department_name LIKE ?');
            $stmt->execute(array("%$search%"));
            
            $departments_array = array();
            while($department = $stmt->fetch(PDO::FETCH_ASSOC)){
                $departments_array[] = new Department((int) $department['department_id'], $department['department_name']);
            }
            return $departments_array;
        }

        public static function getDepartmentID(PDO $db, string $department_name) : int {
            $stmt = $db->prepare('SELECT * FROM departments WHERE department_name = ?');
            $stmt->execute(array($department_name));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $row['department_id'];
        }
        
        public static function getOtherDepartments(PDO $db, string $department) : array {
            $stmt = $db->prepare('SELECT * FROM departments WHERE department_name NOT LIKE ?');
            $stmt->execute(array($department));
            
            $departments_array = array();
            while($department = $stmt->fetch(PDO::FETCH_ASSOC)){
                $departments_array[] = new Department((int) $department['department_id'], $department['department_name']);
            }
            return $departments_array;
        }

        public static function addDepartment(PDO $db, string $department_name){
            $stmt = $db->prepare('INSERT INTO departments (department_id, department_name) VALUES (NULL, ?)');
            $stmt->execute(array($department_name));
        }

        public static function deleteDepartment(PDO $db, int $dept_id){
            $stmt = $db->prepare('DELETE FROM departments WHERE department_id = ?');
            $stmt->execute(array($dept_id));
        }

    }


?>