<?php
require_once("main.php");
session_start();

if (isset($_SERVER['HTTP_REFERER'])) $preUrl = $_SERVER['HTTP_REFERER'];
$customer = new User();
$customer->logout($preUrl);


?>