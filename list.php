<?php
require_once("header.php");
require_once("sidebar.html");

$cid = $_GET['id'];

$c = new Category();
$c->id = $cid;
$c->load();
$rs = $c->rep();

echo '<div class="col-10 showlist">
        <h1>'.$c->name.'</h1>
        <table>
            <tbody>';
foreach ($rs as $r) {
    $r->load();
    $user = $r->User->load();
    //var_dump($user);
    
    echo '<tr>
    <td>';
    echo '<a href="view.php?id=' . $r->id . '"><img src="image/' . $r->mainPic . '" alt=""></a>
    </td>';
    echo '<td>
        <ul>
            <li>
                <h4><a href="view.php?id=' . $r->id . '">' . $r->name . '</a></h4>
            </li>';
    echo '
            <li>
                <div><a href="user.php?id=' . $user->id .'">' . $user->userName . '</a></div>
            </li>';
    echo '
            <li>
                <span><a href="list.php?id=' . $c->id .'">' . $c->name . '</a></span>
            </li>
        </ul>
    </td>
</tr>
';
}

echo '</tbody>
    </table>
</div>';

require_once("footer.html");
?>