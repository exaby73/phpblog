<?php

require_once("./include/urls.php");

require_once(URL_INCLUDE . "/include/connect.php");
require_once(URL_INCLUDE . "/include/helper_functions.php");

require_once(URL_INCLUDE . "/vendor/autoload.php");
session_start();

// Not logged in redirect...
if (!isset($_SESSION['blog_login'])) :
    header("Location: login");
endif;

// Get recent posts
$sql_query = "SELECT * FROM posts ORDER BY date_created DESC LIMIT 100";
$posts = $conn->query($sql_query);

$markdown = new Parsedown();
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
    <link rel="shortcut icon" href="<?= URL_HREF . "/favicon.ico" ?>">
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

                $date = format_date($post['date_created']);

                // Get username of user_id in post
                $sql_query = "SELECT id, first_name, last_name FROM users WHERE id={$post['user_id']}";
                $stmt = $conn->query($sql_query);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $row['id'];
                $user = "{$row['first_name']} {$row['last_name']}";

                ?>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title"><?= $post['title'] ?></h3>
                            <div class="btn-group h-50">
                                <a href="<?= URL_HREF . "/view?id={$post['id']}" ?>" class="btn btn-purple d-flex align-items-center">View</a>
                                <?php if ($_SESSION['blog_id'] == $id) : ?>
                                    <a href="<?= URL_HREF . "/edit?id={$post['id']}" ?>" class="btn btn-warning d-flex align-items-center">Edit</a>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="card-text card-text-list-item"><?= $markdown->text($post['body']) ?></div>
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