<?php
    require_once __DIR__.'/../../vendor/autoload.php';
    class Database {
        private $connection;

        public function __construct($username = null, $password = null, $host = "mongo", $port = "27017"){
            try {
                $this->connection = new MongoDB\Client("mongodb://{$host}:{$port}", [
                    "username" => $username,
                    "password" => $password
                ]);
            } catch (Exception $e) {
                return null;
            }
        }

        public function getDatabase($db_name){
            $db = $this->connection->{$db_name};
            if($db){
                return $db;
            }
            return null;
        }
    }
?>