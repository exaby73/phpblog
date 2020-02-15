<?php

/*
    URL_INCLUDE -> Holds absolute file path to root of document 
        echo in PHP include and require statements

    URL_HREF -> Holds http link to root of document
        echo in client-side HTML/JS such as href attributes
*/

define("URL_INCLUDE", "{$_SERVER['DOCUMENT_ROOT']}/wbp-project");
define("URL_HREF", "http://{$_SERVER['HTTP_HOST']}/wbp-project");

require_once(URL_INCLUDE . "/include/connect.php");
session_start();

// Not logged in redirect...
if (!isset($_SESSION['blog_login'])) :
    header("Location: login");
endif;

// Get recent posts
$sql_query = "SELECT * FROM posts ORDER BY date_created DESC LIMIT 100";
$posts = $conn->query($sql_query);
?>

<!doctype html>
<html lang="en">

<head>
    <title>Blog | Home</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= URL_HREF . "/assets/css/main.css" ?>">
</head>

<body>
    <!-- Navigation Bar -->
    <?php include_once(URL_INCLUDE . "/include/nav.php") ?>

    <div class="container">

        <?php if ($posts->rowCount() === 0) : ?>
            <div class="d-flex justify-content-center">
                <p class="h4 text-muted">No posts to show</p>
            </div>

        <?php else : ?>

            <?php while ($post = $posts->fetch(PDO::FETCH_ASSOC)) : ?>

                <?php

                // Parse date into human readable format
                $date_data = explode("-", $post['date_created']);
                $date = "{$date_data[2]} ";

                switch ($date_data[1]):
                    case "01":
                        $date .= "Jan, ";
                        break;
                    case "02":
                        $date .= "Feb, ";
                        break;
                    case "03":
                        $date .= "Mar, ";
                        break;
                    case "04":
                        $date .= "Apr, ";
                        break;
                    case "05":
                        $date .= "May, ";
                        break;
                    case "06":
                        $date .= "Jun, ";
                        break;
                    case "07":
                        $date .= "Jul, ";
                        break;
                    case "08":
                        $date .= "Aug, ";
                        break;
                    case "09":
                        $date .= "Sep, ";
                        break;
                    case "10":
                        $date .= "Oct, ";
                        break;
                    case "11":
                        $date .= "Nov, ";
                        break;
                    case "12":
                        $date .= "Dec, ";
                        break;
                endswitch;

                $date .= $date_data['0'];

                // Get username of user_id in post
                $sql_query = "SELECT username FROM users WHERE id={$post['user_id']}";
                $user = $conn->query($sql_query);
                $user = $user->fetch(PDO::FETCH_ASSOC);
                $user = $user['username'];

                ?>

                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><?= $post['title'] ?></h3>
                        <div class="dropdown-divider"></div>
                        <p class="card-text"><?= $post['body'] ?></p>
                        <div class="dropdown-divider"></div>
                        <div class="d-flex justify-content-between">
                            <em><?= $date ?></em>
                            <em>- <?= $user ?></em>
                        </div>
                    </div>
                </div>

            <?php endwhile ?>
        <?php endif ?>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>