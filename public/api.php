<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;

$app->get('/api/articles/count',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
        $dbname = "32213694_scoreboard";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id FROM articles";
        $result = $conn->query($sql);
        $count = 0;

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $count++;
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        return $response->withJson($count);
    }
);
$app->post('/api/articles/maxPage',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
        $dbname = "32213694_scoreboard";

        $requestData = $request->getParsedBody();
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $userArticles = $requestData['userArticles'];
        $userId = $requestData['userId'];
        if($userArticles == false){
            $category = $requestData['category'];
            $styles = $requestData['styles'];
            $onlyMyArticles = $requestData['onlyMyArticles'];
            $s0 = "";
            $s1 = "";
            $s2 = "";
            if($styles[0]['value'] == true) $s0 = $styles[0]['key'];
            if($styles[1]['value'] == true) $s1 = $styles[1]['key'];
            if($styles[2]['value'] == true) $s2 = $styles[2]['key'];

            
            if($category != ""){
                if($onlyMyArticles == false){
                    $sql = "SELECT id FROM articles WHERE tag = \"$category\"
                    AND (style = \"$s0\" OR style = \"$s1\" OR style = \"$s2\")";
                }
                else {
                    $sql = "SELECT id FROM articles WHERE userId = $userId AND tag = \"$category\"
                    AND (style = \"$s0\" OR style = \"$s1\" OR style = \"$s2\")";
                }
            }
            else {
                if($onlyMyArticles == false){
                    $sql = "SELECT id FROM articles WHERE
                    style = \"$s0\" OR style = \"$s1\" OR style = \"$s2\"";
                }
                else {
                    $sql = "SELECT id FROM articles WHERE userId = $userId AND
                    (style = \"$s0\" OR style = \"$s1\" OR style = \"$s2\")";
                }
            }
        }
        else {
            $sql = "SELECT id FROM articles WHERE userId = $userId";
        }
        
        $result = $conn->query($sql);
        $count = 0;

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $count++;
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        $limit = 6;
        if($count % $limit == 0){
            $maxPage = intdiv($count, $limit) - 1;
        }
        else {
            $maxPage = intdiv($count, $limit);
        }
        return $response->withJson($maxPage);
    }
);
$app->post('/api/articles',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
        $dbname = "32213694_scoreboard";

        $requestData = $request->getParsedBody();
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $limit = 6;
        $offset = $limit*$requestData['page'];
        $userArticles = $requestData['userArticles'];
        $userId = $requestData['userId'];
        if($userArticles == false){
            $category = $requestData['category'];
            $styles = $requestData['styles'];
            $onlyMyArticles = $requestData['onlyMyArticles'];
            
            $s0 = "";
            $s1 = "";
            $s2 = "";
            if($styles[0]['value'] == true) $s0 = $styles[0]['key'];
            if($styles[1]['value'] == true) $s1 = $styles[1]['key'];
            if($styles[2]['value'] == true) $s2 = $styles[2]['key'];

            
            if($category != ""){
                if($onlyMyArticles == false){
                    $sql = "SELECT * FROM articles WHERE tag = \"$category\"
                    AND (style = \"$s0\" OR style = \"$s1\" OR style = \"$s2\")
                    ORDER BY id DESC LIMIT $limit OFFSET $offset";
                }
                else {
                    $sql = "SELECT * FROM articles WHERE userId = $userId AND tag = \"$category\"
                    AND (style = \"$s0\" OR style = \"$s1\" OR style = \"$s2\")
                    ORDER BY id DESC LIMIT $limit OFFSET $offset";
                }
            }
            else {
                if($onlyMyArticles == false){
                    $sql = "SELECT * FROM articles WHERE
                    style = \"$s0\" OR style = \"$s1\" OR style = \"$s2\"
                    ORDER BY id DESC LIMIT $limit OFFSET $offset";
                }
                else {
                    $sql = "SELECT * FROM articles WHERE userId = $userId AND
                    (style = \"$s0\" OR style = \"$s1\" OR style = \"$s2\")
                    ORDER BY id DESC LIMIT $limit OFFSET $offset";
                }
            }
        }
        else {
            $sql = "SELECT * FROM articles WHERE userId = $userId ORDER BY id DESC LIMIT $limit OFFSET $offset";
        }
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
$app->post('/api/article/delete',
    function (Request $request, Response $response, array $args) {

    $servername = "serwer2001916.home.pl";
    $username = "32213694_scoreboard";
    $password = "passwordscoreboard";
    $dbname = "32213694_scoreboard";

    $requestData = $request->getParsedBody();
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $id = $requestData['id'];

    $sql = "DELETE FROM articles WHERE id = $id";

    if ($conn->query($sql) === TRUE) {        //usuwanie artykułu
        echo "Article deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $sql1 = "DELETE FROM rating WHERE articleId = $id";

    if ($conn->query($sql1) === TRUE) {       //usuwanie ocen
        echo "Rating deleted successfully";
    } else {
        echo "Error: " . $sql1 . "<br>" . $conn->error;
    }
    
    $sql2 = "SELECT id FROM comments WHERE articleId = $id";

    $commentsIds = [];
    $result = $conn->query($sql2);        //wzięcie id komentarzy
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $commentsIds[] = $row['id'];
        }
    }
    if(count($commentsIds) > 0){
        $sql3 = 'DELETE FROM likes WHERE objId IN (' . implode(',', array_map('intval', $commentsIds)) . ')';

        if ($conn->query($sql3) === TRUE) {       //usuwanie lików
            echo "Likes deleted successfully";
        } else {
            echo "Error: " . $sql3 . "<br>" . $conn->error;
        }
    }
    
    $sql4 = "DELETE FROM comments WHERE articleId = $id";

    if ($conn->query($sql4) === TRUE) {       //usuwanie lików
        echo "Comments deleted successfully";
    } else {
        echo "Error: " . $sql4 . "<br>" . $conn->error;
    }

    $conn->close();
    return $requestData;
  }

);
$app->get('/api/articles/{articleId}/rating/user/{userId}',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
$app->get('/api/users',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
        $dbname = "32213694_scoreboard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, nick, name, surname, email FROM users";
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
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
$app->post('/api/users/user-short-data-needed',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
        $dbname = "32213694_scoreboard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $requestData = $request->getParsedBody();
        $userIds = $requestData['userIds'];
        $sql = 'SELECT id, name, surname FROM users WHERE id IN (' . implode(',', array_map('intval', $userIds)) . ')';
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
        $password = "passwordscoreboard";
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

        $finish = false;
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
        
        if($finish){
            $date = date('Y-m-d H:i:s');
            $sql2 = "UPDATE users SET lastLogin = \"$date\" WHERE nick = \"$nick\"";
            $conn->query($sql2);

            $conn->close();
            $wynik = "true";
            return $wynik;
        }
        else {
            $conn->close();
            $wynik = "false";
            return $wynik;
        }
        //return $response->withJson($array);
    }
);
$app->post('/api/mail',
    function (Request $request, Response $response, array $args) {
        // The message
        $message = "Line 1\r\nLine 2\r\nLine 3";

        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70, "\r\n");

        // Send
        $success = mail('michalon2000@gmail.com', 'My Subject', $message);
        if (!$success) {
            $errorMessage = error_get_last()['message'];
            echo $errorMessage;
        }
        else echo "true";
    }
);
$app->post('/api/validateUniqueNick',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
        $password = "passwordscoreboard";
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
$app->get('/api/articles/{id}/comments/number',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
        $dbname = "32213694_scoreboard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $id = $args['id'];
        $sql = "SELECT count(id) as total FROM comments WHERE articleId = $id";
        $result = $conn->query($sql);
        $array = [];

        while($row = $result->fetch_assoc()) {
            $array[] = $row;
        }

        $conn->close();
        return $response->withJson($array);
    }
);
$app->get('/api/users/{id}/likes',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
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
      $password = "passwordscoreboard";
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
$app->post('/api/comments/delete',
     function (Request $request, Response $response, array $args) {

      $servername = "serwer2001916.home.pl";
      $username = "32213694_scoreboard";
      $password = "passwordscoreboard";
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
          echo "Comment deleted successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
      
      $sql1 = "DELETE FROM likes WHERE objId = $id";

      if ($conn->query($sql1) === TRUE) {
          echo "Likes deleted successfully";
      } else {
          echo "Error: " . $sql1 . "<br>" . $conn->error;
      }

      $conn->close();
      return $requestData;
  }

);
$app->post('/api/users/delete',
     function (Request $request, Response $response, array $args) {

      $servername = "serwer2001916.home.pl";
      $username = "32213694_scoreboard";
      $password = "passwordscoreboard";
      $dbname = "32213694_scoreboard";

      $requestData = $request->getParsedBody();
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      $userId = $requestData['id'];
      $sql = "DELETE FROM comments WHERE userId = $userId"; //usuwam jego komentarze

      if ($conn->query($sql) === TRUE) {
          echo "Comment deleted successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
      
      $sql1 = "DELETE FROM likes WHERE userId = $userId";   //usuwam jego lajki

      if ($conn->query($sql1) === TRUE) {
          echo "Likes deleted successfully";
      } else {
          echo "Error: " . $sql1 . "<br>" . $conn->error;
      }
      
      $sql10 = "SELECT articleId FROM rating WHERE userId = $userId";  //biore artykuły które on je ocenił
        $result = $conn->query($sql10);
        $articlesToChange = [];
        $articlesToChangeNumber = $result->num_rows;

        if ($articlesToChangeNumber > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $articlesToChange[] = $row['articleId'];
            }
        }
        

      $sql2 = "DELETE FROM rating WHERE userId = $userId";   //usuwam jego ratingi

      if ($conn->query($sql2) === TRUE) {
          echo "Ratings deleted successfully";
      } else {
          echo "Error: " . $sql2 . "<br>" . $conn->error;
      }

      for($j = 0 ; $j < $articlesToChangeNumber ; $j = $j + 1){     //zmiana średnich ocen artykułów tam gdzie usunę jego ocenę
          $artChId = $articlesToChange[$j];
            $sql11 = "SELECT rating FROM rating WHERE articleId = $artChId";
            $result = $conn->query($sql11);
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
                $wynik = round($avg/$count, 1);
            }
            else{
                $wynik = 0;
            }

            $sql12 = "UPDATE articles SET rating = '$wynik' WHERE id = $artChId";

            if ($conn->query($sql12) === TRUE) {
                echo "Updated article rating";
            } else {
                echo "Error: " . $sql12 . "<br>" . $conn->error;
            }
        }

      
      $sql3 = "SELECT id FROM articles WHERE userId = $userId";   //biore wszystkie jego artykuły

      $result = $conn->query($sql3);
        $articles = [];
        $numberOfArticles = $result->num_rows;
        if ($numberOfArticles > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $articles[] = $row['id'];
            }
        }

      //USUWANIE WSZYSTKICH JEGO ARTYKUŁÓW -------------------------------------------------------------------------------------
      echo "\r\nUsuwanie artykulow:\r\n";
        for($i = 0 ; $i < $numberOfArticles ; $i = $i+1){
            echo "\r\n";
            $artId = $articles[$i];
            $sql4 = "DELETE FROM articles WHERE id = $artId";

            if ($conn->query($sql4) === TRUE) {        //usuwanie artykułu
                echo "Article deleted successfully";
            } else {
                echo "Error: " . $sql4 . "<br>" . $conn->error;
            }
            
            $sql5 = "DELETE FROM rating WHERE articleId = $artId";

            if ($conn->query($sql5) === TRUE) {       //usuwanie ocen
                echo "Rating deleted successfully";
            } else {
                echo "Error: " . $sql5 . "<br>" . $conn->error;
            }
            
            $sql6 = "SELECT id FROM comments WHERE articleId = $artId";

            $commentsIds = [];
            $result = $conn->query($sql6);        //wzięcie id komentarzy
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $commentsIds[] = $row['id'];
                }
            }
            if(count($commentsIds) > 0){
                $sql7 = 'DELETE FROM likes WHERE objId IN (' . implode(',', array_map('intval', $commentsIds)) . ')';

                if ($conn->query($sql7) === TRUE) {       //usuwanie lików
                    echo "Likes deleted successfully";
                } else {
                    echo "Error: " . $sql7 . "<br>" . $conn->error;
                }
            }
            
            $sql8 = "DELETE FROM comments WHERE articleId = $artId";

            if ($conn->query($sql8) === TRUE) {       //usuwanie lików
                echo "Comments deleted successfully";
            } else {
                echo "Error: " . $sql8 . "<br>" . $conn->error;
            }
        }
    //KONIEC USUWANIA WSZYSTKICH JEGO ARTYKUŁÓW --------------------------------------------------------------------------------------

    $sql9 = "DELETE FROM users WHERE id = $userId";   //usuwam jego lajki

      if ($conn->query($sql9) === TRUE) {
          echo "User deleted successfully";
      } else {
          echo "Error: " . $sql9 . "<br>" . $conn->error;
      }


      $conn->close();
      return $requestData;
  }

);
$app->post('/api/likes/add',
     function (Request $request, Response $response, array $args) {

      $servername = "serwer2001916.home.pl";
      $username = "32213694_scoreboard";
      $password = "passwordscoreboard";
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
      $password = "passwordscoreboard";
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
      $password = "passwordscoreboard";
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
      $password = "passwordscoreboard";
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
      $password = "passwordscoreboard";
      $dbname = "32213694_scoreboard";

      $requestData = $request->getParsedBody();
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $sql = "INSERT INTO articles (title, tag, style, content, bibliography, userId)
       VALUES(?, '$requestData[category]', '$requestData[style]', ?, ?, '$requestData[userId]')";


        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $requestData['title'], $requestData['content'], $requestData['bibliography']); // 's' specifies the variable type => 'string'


      if ($stmt->execute() === TRUE) {
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
      $password = "passwordscoreboard";
      $dbname = "32213694_scoreboard";

      $requestData = $request->getParsedBody();
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
    //   $title = $requestData['title'];
      $tag = $requestData['tag'];
      $style = $requestData['style'];
    //   $content = $requestData['content'];
    //   $bibliography = $requestData['bibliography'];
      $id = $requestData['id'];

      $sql = "UPDATE articles SET title = ?, tag = \"$tag\",
       style=\"$style\", content= ?, bibliography= ? WHERE id = $id";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $requestData['title'], $requestData['content'], $requestData['bibliography']); // 's' specifies the variable type => 'string'

        

      if ($stmt->execute() === TRUE) {
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
      $password = "passwordscoreboard";
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
              VALUES('$requestData[nick]', ?, ?,
                 ?, ?, '$encryptedPass', '$requestData[avatar]')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $surname, $requestData['email'], $requestData['description']); // 's' specifies the variable type => 'string'


      if ($stmt->execute() === TRUE) {
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
      $password = "passwordscoreboard";
      $dbname = "32213694_scoreboard";

      $requestData = $request->getParsedBody();
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $nick = $requestData['nick'];
      $sqlVal = "SELECT nick, password FROM users WHERE nick = \"$nick\"";
      $resultVal = $conn->query($sqlVal);

      if ($resultVal->num_rows > 0) {
          $row = $resultVal->fetch_assoc();
          $hash = $row['password'];
          $finish = password_verify($requestData['password'], $hash);

      } else {
          echo "0 results";
      }
      if($finish){
        $email = $requestData['email'];
        $name = $requestData['name'];
        $surname = $requestData['surname'];
        $description = $requestData['description'];
        $nick = $requestData['nick'];
        $avatar = $requestData['avatar'];
        $encryptedPass = password_hash($requestData['newPassword'], PASSWORD_DEFAULT);
        if($requestData['newPassword'] != ""){
          $sql = "UPDATE users SET name = ?, surname = ?, email = ?, description = \"$description\",
           avatar = \"$avatar\", password = \"$encryptedPass\" WHERE nick = \"$nick\"";
        }
        else {
          $sql = "UPDATE users SET name = ?, surname = ?, email = ?, description = ?, avatar = \"$avatar\" WHERE nick = \"$nick\"";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $surname, $email, $description); // 's' specifies the variable type => 'string'

  
        if ($stmt->execute() === TRUE) {
            $wynik = "true";
            return $wynik;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }
      else {
          $wynik = "false";
          return $wynik;
      }

      $conn->close();
      $wynik = "true";
      return $wynik;
  }

);
$app->put('/api/articles/{id}/rating',
     function (Request $request, Response $response, array $args) {

      $servername = "serwer2001916.home.pl";
      $username = "32213694_scoreboard";
      $password = "passwordscoreboard";
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
      $password = "passwordscoreboard";
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
      $password = "passwordscoreboard";
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
$app->get('/api/emails/all',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
        $dbname = "32213694_scoreboard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, email FROM users";
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
$app->get('/api/emails/sleeping',
    function (Request $request, Response $response, array $args) {
        $servername = "serwer2001916.home.pl";
        $username = "32213694_scoreboard";
        $password = "passwordscoreboard";
        $dbname = "32213694_scoreboard";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, email FROM users WHERE TO_DAYS(LEFT(lastLogin , 10)) - TO_DAYS(CURDATE()) >= 5 OR lastLogin IS NULL";
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
