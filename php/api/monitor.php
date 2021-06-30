<?php
    require_once __DIR__ . '/_.includes.php';
    require_once __DIR__ . '/classes/Database.php';
    require_once __DIR__ . '/classes/User.php';


    $connection = new Database('root', '1234');
    $cardgame = $connection->getDatabase('cardgame');

    // $findUser = $cardgame->log_history->find();
    // $results = [];
    // foreach($findUser as $user){
    //     $results[] = $user;
    // }
    // echo "<pre>".var_export($results, true)."</pre>";
?>