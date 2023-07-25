<?php
session_start();

include '../vendor/autoload.php';
include 'functions.php';
include 'db_connection.php';
include 'classes/Screen.php';

$path_video = "../" . $_SESSION["path_video"];
$filename = $_SESSION["name_file_video"];

$pdo = get_connection();

if(isset($_GET["operation"])){
    switch ($_GET["operation"]){
        case "get_screen":
            try{                
                $timing_screen_string = $_POST["timing_video"];
                $timing_screen = getIntTimingScreen($timing_screen_string);
                getScreen($path_video, $filename, $timing_screen, $timing_screen_string, $pdo);
            } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
            header("Location: ../index.php?timing_screen=$timing_screen");
            break;
        case "update_screen":
            try{  
                $ok = "true";
                if(!updateScreen($pdo)){
                    $ok = "false";
                }
                header("Location: ./screen_details.php?id={$_GET["id"]}&updated=$ok");
            } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
            break;
        case "delete_screen":
            if(isset($_GET["id"])){
                deleteScreen($pdo, $_GET["id"]);
            }
            header("Location: ./screen_details.php?id={$_GET["id"]}&screen_deleted=true");
            break;

    }
}

//funzioni locali, solo per questo file
function getScreen($path_video, $filename, $timing_screen, $timing_screen_string, $pdo){
    $ffmpeg = FFMpeg\FFMpeg::create();
    $video = $ffmpeg->open($path_video);
    $screen_name = generateScreenName($filename, $timing_screen_string);
    $video
        ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($timing_screen))
        ->save("../" . $screen_name);

    $query = 'INSERT INTO screenshots(locazione, video) VALUES (:locazione, :video)';
    $statement = $pdo->prepare($query);
    $statement->execute([
        ':locazione' => $screen_name,
        ':video' => $_SESSION["path_video"],
    ]);
}

function updateScreen($pdo){
    $id = $_GET["id"];
    $name = ($_POST["screen_name"] == "") ? null : $_POST["screen_name"];
    $note = ($_POST["screen_note"] == "") ? null : $_POST["screen_note"];
    $screen = new Screen(null, $name, $note, $id, null);
    return updateScreenFromId($pdo, $screen);
}

function deleteScreen($pdo, $id){
    deleteScreenFromId($pdo, $id);
}