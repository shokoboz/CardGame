<?php
    require_once __DIR__ . '/.env.php';
    require_once __DIR__ . '/_.includes.php';
    require_once __DIR__ . '/classes/Database.php';
    require_once __DIR__ . '/classes/User.php';


    $connection = new Database($GLOBALS["mongo_user"], $GLOBALS["mongo_pass"]);
    $cardgame = $connection->getDatabase($GLOBALS["mongo_db"]);

    $findUser = $cardgame->users->find(
        [],
        [
            "projection" => [
                "_id" => 0
            ],
        ]
    );
    $results = [];
    foreach($findUser as $user){
        $results[] = $user;
    }
    echo "<pre>".var_export($results, true)."</pre>";
?>