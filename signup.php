<?php

/*
    URL_INCLUDE -> Holds absolute file path to root of document 
        echo in PHP include and require statements

    URL_HREF -> Holds http link to root of document
        echo in client-side HTML/JS such as href attributes
*/

$local_dev = getenv('LOCAL');

define("URL_INCLUDE", "{$_SERVER['DOCUMENT_ROOT']}");
if ($local_dev == "true") :
    define("URL_HREF", "http://{$_SERVER['HTTP_HOST']}");
else :
    define("URL_HREF", "https://{$_SERVER['HTTP_HOST']}");
endif;

require_once(URL_INCLUDE . "/include/connect.php");
require_once(URL_INCLUDE . "/include/validate.php");
session_start();

if (isset($_SESSION['blog_login'])) :
    header("Location: index");
    exit();
endif;

if (isset($_COOKIE['blog_username']) and isset($_COOKIE['blog_password'])) :
    $username = $_COOKIE['blog_username'];
    $password = $_COOKIE['blog_password'];
    $auth = login_validate($username, $password);

    if ($auth['validate']) :
        $_SESSION['blog_login'] = true;
        header("Location: index");
        exit();
    endif;
endif;

if (isset($_POST['submit'])) :
    if (
        !isset($_POST['first_name'])
        or !isset($_POST['last_name'])
        or !isset($_POST['username'])
        or !isset($_POST['email'])
        or !isset($_POST['password'])
        or !isset($_POST['confirm_password'])
    ) :
        $auth['message'] = "Something went wrong...";
    else :
        // Check if any field is empty and generate a user friendly message
        foreach ($_POST as $key => $value) :
            if ($key === "submit") :
                continue;
            endif;
            if (empty($_POST[$key])) :
                $message = "";

                $field_names = explode("_", $key);
                foreach ($field_names as $field) :
                    $message .= ucfirst($field) . " ";
                endforeach;

                $message .= "is required";
                $auth['message'] = $message;

                break;
            else :
                if ($key === "password" or $key === "confirm_password") :
                    continue;
                endif;
                $auth[$key] = $value;
            endif;
        endforeach;

        if (!isset($auth['message'])) :
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            $auth = signup_validate($first_name, $last_name, $username, $email, $password, $confirm_password);

            if ($auth['validate']) :
                $sql_query = "INSERT INTO users (username, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_query);
                $stmt->execute([$username, $first_name, $last_name, $email, password_hash($password, PASSWORD_BCRYPT)]);
                header("Location: login?signup=success");
                exit();
            endif;
        endif;

    endif;

endif;

?>

<!doctype html>
<html lang="en">

<head>
    <title>Blog | Signup</title>

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
                    <h3 class="card-title">Signup</h3>
                </div>
                <div class="dropdown-divider my-3"></div>
                <form action="<?= URL_HREF . "/signup" ?>" method="POST">
                    <div class="form-group mt-4">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" value="<?= (isset($auth['first_name'])) ? $auth['first_name'] : "" ?>">
                    </div>
                    <div class="form-group mt-4">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="<?= (isset($auth['last_name'])) ? $auth['last_name'] : "" ?>">
                    </div>
                    <div class="form-group mt-4">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" value="<?= (isset($auth['username'])) ? $auth['username'] : "" ?>">
                    </div>
                    <div class="form-group mt-4">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" value="<?= (isset($auth['email'])) ? $auth['email'] : "" ?>">
                    </div>
                    <div class="form-group mt-4">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" value="<?= (isset($auth['password'])) ? $auth['password'] : "" ?>">
                    </div>
                    <div class="form-group mt-4">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" value="<?= (isset($auth['confirm_password'])) ? $auth['confirm_password'] : "" ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-purple mt-4 btn-block">Signup</button>
                    </div>
                </form>
                <div class="d-flex justify-centent-center">
                    <p class="text-danger mx-auto mt-4"><?= (isset($auth['message'])) ? $auth['message'] : "" ?></p>
                </div>
                <div class="dropdown-divider mb-4 mt-3"></div>
                <div class="d-flex justify-centent-center">
                    <a href="<?= URL_HREF . "/login" ?>" class="card-link">Already have an account? Login</a>
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