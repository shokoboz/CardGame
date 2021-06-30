<?php
class Boo
{
    public static function Route($name = null)
    {
        if ($name) {
            return $name == $_SERVER['QUERY_STRING'];
        } else {
            $route = $_SERVER['QUERY_STRING'];
            return $route;
        }
    }

    public static function Payload($type = "JSON"){
        if($type == "POST"){
            return $_POST;
        }
        header('Content-type: application/json; charset=utf-8');
        $payload = json_decode(file_get_contents('php://input'), false, 512, JSON_BIGINT_AS_STRING);
        return $payload;
    }

    public static function response($data, $state = true, $custom = true)
    {
        header('Content-Type: application/json');
        echo json_encode([
            "status"  => $state,
            "message" => $data,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
    public static function rawResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}