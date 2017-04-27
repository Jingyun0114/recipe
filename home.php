<?php
require_once("header.php");
require_once("sidebar.html");
//var_dump($_SESSION);
// begin of main content
echo '<div class="col-7 show">
        <table>
            <tbody>';
$r = new Recipe();

if (isset($_GET["se_submit"]) && !empty($_GET["search"])) {
    $keywords = preg_split("/[\s,&$|]+/", trim($_GET["search"]));
    $rs = $r->search($keywords);  
} else {
    $rs = $r->newest();
}

if (!is_null($rs)) {
    foreach ($rs as $nr) {
        $nr->load();
        $user = $nr->User->load();
        $category = $nr->Category->load();
        echo '<tr>
        <td>';
        echo '<a href="view.php?id=' . $nr->id . '"><img src="image/' . $nr->mainPic . '" alt=""></a>
        </td>';
        echo '<td>
            <ul>
                <li>
                    <h4><a href="view.php?id=' . $nr->id . '">' . $nr->name . '</a></h4>
                </li>';
        echo '
                <li>
                    <div><a href="user.php?id=' . $user->id .'">' . $user->userName . '</a></div>
                </li>';
        echo '
                <li>
                    <span><a href="list.php?id=' . $category->id .'">' . $category->name . '</a></span>
                </li>
            </ul>
        </td>
    </tr>
    ';
    }
} else {
    echo "No result";
}


echo '</tbody>
    </table>
</div>';

// begin of user and history part
echo '<div class="col-3 right">';
if (!$mark) {
    echo '<div id="home_login"><a href="login.html">Login</a></div>
    <div id="home_register">
                    <a href="register.html">Register</a>
                </div>
                ';
} else {
    echo '<div id="user_login">
            Welcome, <a href="user.php?id=' . $_SESSION["login_user_id"] . '">'.$_SESSION["login_user_name"].'</a>
        </div>
        <div id="user_upload">
            <a href="upload.html">Upload</a>
        </div>
    <div id="history">
                    <p>History</p>
                        <ul>';
                    if (isset($_SESSION["history"])) {
                        foreach ($_SESSION["history"] as $h) {
                            $hArray = explode(':', trim($h, '\''));
                            echo '<li>
                                <a href="view.php?id='.$hArray[0].'">' . $hArray[1] . '</a>
                            </li>';
                        }
                        
                    }    
                    echo '</ul>
                </div>';

}

echo ' </div>';


require_once("footer.html");
?>