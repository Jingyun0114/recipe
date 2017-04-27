<?php
require_once("main.php");

$uploadOk = 0;
if(!empty($_FILES["photo"]["tmp_name"]) && !empty($_FILES["photo"]["name"])) {
    $targetName = time() .'_' . basename($_FILES["photo"]["name"]);
    $targetFile = "image/" . $targetName;
    //$uploadOk = 1;
    $imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
    // check whether real picture
    if(isset($_POST["reg_submit"])) {
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
            //echo "The file ". basename($_FILES["photo"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } 
}

if(isset($_POST["reg_submit"])) {
    if (empty($_POST["email"]) || empty($_POST["username"]) || empty($_POST["password"])) {
        echo "Sorry, some parameters are missing.";
    } else {
        $post = $_POST;
        $user = new User();
        
        $user->emailAddress = $_POST["email"];
        $user->userName = $_POST["username"];
        $user->userPwd = md5($_POST["password"]);
        $user->userPic = $uploadOk == 1 ? $targetName : "defaultUser.png";
        $user->userInfo = !empty($_POST["bio"]) ? $_POST["bio"] : NULL;
        $user->regTime = date("Y-m-d H:i:s", time());
        
        if ($user->insert() !== false) {
            //$newUser = new User();
            //$newUser->login($_POST["email"], $_POST["password"]);
            header("Location: login.html");
            exit();
        } else {
            echo "Sorry, failed to insert.";
        }
    }
} else
    echo "Sorry, failed to submit.";




?>