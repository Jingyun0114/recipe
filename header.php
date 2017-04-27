<?php
require_once("main.php");
session_start();
$customer = new User();
$mark = $customer->checkLogin();
require_once("header.html");
if (!$mark) {
    echo '<div class="col-3 forlogin">
                    <div id="login"><a href="login.html">Login</a></div>
                    <div id="register"><a href="register.html">Register</a></div>
                </div>
        </div>
        <!-- End of header part here -->';
} else {
    echo '<div class="col-3 forlogin">
            <div id="login"><a href="user.php?id=' . $_SESSION["login_user_id"] . '">'.$_SESSION["login_user_name"].'</a></div>
            <div id="register">
                <a href="logout.php">Logout</a>
            </div>
            <!-- <div id="register">
                <a href="register.html">Register</a>
            </div> -->
        </div>
        </div>
        <!-- End of header part here -->';
}

?>