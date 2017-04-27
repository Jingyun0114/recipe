<?php
require_once("header.php");

$uid = $_GET['id'];
$u = new User();
$u->id = $uid;
$u->load();
$rs = $u->rep();
//var_dump($rs);

echo '
<div class="row main">
    <div class="col-9 showlist">
        ';
foreach ($rs as $r) {
    $r->load();
    //$user = $r->User->load();
    //var_dump($user);
    $c = $r->Category->load();
    
    echo '<table>
            <tbody>
            <tr>
    <td>';
    echo '<a href="view.php?id=' . $r->id . '"><img src="image/' . $r->mainPic . '" alt=""></a>
    </td>';
    echo '<td>
        <ul>
            <li>
                <a href="view.php?id=' . $r->id . '">' . $r->name . '</a>
            </li>';
    echo '
            <li>
                <a href="user.php?id=' . $u->id .'">' . $u->userName . '</a>
            </li>';
    echo '
            <li>
                <a href="user.php?id=' . $c->id .'">' . $c->name . '</a>
            </li>
        </ul>
    </td>
</tr>
</tbody>
</table>
';
}

echo '</div>
<div class="col-3 userinfo">
    <h1>'.$u->userName.'</h1>
    <div id="personal">';
if (!empty($u->userPic))
    echo '<img src="image/'. $u->userPic .'" alt="user_picture" />';
else echo '<img src="image/userDefault.jpg" alt="user_picture" />';
echo '<p id="bio">'.$u->userInfo.'</p>
    </div>
</div>
';

require_once("footer.html");
?>