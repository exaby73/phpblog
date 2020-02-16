<?php

require_once("./include/urls.php");

require_once(URL_INCLUDE . "/include/connect.php");
require_once(URL_INCLUDE . "/include/validate.php");
require_once(URL_INCLUDE . "/include/helper_functions.php");
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

        if ($row['user_id'] !== $_SESSION['blog_id']) :
            header("Location: 404?user");
            exit();
        endif;

        $title = $row['title'];
        $body = $row['body'];
        $date_created = $row['date_created'];
        $user_id = $row['user_id'];

        $sql_query = "SELECT first_name, last_name FROM users WHERE id=$user_id";
        $query = $conn->query($sql_query);

        $row = $query->fetch(PDO::FETCH_ASSOC);

        $first_name = $row['first_name'];
        $last_name = $row['last_name'];

        $author = "$first_name $last_name";
    endif;
endif;

if (isset($_POST['submit'])) :
    if (!isset($_POST['title']) or !isset($_POST['body'])) :
        $auth['message'] = "Something went wrong...";
    else :
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);

        if (empty($title)) :
            $auth['message'] = "Title is required";
            $auth['body'] = (isset($body)) ? $body : null;
        elseif (empty($body)) :
            $auth['message'] = "Body is required";
            $auth['title'] = $title;
        else :
            $sql_query = "UPDATE posts SET title=?, body=? WHERE id=$post_id";
            $stmt = $conn->prepare($sql_query);
            $stmt->execute([$title, $body]);

            header("Location: /view?id=$post_id");
            exit();
        endif;
    endif;
endif;

?>

<!doctype html>
<html lang="en">

<head>
    <title>Blog | Edit Post</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="shortcut icon" href="<?= URL_HREF . "/favicon.ico" ?>">
    <link rel="stylesheet" href="<?= URL_HREF . "/assets/css/main.css" ?>">
</head>

<body>

    <?php include_once(URL_INCLUDE . "/include/nav.php") ?>

    <div class="container">
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <h3 class="card-title">Edit Post</h3>
                </div>
                <div class="dropdown-divider my-3"></div>
                <form action="<?= URL_HREF . "/edit?id=$post_id" ?>" method="POST">
                    <div class="mt-4">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" value="<?= (isset($auth['title'])) ? $auth['title'] : $title ?>">
                    </div>
                    <div class="mt-4">
                        <label for="body">Body</label>
                        <textarea class="form-control" name="body" id="body" rows="20" wrap="soft"><?= (isset($auth['body'])) ? $auth['body'] : $body ?></textarea>
                    </div>
                    <div class="mt-4">
                        <label for="date_created">Date Created</label>
                        <p id="date_created"><?= format_date($date_created) ?></p>
                    </div>
                    <div class="mt-4">
                        <label for="author">Author</label>
                        <p id="author"><?= $author ?></p>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-purple mt-4 btn-block">Submit</button>
                    </div>
                </form>
                <div class="d-flex justify-centent-center">
                    <p class="text-danger mx-auto mt-4"><?= (isset($auth['message'])) ? $auth['message'] : "" ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>