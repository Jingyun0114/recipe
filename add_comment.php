<?php
require_once("main.php");

$commText = $_GET["ct"];
$uid = $_GET["uid"];
$rid = $_GET["rid"];

if (isset($commText) && isset($uid) && isset($rid)) {
    $comment = new Comment();
    $comment->repId = $rid;
    $comment->comUserId = $uid;
    $comment->comTime = date("Y-m-d H:i:s", time());
    $comment->comText = $commText;
    
    $newCommId = $comment->insert();
    if ($newCommId !== false) {
        $newComm = new Comment();
        $newComm->id = $newCommId;
        $newComm->load();
        $newComm->User->load();
        
        $newCommText = "1:" . $newComm->User->userName . ":" . $newComm->comText;
        echo $newCommText;
    } else {
        echo "0:0:Sorry, failed to add Comment.";
    }
} else
    echo "0:0:Sorry, failed to add Comment.";


?>