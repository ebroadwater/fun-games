<?php 
// require_once 'config/connect.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);

spl_autoload_register(function ($class) {
    // include "/$class.php";
    $paths = ['controllers', 'models', 'core', 'config', 'views', 'public', 'data']; 
    foreach ($paths as $dir) {
        $file = __DIR__ . "/$dir/$class.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    die("Autoload error: Class $class not found.");
});

$db = new Database(); 
$game = new GameController($_GET, $db); 
$game->run(); 
?>