<?php
require_once("main.php");
session_start();
if (isset($_POST["log_submit"])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo "email or password is not set!";
    } else {
        $user = new User();
        // check login
        //$user->checkLogin($email);
        $user->login($_POST['email'], $_POST['password']);
        
    }
}




?>