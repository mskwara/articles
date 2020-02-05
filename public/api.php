<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;

$app->get(
    '/api/articles',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "Fell!Dell!=";
        $dbname = "32213694_scoreboard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM articles";
        $result = $conn->query($sql);
        $array = [];

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $array[] = $row;
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        return $response->withJson($array);
    }
);

$app->get(
    '/api/articles/{id}',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "Fell!Dell!=";
        $dbname = "32213694_scoreboard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $id = $args['id'];
        $sql = "SELECT * FROM articles WHERE id = $id";
        $result = $conn->query($sql);
        $array = [];

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $array[] = $row;
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        return $response->withJson($array);
    }
);


$app->run();
