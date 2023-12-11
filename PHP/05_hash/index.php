<?php
$password = "yoones67";

$hash = password_hash($password, PASSWORD_DEFAULT);

if (password_verify("$password", $hash)) {
    echo "correct password";
} else {
    echo "wrong password";
}
