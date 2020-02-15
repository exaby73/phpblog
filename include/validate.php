<?php

function login_validate($username, $password)
{
    global $conn;

    $sql_query = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = $conn->prepare($sql_query);
    $stmt->execute([$username, $username]);

    if ($stmt->rowCount() == 0) :
        return ['validate' => false, 'message' => "User does not exist"];
    endif;

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $password_db = $user['password'];
    if (!password_verify($password, $password_db)) :
        return ['validate' => false, 'message' => "Incorrect password", 'username' => $username];
    endif;

    return ['validate' => true, 'user_id' => $user['id']];
}

function signup_validate($first_name, $last_name, $username, $email, $password, $password_copy)
{
    global $conn;

    $sql_query = "SELECT * FROM users WHERE username=? LIMIT 1";
    $stmt = $conn->prepare($sql_query);
    $stmt->execute([$username]);

    if ($stmt->rowCount() == 1) :
        return ['validate' => false, 'message' => "Username $username is already taken"];
    endif;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
        return [
            'validate' => false,
            'message' => "$email is not a valid email address",
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username
        ];
    endif;

    $sql_query = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($sql_query);
    $stmt->execute([$email]);

    if ($stmt->rowCount() == 1) :
        return [
            'validate' => false,
            'message' => "Email $email is already taken",
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username
        ];
    endif;

    if (strlen($password) < 8) :
        return [
            'validate' => false, 'message' => "Password should be at least 8 characters long",
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'email' => $email
        ];
    endif;

    if ($password !== $password_copy) :
        return [
            'validate' => false, 'message' => "Passwords do not match",
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'email' => $email
        ];
    endif;

    return ['validate' => true];
}
