<?php
setcookie("fav_food", "pizza", time() + 3600, "/");
setcookie("fav_drink", "coffee", time() + 3600, "/");
setcookie("fav_dessert", "ice cream", time() + 3600, "/");

//* foreach ($_COOKIE as $key => $val) {
//*     echo "{$key} = {$val} <br>";
//* }

if (isset($_COOKIE['fav_food'])) {
    echo "buy a {$_COOKIE['fav_food']}";
} else {
    echo "there is no cookies";
}
