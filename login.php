<?php

require_once("./include/urls.php");

require_once(URL_INCLUDE . "/include/connect.php");
require_once(URL_INCLUDE . "/include/validate.php");
session_start();

if (isset($_SESSION['blog_login'])) :
    header("Location: index");
    exit();
endif;

if (isset($_COOKIE['blog_username']) and isset($_COOKIE['blog_password']) and isset($_COOKIE['blog_id'])) :
    $id = $_COOKIE['blog_id'];
    $username = $_COOKIE['blog_username'];
    $password = $_COOKIE['blog_password'];
    $auth = login_validate($username, $password);

    if ($auth['validate']) :
        $_SESSION['blog_login'] = true;
        $_SESSION['blog_id'] = $id;
        header("Location: index?blog-id=" . $_SESSION['blog_id']);
        exit();
    endif;
endif;

if (isset($_POST['submit'])) :
    if (!isset($_POST['username']) or !isset($_POST['password'])) :
        $auth['message'] = "Something went wrong...";
    else :
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username)) :
            $auth['message'] = "Username is required";
        elseif (empty($password)) :
            $auth['username'] = $username;
            $auth['message'] = "Password is required";
        else :

            $auth = login_validate($username, $password);

            if ($auth['validate']) :
                if (isset($_POST['remember_me'])) :
                    // set cookie for 1 month
                    setcookie("blog_id", $auth['user_id'], time() + (60 * 60 * 24 * 30), "/");
                    setcookie("blog_username", $username, time() + (60 * 60 * 24 * 30), "/");
                    setcookie("blog_password", $password, time() + (60 * 60 * 24 * 30), "/");
                endif;

                $_SESSION['blog_login'] = true;
                $_SESSION['blog_id'] = $auth['user_id'];
                header("Location: index?id=" . $auth['user_id']);
                exit();
            endif;
        endif;
    endif;
endif;

if (isset($_GET['signup'])) :
    if ($_GET['signup'] === "success") :
        $auth['message'] = "Signup Success";
        $auth['message_class'] = "text-success";
    endif;
endif;

?>

<!doctype html>
<html lang="en">

<head>
    <title>Blog | Login</title>

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
                    <h3 class="card-title">Login</h3>
                </div>
                <div class="dropdown-divider my-3"></div>
                <form action="<?= URL_HREF . "/login" ?>" method="POST">
                    <div class="form-group mt-4">
                        <label for="username">Username/Email</label>
                        <input type="text" class="form-control" name="username" id="username" value="<?= (isset($auth['username'])) ? $auth['username'] : "" ?>">
                    </div>
                    <div class="form-group mt-4">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group form-check mt-4">
                        <input type="checkbox" class="form-check-input" name="remember_me" id="remember_me">
                        <label class="form-check-label" for="remember-me">Remember Me</label>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-purple mt-4 btn-block">Login</button>
                    </div>
                </form>
                <div class="d-flex justify-centent-center">
                    <p class="<?= (isset($auth['message_class'])) ? $auth['message_class'] : "text-danger" ?> mx-auto mt-4"><?= (isset($auth['message'])) ? $auth['message'] : "" ?></p>
                </div>
                <div class="dropdown-divider mb-4 mt-3"></div>
                <div class="d-flex justify-centent-center">
                    <a href="<?= URL_HREF . "/signup" ?>" class="card-link">No account? Register Now!</a>
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