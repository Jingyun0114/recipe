<?php
require_once("header.php");
require_once("sidebar.html");

$rid = $_GET['id'];
$r = new Recipe();
$r->id = $rid;
$r->load();
$r->User->load();
$ingredients = array();
$ingredients = $r->cropString($r->reIngredient);
$instructions = array();
$instructions = $r->cropString($r->reInstruction);
if (isset($_SESSION["history"])) {
    $history = is_null($_SESSION["history"]) ? array() : $_SESSION["history"];
    //echo "'" .$r->id . ":" . $r->name."'";
    array_unshift($history, "'" .$r->id . ":" . $r->name."'");
    $_SESSION["history"] = $history;  
}


$co = new Comment();
$comments = $co->allComments($rid);
$customerId = isset($_SESSION["login_user_id"])? $_SESSION["login_user_id"] : 0;
        
echo '      
        <div class="col-10 showview">
            <div id="recipe_main">
                <h1>'. $r->name .'</h1>
                <ul>
                    <li>
                        <img src="image/'.$r->mainPic.'" alt="">
                    </li>
                    <li>
                        <h3><a href="user.php?id='.$r->User->id.'">'. $r->User->userName .'</a></h3>
                    </li>
                    <li>
                        <div>'.$r->creatTime.'</div>
                    </li>
                    <li>
                        <label>Calories:</label>
                        ' . $r->reCalorie . '
                    </li>
                    <li>
                        <div id="ingre_'.$r->id.'">
                        <label>Ingredients:</label>
                            <ul>';
foreach($ingredients as $i) {
    echo '
                                <li>'.$i.'</li>
          ';
}                        
                        
echo '      </ul>
                </div>
                </li>
                    <li>
                        <div id="instr_'.$r->id.'">
                        <label>Instructions:</label>
                        <ul>';
foreach($instructions as $i) {
    echo '
                            <li>'.$i.'</li>
          ';
}                        
                        
echo '                  </ul>
                        </div>
                    </li>
                </ul>
            </div>';
// begin comments part here

echo '<div id="comment">
                <h4>Comments:</h4>    
                <textarea id="com_context" rows="5" cols="50" name="com_context" required></textarea>
                <button id="com_submit" name="com_submit" onclick="addComment('.$r->id.','. $customerId .')" >Add Comment</button>
                
                <div id="all_comm">
                    <table>
                        <tbody>';
                        foreach($comments as $comment) {
                            $comment->load();
                            $comment->User->load();
                            echo '<tr>
                                <td>'.$comment->User->userName.':</td>
                                <td>'.$comment->comText.'</td>
                            </tr>';
                        }
                            
echo '                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>';





require_once("footer.html");

?>