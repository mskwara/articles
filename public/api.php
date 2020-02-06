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
$app->get(
    '/api/articles/{id}/comments',
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
        $sql = "SELECT * FROM comments WHERE articleId = $id";
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

$app->post(
    '/api/comments/add',
     function (Request $request, Response $response, array $args) {

      $servername = "serwer2001916.home.pl";
      $username = "32213694_scoreboard";
      $password = "Fell!Dell!=";
      $dbname = "32213694_scoreboard";

      $requestData = $request->getParsedBody();
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $sql = "INSERT INTO comments (articleId, content, userId) VALUES('$requestData[articleId]', '$requestData[content]', '$requestData[userId]')";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->put(
    '/api/articles/{id}/commentsNumber',
     function (Request $request, Response $response, array $args) {

      $servername = "serwer2001916.home.pl";
      $username = "32213694_scoreboard";
      $password = "Fell!Dell!=";
      $dbname = "32213694_scoreboard";

      $requestData = $request->getParsedBody();
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      $id = $args['id'];
      $sql = "UPDATE articles SET commentsNumber=commentsNumber+1 WHERE id=$id";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->put(
    '/api/articles/{id}/likes',
     function (Request $request, Response $response, array $args) {

      $servername = "serwer2001916.home.pl";
      $username = "32213694_scoreboard";
      $password = "Fell!Dell!=";
      $dbname = "32213694_scoreboard";

      $requestData = $request->getParsedBody();
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      $id = $args['id'];
      $sql = "UPDATE articles SET likes=likes+1 WHERE id=$id";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);

$app->run();
