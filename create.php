<?php

require_once("./include/urls.php");

require_once(URL_INCLUDE . "/include/connect.php");
session_start();

// Not logged in redirect...
if (!isset($_SESSION['blog_login'])) :
    header("Location: login");
endif;

if (isset($_POST['submit']) and $_SERVER['REQUEST_METHOD'] == "POST") :
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
            $sql_query = "INSERT INTO posts (user_id, title, body, date_created) VALUES (:user_id, :title, :body, :date_created)";
            $stmt = $conn->prepare($sql_query);
            $stmt->execute([
                'user_id' => $_SESSION['blog_id'],
                'title' => $title,
                'body' => $body,
                'date_created' => date('Y-m-d')
            ]);

            header("Location: index");
            exit();
        endif;
    endif;
endif;

?>

<!doctype html>
<html lang="en">

<head>
    <title>Blog | Create Post</title>

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
        <div class="card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <h3 class="card-title">Create Post</h3>
                </div>
                <div class="dropdown-divider my-3"></div>
                <form action="<?= URL_HREF . "/create" ?>" method="POST">
                    <div class="mt-4">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" value="<?= (isset($auth['title'])) ? $auth['title'] : "" ?>">
                    </div>
                    <div class="mt-4">
                        <label for="body">Body</label>
                        <textarea class="form-control" name="body" id="body" rows="20" wrap="soft"><?= (isset($auth['body'])) ? $auth['body'] : "" ?></textarea>
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>