<?php
    require_once __DIR__ . '/../firebase/php-jwt/JWT.php';

    use Firebase\JWT\JWT;

    class User {
        private static $key = "hX3b9HyfhhDcuTyuBokW/q8JAzwUlrxxGwEdHev0e";
        private static $serverName = "local.cardgame.com";
        public static function getUser($name = null){
            
        }

        public static function generateToken($name){
            $key = self::$key;
            $serverName = self::$serverName;

            $issuedAt = new DateTimeImmutable();
            # TOKEN 24 Hour
            $expire     = $issuedAt->modify('+3600 minutes')->getTimestamp();
            $payload = array(
                "iss" => $serverName,
                "aud" => $serverName,
                "iat" => $issuedAt->getTimestamp(),
                "nbf" => $issuedAt->getTimestamp(),
                "exp" => $expire,
                "name" => $name
            );
            $jwt = JWT::encode($payload, $key);
            return $jwt;
        }

        public static function veriftyToken()
        {
            try {
                if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                    header('HTTP/1.0 400 Bad Request');
                    echo 'Token not found in request';
                    exit;
                }
                $jwt = $matches[1];
                if (!$jwt) {
                    # NO TOKEN
                    header('HTTP/1.0 400 Bad Request');
                    exit;
                }
                $key = self::$key;
                $serverName = self::$serverName;
                $now = new DateTimeImmutable();

                $token = JWT::decode($jwt, $key, array('HS256'));
                if (
                    $token->iss !== $serverName ||
                    $token->nbf > $now->getTimestamp() ||
                    $token->exp < $now->getTimestamp()
                ) {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
            return $token;
        }

        public static function saveHistory($db, $log_data){
            $collection_history = $db->log_history;
            $create_user = $collection_history->insertOne($log_data);
        }
}
