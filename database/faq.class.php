<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../database/users.class.php');
  

    class FAQ {
        public int $faq_id;
        public string $question;
        public string $answer;
    
        public function __construct(int $faq_id, string $question, string $answer){
            $this->faq_id = $faq_id;
            $this->question = $question;
            $this->answer = $answer;
        }
    
        static function getFAQs(PDO $db) : array {
            $query = $db->query('SELECT * FROM faqs');
            $faqs = [];
            while($faq = $query->fetch()){
                $faqs[] = FAQ::createFAQ($faq);
            }

            return $faqs;
        }
    
        static private function createFAQ(array $faq){    
            return new FAQ(
                (int) $faq['faq_id'],
                $faq['question'],
                $faq['answer']
            );
        }

        static public function removeFAQ(PDO $db, int $faq_id) {
            $stmt = $db->prepare('DELETE FROM faqs WHERE faq_id = :faq_id');
            $stmt->execute([':faq_id' => $faq_id]);
        }
    

        static public function addFAQ(PDO $db, string $question, string $answer) {
            $stmt = $db->prepare('INSERT INTO faqs (question, answer) VALUES (:question, :answer)');
            $stmt->bindValue(':question', $question);
            $stmt->bindValue(':answer', $answer);
            $stmt->execute();
        }

        public static function searchFAQs(PDO $db, string $search): array {
            
            $query = $db->prepare('SELECT answer FROM faqs WHERE answer LIKE ?');
            $query->execute(array("%$search%"));
            
            $faqs = [];
            while ($faq = $query->fetch(PDO::FETCH_ASSOC)) {
                $faqs[] = $faq['answer'];
            }
            
            return $faqs;}

        
        
}


?>    