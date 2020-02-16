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

if (!isset($_GET['id'])) :
    header("Location: 404?noid");
    exit();
else :
    $post_id = $_GET['id'];

    $sql_query = "SELECT * FROM posts WHERE id=? LIMIT 1";
    $stmt = $conn->prepare($sql_query);
    $stmt->execute([$post_id]);

    if ($stmt->rowCount() === 0) :
        header("Location: 404?rowcount");
        exit();
    else :
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $title = $row['title'];
        $body = $row['body'];
        $date_created = $row['date_created'];
        $user_id = $row['user_id'];

        $sql_query = "SELECT id, first_name, last_name FROM users WHERE id=$user_id";
        $query = $conn->query($sql_query);

        $row = $query->fetch(PDO::FETCH_ASSOC);

        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];

        $author = "$first_name $last_name";
    endif;
endif;

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
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?= $title ?></h3>
                    <?php if ($_SESSION['blog_id'] == $id) : ?>
                        <a href="<?= URL_HREF . "/edit?id=$post_id" ?>" class="btn btn-purple d-flex align-items-center h-50">Edit</a>
                    <?php endif ?>
                </div>
                <div class="dropdown-divider"></div>
                <div class="card-text"><?= $markdown->text($body) ?></div>
                <div class="dropdown-divider"></div>
                <div class="d-flex justify-content-between">
                    <em><?= format_date($date_created) ?></em>
                    <em>- <?= $author ?></em>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>