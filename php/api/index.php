<?php
    header('Access-Control-Allow-Origin: localhost');
    require_once __DIR__ . '/.env.php';
    require_once __DIR__ . '/_.includes.php';
    require_once __DIR__ . '/classes/Database.php';
    require_once __DIR__ . '/classes/User.php';


    $connection = new Database($GLOBALS["mongo_user"], $GLOBALS["mongo_pass"]);
    $cardgame = $connection->getDatabase($GLOBALS["mongo_db"]);

  
    if (Boo::Route('join')){
        $payload = Boo::Payload();
        if(isset($payload->name)){
            $name = $payload->name;
            $users = $cardgame->users;
            if($users){
                $find = $users->findOne([
                    "name" => $name
                ]);
                if(!$find){
                    $new_data = [
                        'name' => $name,
                        'best_score' => 0,
                        'last_login' => date('Y-m-d H:i:s')
                    ];
                    $create_user = $users->insertOne($new_data);
                    if($create_user->getInsertedId()){
                        Boo::rawResponse([
                            "token" => User::generateToken($name)
                        ]);
                    }
                } else {
                    $update_user = $users->findOneAndUpdate(
                        [ 'name' => $name ],
                        [ '$set' => [ 
                                'last_login' => date('Y-m-d H:i:s') 
                            ]
                        ],
                        [
                            'returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER,                    
                        ]
                    );
                    if($update_user){
                        Boo::rawResponse([
                            "token" => User::generateToken($name)
                        ]);
                    }
                }
            }
        } else {
            
        }
    } else if (Boo::Route('global-data')){
        if($cardgame){
            $users = $cardgame->users;
            if($users){
                $global_data = $users->findOne(
                    [
                        "best_score" => ['$gt' => 0]
                    ],
                    [
                        "projection" => [
                            "_id" => 0
                        ],
                        "sort" => [
                            "best_score" => +1,
                            "last_login" => -1
                        ],
                        "limit" => 1
                    ],
                );
                Boo::rawResponse([
                    "global_score" => $global_data["best_score"]
                ]);
            } else {
                Boo::rawResponse([]);
            }
        }
    } else if(Boo::Route('high-score')) {
        if($cardgame){
            $users = $cardgame->users;
            if($users){
                $global_data = $users->findOne(
                    [
                        "best_score" => ['$gt' => 0]
                    ],
                    [
                        "projection" => [
                            "_id" => 0
                        ],
                        "sort" => [
                            "best_score" => +1,
                            "last_login" => -1
                        ],
                        "limit" => 1
                    ],
                );
                Boo::rawResponse([
                    "global_score" => $global_data["best_score"]
                ]);
            } else {
                Boo::response([]);
            }
        }
    } else {
        # ROUTE WITH USER
        if ($token = User::veriftyToken()) {
            $name = $token->name;
            $users = $cardgame->users;
            $payload = Boo::Payload();
            if (Boo::Route('user')) {
                $query = $users->findOne(
                    ["name" => $name]
                );
                if($query){
                    Boo::rawResponse([
                        "user" => [
                            "name"  => $name,
                            "best_score" => $query['best_score']
                        ]
                    ]);
                } else {
                    Boo::rawResponse(false);
                }
            } else if(Boo::Route('save-game')){
                $score = $payload->score;
                $save_time = date('Y-m-d H:i:s');
                # UPDATE BASE SCORE
                $updateResult = $users->updateOne(
                    [ 
                        'name' => $name,
                        '$or' => [
                            ['best_score' => 0],
                            ['best_score' => null],
                            ['best_score' => ['$gt' => $score]]
                        ]
                    ],
                    [ '$set' => [
                        "best_score" => $score,
                        'updated_time' => date('Y-m-d H:i:s') 
                    ]]
                );
                User::saveHistory($cardgame, [
                    'name'       => $name,
                    'score'      => $score,
                    'created_time' => date('Y-m-d H:i:s') 
                ]);
                if($updateResult->getMatchedCount()){
                    Boo::rawResponse([
                        "status" => true
                    ]);
                } else {
                    Boo::rawResponse([
                        "status" => false
                    ]);
                }
            }
        } else {
            Boo::rawResponse(false);
        }
    }