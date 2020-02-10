<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;

$app->get('/api/articles',
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

        $sql = "SELECT * FROM articles ORDER BY id DESC";
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
$app->get('/api/users/{userId}/articles',
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

        $userId = $args['userId'];
        $sql = "SELECT * FROM articles WHERE userId = $userId ORDER BY id DESC";
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
$app->get('/api/articles/category/{category}',
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

        $category = $args['category'];
        $sql = "SELECT * FROM articles WHERE tag = \"$category\" ORDER BY id DESC";
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
$app->get('/api/articles/search/{phrase}',
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

        $phrase = $args['phrase'];
        $sql = "SELECT * FROM articles WHERE title LIKE '%$phrase%' OR content LIKE '%$phrase%' ORDER BY id DESC";
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

$app->get('/api/articles/{id}',
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
$app->get('/api/users/{nick}',
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
        $nick = $args['nick'];
        $sql = "SELECT id, nick, name, surname, email, description FROM users WHERE nick = \"$nick\"";
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
$app->get('/api/users/short/{id}',
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
        $sql = "SELECT name, surname FROM users WHERE id = $id";
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
$app->post('/api/validateLogin',
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
        $nick = $requestData['nick'];
        $sql = "SELECT nick, password FROM users WHERE nick = \"$nick\"";
        $result = $conn->query($sql);
        $array = [];

        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();
            // while($row = $result->fetch_assoc()) {
            //     $array[] = $row;
            // }
            $hash = $row['password'];
            $finish = password_verify($requestData['password'], $hash);

        } else {
            echo "0 results";
        }
        $conn->close();
        if($finish){
          return "true";
        }
        else {
          return "false";
        }
        //return $response->withJson($array);
    }
);
$app->get('/api/articles/{id}/comments',
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
$app->get('/api/users/{id}/likes',
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
        $sql = "SELECT * FROM likes WHERE userId = $id";
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

$app->post('/api/comments/add',
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

      $sql = "INSERT INTO comments (articleId, content, userId, userName, userSurname) VALUES('$requestData[articleId]', '$requestData[content]', '$requestData[userId]', '$requestData[userName]', '$requestData[userSurname]')";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->post('/api/likes/add',
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

      $sql = "INSERT INTO likes (type, objId, userId) VALUES('$requestData[type]', '$requestData[objId]', '$requestData[userId]')";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->post('/api/articles/add',
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

      $sql = "INSERT INTO articles (title, tag, content, userId) VALUES('$requestData[title]', '$requestData[category]', '$requestData[content]', '$requestData[userId]')";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->post('/api/users/add',
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


      $encryptedPass = password_hash($requestData['password'], PASSWORD_DEFAULT);
      echo $encryptedPass;

      $sql = "INSERT INTO users (nick, name, surname, email, description, password)
              VALUES('$requestData[nick]', '$requestData[name]', '$requestData[surname]', '$requestData[email]', '$requestData[description]', '$encryptedPass')";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->put('/api/articles/{id}/commentsNumber',
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
$app->put('/api/articles/{id}/likes',
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
$app->put('/api/comments/{id}/likes',
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
      $sql = "UPDATE comments SET likes=likes+1 WHERE id=$id";

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
