<?php
    class Session{
        private array $messages;

        public function __construct(){
            session_start();
            session_set_cookie_params(0, '/', 'localhost', true, true);

            if(!isset($_SESSION['csrf'])){
                $_SESSION['csrf'] = $this->generate_random_token();
            }

            $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
            // unset($_SESSION['message']);
        }

        private function generate_random_token() {
            return bin2hex(openssl_random_pseudo_bytes(32));
        }

        
        public function isLoggedIn() : bool{
            return isset($_SESSION['id']);
        }

        public function logout() : void{
            session_destroy();
        }

        public function getId() : ?int{
            return isset($_SESSION['id']) ? $_SESSION['id'] : null;
        }

        public function getName() : ?string{
            return isset($_SESSION['name']) ? $_SESSION['name'] : null;
        }

        public function setId(int $id) : void{
            $_SESSION['id'] = $id;
        }

        public function setName(string $name) : void{
            $_SESSION['name'] = $name;
        }

        public function addMessage(string $type, string $text)
        {
            $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
        }

        public function getMessages()
        {
            unset($_SESSION['messages']);
            return $this->messages;
        }
    }

?>