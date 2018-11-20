<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

class MyDB extends SQLite3 {
    function __construct() {
       $this->open('friends.db');
    }
 }

 $db = new MyDB();
 if(!$db) {
    echo $db->lastErrorMsg();
    exit();
 } 
 
$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->get(
    '/friends/{id}',
    function (Request $request, Response $response, array $args) use ($db) {
		$id=$args['id'];
        $sql = "select * from friend where id=$id";
        $ret = $db->query($sql);
        $friends = [];
        while ($friend = $ret->fetchArray(SQLITE3_ASSOC)) {
            $friends[] = $friend;
            if($friend)
            {
        return $response->withJson($friends);
			}
			else
			{
				return $response->withStatus(404)->withJson();
			}
	}
    }
);

$app->run();
