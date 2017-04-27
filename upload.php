<?php
require_once("main.php");
session_start();
$uploadOk = 0;
if(!empty($_FILES["photo"]["tmp_name"]) && !empty($_FILES["photo"]["name"])) {
    $targetName = time() .'_' . basename($_FILES["photo"]["name"]);
    $targetFile = "image/" . $targetName;
    //$uploadOk = 1;
    $imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
    // check whether real picture
    if(isset($_POST["up_submit"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Sorry, file is not an image.";
            $uploadOk = 0;
        }
    }
    // check size, not allow larger than 800K
    if ($_FILES["photo"]["size"] > 819200) {
        echo "Sorry, file size should no larger than 800K.";
        $uploadOk = 0;
    }
    // check format
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // upload
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } 
}

if(isset($_POST["up_submit"]) && $uploadOk == 1) {
        $recipe = new Recipe();
        $instruction = "";
        $ingredient = "";
        foreach($_POST as $key => $p) {
            if (strpos($key, "instruction") !== false) {
                $instruction .= $p . ";";
            }
            if (strpos($key, "ingredient") !== false) {
                $ingredient .= $p . ";";
            }
        }
        $instruction = rtrim($instruction, ";");
        $ingredient = rtrim($ingredient, ";");
        $recipe->name = $_POST["rname"];
        $recipe->reCalorie = $_POST["calorie"];
        $recipe->reIngredient = $ingredient;
        $recipe->reInstruction = $instruction; //instruction_1,ingredient_1
        $recipe->mainPic = $targetName;
        $recipe->creatTime = date("Y-m-d H:i:s", time());
        $recipe->like = 0;
        $recipe->categoryId = $_POST["tid"];
        $recipe->userId = $_SESSION["login_user_id"];
        
        if ($recipe->insert() !== false) {
            header("Location: home.php");
        } else {
            echo "Sorry, failed to insert.";
        }
} else
    echo "Sorry, failed to submit.";




?>