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

    return ['validate' => true, 'message' => "Success"];
}

function signup_validate($first_name, $last_name, $username, $email, $password, $password_copy)
{
    global $conn;
}
