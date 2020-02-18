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
$app->get('/api/articles/{articleId}/rating/user/{userId}',
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
        $articleId = $args['articleId'];
        $userId = $args['userId'];
        $sql = "SELECT * FROM rating WHERE articleId = $articleId AND userId = $userId";
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
$app->get('/api/articles/{articleId}/averageRating',
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
        $articleId = $args['articleId'];
        $sql = "SELECT rating FROM rating WHERE articleId = $articleId";
        $result = $conn->query($sql);
        $count = 0;
        $avg = 0;

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $avg = $avg + $row['rating'];
                $count = $count + 1;
            }
        }
        if($count > 0){
          $wynik = [
             ['avg' => $avg, 'count' => $count],
          ];
        }
        else {
          $wynik = null;
        }

        $conn->close();
        return $response->withJson($wynik);
    }
);
$app->get('/api/users/{userId}/articles/statistics',
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
        $sql = "SELECT id FROM articles WHERE userId = $userId";    //biore wszystkie id artykułów tego usera
        $result = $conn->query($sql);
        $articlesIds = [];

        $numberOfArticles = 0;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $articlesIds[] = $row['id'];    //wpisuję id artykułów do tablicy
                $numberOfArticles = $numberOfArticles + 1;
            }
        }

        $r1 = 0;
        $r2 = 0;
        $r3 = 0;
        $r4 = 0;
        $r5 = 0;

        for($i = 0 ; $i < $numberOfArticles ; $i = $i+1){
          $currArtId = $articlesIds[$i];
          $sql1 = "SELECT rating FROM rating WHERE articleId = $currArtId";    //biore wszystkie oceny tego artykulu
          $result1 = $conn->query($sql1);

          if ($result1->num_rows > 0) {
              while($row = $result1->fetch_assoc()) {
                  if($row['rating'] == 1)   $r1 = $r1 + 1;
                  else if($row['rating'] == 2)   $r2 = $r2 + 1;
                  else if($row['rating'] == 3)   $r3 = $r3 + 1;
                  else if($row['rating'] == 4)   $r4 = $r4 + 1;
                  else if($row['rating'] == 5)   $r5 = $r5 + 1;
              }
          }
        }
        $rate1 = ['id' => 1, 'count' => $r1];   //ile jest poszczególnych ocen
        $rate2 = ['id' => 2, 'count' => $r2];
        $rate3 = ['id' => 3, 'count' => $r3];
        $rate4 = ['id' => 4, 'count' => $r4];
        $rate5 = ['id' => 5, 'count' => $r5];
        $rates = [];
        $rates[] = $rate5;
        $rates[] = $rate4;
        $rates[] = $rate3;
        $rates[] = $rate2;
        $rates[] = $rate1;


        $sql2 = "SELECT id FROM articles";    //ile jest wszystkich artykułów
        $result2 = $conn->query($sql2);
        $allArticles = 0;
        if ($result2->num_rows > 0) {
            while($row = $result2->fetch_assoc()) {
                $allArticles = $allArticles + 1;
            }
        }
        $articles = ['yourArticles' => $numberOfArticles, 'allArticles' => $allArticles];

        $sql3 = "SELECT id FROM comments WHERE userId = $userId";    //ile jest twoich komentarzy
        $result3 = $conn->query($sql3);
        $yourComments = 0;
        if ($result3->num_rows > 0) {
            while($row = $result3->fetch_assoc()) {
                $yourComments = $yourComments + 1;
            }
        }

        $sql4 = "SELECT id FROM comments";    //ile jest wszystkich komentarzy
        $result4 = $conn->query($sql4);
        $allComments = 0;
        if ($result4->num_rows > 0) {
            while($row = $result4->fetch_assoc()) {
                $allComments = $allComments + 1;
            }
        }
        $comments = ['yourComments' => $yourComments, 'allComments' => $allComments];

        $sql5 = "SELECT id FROM rating WHERE userId = $userId";    //ile jest twoich opinii
        $result5 = $conn->query($sql5);
        $yourRatings = 0;
        if ($result5->num_rows > 0) {
            while($row = $result5->fetch_assoc()) {
                $yourRatings = $yourRatings + 1;
            }
        }

        $array = ['rating' => $rates, 'articles' => $articles, 'comments' => $comments, 'yourRatings' => $yourRatings];

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
        $article = [];

        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();
            $article = $row;

        }
        $userId = $article['userId'];
        $sql1 = "SELECT name, surname FROM users WHERE id = $userId";
        $result1 = $conn->query($sql1);
        $userInfo = [];

        if ($result1->num_rows > 0) {
            // output data of each row
            $row = $result1->fetch_assoc();
            $userInfo = $row;
        }
        $array = ['article' => $article, 'userInfo' => $userInfo];

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
        $sql = "SELECT id, nick, name, surname, email, description, avatar FROM users WHERE nick = \"$nick\"";
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
$app->get('/api/users/info/{userId}',
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
        $sql = "SELECT name, surname, description, avatar FROM users WHERE id = \"$userId\"";
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
$app->get('/api/users/{id}/avatar',
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
        $sql = "SELECT avatar FROM users WHERE id = $id";
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
$app->post('/api/users/avatars-needed',
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
        $requestData = $request->getParsedBody();
        $userIds = $requestData['userIds'];
        $sql = 'SELECT id, avatar FROM users WHERE id IN (' . implode(',', array_map('intval', $userIds)) . ')';
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
$app->post('/api/validateUniqueNick',
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
        $sql = "SELECT nick FROM users";
        $result = $conn->query($sql);
        $array = [];

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                if(strtolower($row['nick']) == strtolower($nick)) {
                  return "false";
                }
            }

        }
        $conn->close();

        return "true";
    }
);
$app->post('/api/validateIfUserExists',
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
        $id = $requestData['id'];
        $sql = "SELECT id FROM users";
        $result = $conn->query($sql);
        $array = [];

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                if($row['id'] == $id) {
                  return "true";
                }
            }

        }
        $conn->close();

        return "false";
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
        $sql = "SELECT * FROM comments WHERE articleId = $id ORDER BY id DESC";
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

);$app->post('/api/comments/delete',
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
      $id = $requestData['id'];
      $sql = "DELETE FROM comments WHERE id = $id";

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
$app->post('/api/rating/add',
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

      $sql = "INSERT INTO rating (articleId, userId, rating) VALUES('$requestData[articleId]', '$requestData[userId]', '$requestData[rating]')";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->put('/api/rating/update',
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

      $sql = "UPDATE rating SET rating = '$requestData[rating]' WHERE id = '$requestData[id]'";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->post('/api/likes/delete',
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
      $type = $requestData['type'];
      $objId = $requestData['objId'];
      $userId = $requestData['userId'];

      $sql = "DELETE FROM likes WHERE type = \"$type\" AND objId = $objId AND userId = $userId";

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

      $sql = "INSERT INTO articles (title, tag, style, content, bibliography, userId) VALUES('$requestData[title]', '$requestData[category]', '$requestData[style]', '$requestData[content]', '$requestData[bibliography]', '$requestData[userId]')";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->put('/api/article/edit',
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
      $title = $requestData['title'];
      $tag = $requestData['tag'];
      $style = $requestData['style'];
      $content = $requestData['content'];
      $bibliography = $requestData['bibliography'];
      $id = $requestData['id'];

      $sql = "UPDATE articles SET title = \"$title\", tag = \"$tag\",
       style=\"$style\", content=\"$content\", bibliography=\"$bibliography\" WHERE id = $id";

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
      $name = ucwords($requestData['name']);
      $surname = ucwords($requestData['surname']);

      $sql = "INSERT INTO users (nick, name, surname, email, description, password, avatar)
              VALUES('$requestData[nick]', \"$name\", \"$surname\",
                 '$requestData[email]', '$requestData[description]', '$encryptedPass', '$requestData[avatar]')";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->put('/api/users/update',
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

      $email = $requestData['email'];
      $description = $requestData['description'];
      $nick = $requestData['nick'];
      $avatar = $requestData['avatar'];
      $encryptedPass = password_hash($requestData['newPassword'], PASSWORD_DEFAULT);
      if($requestData['newPassword'] != ""){
        $sql = "UPDATE users SET email = \"$email\", description = \"$description\",
         avatar = \"$avatar\", password = \"$encryptedPass\" WHERE nick = \"$nick\"";
      }
      else {
        $sql = "UPDATE users SET email = \"$email\", description = \"$description\", avatar = \"$avatar\" WHERE nick = \"$nick\"";
      }


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
$app->put('/api/articles/{id}/rating',
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
      $sql = "UPDATE articles SET rating='$requestData[rating]' WHERE id=$id";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->put('/api/comments/{id}/incrementLikes',
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
$app->put('/api/comments/{id}/decrementLikes',
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
      $sql = "UPDATE comments SET likes=likes-1 WHERE id=$id";

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
