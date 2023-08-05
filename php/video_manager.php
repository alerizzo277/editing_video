<?php
session_start();

include '../vendor/autoload.php';
include 'functions.php';
include 'db_connection.php';
include 'classes/Video.php';
include 'classes/Person.php';

$pdo = get_connection();
$video = null;
$person = null;

if(isset($_SESSION["video"])){
    $video = unserialize($_SESSION["video"]);
}
if(isset($_SESSION["person"])){
    $person = unserialize($_SESSION["person"]);
    myVarDump($person);
}
else{
    header("Location: ../".INDEX);
}

if(isset($_GET["operation"])){
    switch($_GET["operation"]){
        case "select_video":
            select($pdo);
            //$video = unserialize($_SESSION["video"]);se il video salvato in sessio
            header("Location: ".VIDEO_DETAILS);
            break;
        case "new_video":
            break;  
        case "update_video":
            update($pdo, $video, $person);
            //header("Location: " . getPreviusPage());
            header("Location: " . VIDEO_MANAGER . "?operation=select_video&id=" . $video->getId());
            break;
        case "delete_video":
            delete($pdo);
            header("Location: " . getPreviusPage() . "?video_deleted=true");
            break;
        case "multiple_video_delete":
            multipleDelete($pdo);
            header("Location: " . getPreviusPage() . "?videos_deleted=true");
            break;
        default:
			break;
    }
}

function select($pdo){
    if(isset($_GET["id"])){
        try{
            $id = $_GET["id"];
            $video = getVideoFromId($pdo, $id);
            $_SESSION["video"] = serialize($video);
            $_SESSION["path_video"] = $video->getPath();
        } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
    }
}

function update($pdo, $video, $person){
    if(isset($_GET["id"])){
        try{
            myVarDump($person);
            $id = intval($_GET["id"]);
            $name = ($_POST["video_name"] == "") ? null : $_POST["video_name"];
            $note = ($_POST["video_note"] == "") ? null : $_POST["video_note"];
            $video = new Video($video->getId(), $video->getPath(), $name, $note, $person->getEmail(), $video->getSession());
            updateVideo($pdo, $video);
        } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
    }
}

function delete($pdo){
    echo $_SERVER['QUERY_STRING']."<br>";
    if(isset($_GET["id"])){
        try{
            $id = intval($_GET["id"]);
            deleteVideoFromId($pdo, $id);
        } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
    }
}

function multipleDelete($pdo){
	foreach($_POST["id"] as $el){
        try{
            $video = getVideoFromId($pdo, $el);
            if(file_exists("../{$video->getPath()}")){   
                unlink("../{$video->getPath()}");
            }
            deleteVideoFromId($pdo, $el);
        } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
	}
}