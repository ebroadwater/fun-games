<?php 

class GameController {
    private $input = []; 
    private $db; 

    public function __construct($input, $db){
        session_start(); 
        $this->db = $db->getConnection(); 
        $this->input = $input; 
    }
    public function run() {
        $stmt = $this->db->query('SELECT * FROM users WHERE 1'); 
        $users = $stmt->fetchAll(); 

        include 'views/homepage.php';
    }
}