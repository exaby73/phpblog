<?php

/*
    In credentials.php, define the following variables:
        $dbhost -> Host or domain of postgres server. eg: localhost
        $dbname -> Database name
        $dbuser -> Username of user for above database
        $dbpass -> Password of user
*/
require_once("credentials.php");

$dsn = "pgsql:host=$dbhost;port=5432;dbname=$dbname;user=$dbuser;password=$dbpass";

try {
    $conn = new PDO($dsn);
} catch (PDOException $e) {
    echo $e->getMessage();
}
