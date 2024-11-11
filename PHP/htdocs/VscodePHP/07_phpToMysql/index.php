<?php
include("database.php");

$username = "aisan";
$password = "eslami";
// $hash = password_hash($password , PASSWORD_DEFAULT);

$sql = "INSERT INTO users(user ,password) VALUES ('$username' , '$password')";

try{
    mysqli_query($conn, $sql);
    echo "user is now registered";
}
catch(mysqli_sql_exception){
    echo "could not register user";
}
mysqli_close($conn);
?>