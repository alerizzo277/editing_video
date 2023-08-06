<?php
session_start();

include '../vendor/autoload.php';
include 'functions.php';
include 'db_connection.php';
include 'classes/Screen.php';
include 'classes/Video.php';
include 'classes/Person.php';

$video = null;
$person = null;
$filename = null;
$path_video = null;

if(isset($_SESSION["video"])){
    $video = unserialize($_SESSION["video"]);
    $filename = basename($video->getPath(), ".mp4");
}
if(isset($_SESSION["person"])){
    $person = unserialize($_SESSION["person"]);
    myVarDump($person);
}
else{
    header("Location: ../".INDEX);
}

$pdo = get_connection();

if(isset($_GET["operation"])){
    switch ($_GET["operation"]){
        case "get_screen":
            try{                
                $timing_screen_string = $_POST["timing_video"];
                $timing_screen = getIntTimingScreen($timing_screen_string);
                getScreen($video->getPath(), $filename, $timing_screen, $timing_screen_string, $pdo);
            } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
            //header("Location: editing_video.php?timing_screen=$timing_screen");
            header("Location: " . getPreviusPage(). "?timing_screen=$timing_screen");
            break;
        case "update_screen":
            try{  
                $ok = "true";
                if(!updateScreen($pdo)){
                    $ok = "false";
                }
                header("Location: screen_details.php?id={$_GET["id"]}&updated=$ok");
            } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
            break;
        case "delete_screen":
            if(isset($_GET["id"])){
                deleteScreen($pdo, $_GET["id"]);
            }
            header("Location: screen_details.php?id={$_GET["id"]}&screen_deleted=true");
            break;
        case "multiple_screen_delete":
            if(isset($_POST["id"])){
                multipleDelete($pdo);
            }
            header("Location: ./screenshots_list.php");
            break;

    }
}

//funzioni locali, solo per questo file
function getScreen($path_video, $filename, $timing_screen, $timing_screen_string, $pdo){
    $ffmpeg = FFMpeg\FFMpeg::create();
    $video = $ffmpeg->open("../$path_video");
    
    $screen_name = generateScreenName($filename, $timing_screen_string);
    $video
        ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($timing_screen))
        ->save("../" . $screen_name);
    
    $name = basename($screen_name, ".jpg");
    $note = "Screenshots del video $path_video";
    $screen = new Screen($screen_name, $name, $note, NULL, $path_video);
    insertNewScreen($pdo, $screen);
}

function updateScreen($pdo){
    $id = $_GET["id"];
    $name = ($_POST["screen_name"] == "") ? null : $_POST["screen_name"];
    $note = ($_POST["screen_note"] == "") ? null : $_POST["screen_note"];
    $screen = new Screen(null, $name, $note, $id, null);
    return updateScreenFromId($pdo, $screen);
}

function deleteScreen($pdo, $id){
    $path_screen = "";
    $query = "SELECT locazione FROM screenshots WHERE id=$id";
    $statement = $pdo->query($query);
    $publishers = $statement->fetchAll(PDO::FETCH_ASSOC);
    if ($publishers) {
        foreach ($publishers as $publisher) {
            $path_screen = $publisher['locazione'];
        }
    }
    deleteScreenFromId($pdo, $id);
    unlink("../$path_screen");
}

function multipleDelete($pdo){
    foreach($_POST["id"] as $el){
        deleteScreen($pdo, $el);
    }
}