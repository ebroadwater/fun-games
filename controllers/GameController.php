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
        // $stmt = $this->db->query('SELECT * FROM users WHERE 1'); 
        // $users = $stmt->fetchAll(); 

        $command = "home"; 
        if (isset($this->input["command"])){
            $command = $this->input["command"]; 
        }
        switch($command) {
            case "connections":
                $this->playConnections(); 
                break;
            case "spotify":
                $this->spotify(); 
                break;
            case "leaderboard":
                $this->showLeaderboard(); 
                break; 
            default:
                $this->showHome(); 
                break;
        }
    }
    public function showHome(){
        include 'views/homepage.php';
    }
    public function playConnections(){
        include 'views/connections.php';
    }
    public function spotify(){
        include 'views/spotify.php';
    }
    public function showLeaderboard(){
        include 'views/leaderboard.php';
    }
}