<?php

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "yoonydb";
$conn = "";

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception) {
    echo "Error connecting to the database <br>";
}

//for testing purposes
if ($conn) {
    echo "you are connected to the database";
} else {
    echo "you are not connected to the database";
}