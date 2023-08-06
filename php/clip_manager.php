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
    $person = unserialize($_SESSION["person"]);}
else{
    header("Location: ../".INDEX);
}

if(isset($_GET["operation"])){
    switch($_GET["operation"]){
        case "new_clip":
            if (isset($_POST["start_timing_trim"]) && isset($_POST["end_timing_trim"])){
                newClip($pdo, $video, $person);
                header("Location: ./clip.php?clip=$clip_name");
            }
            break;  
        case "multiple_clip_delete":
                if(isset($_POST["id"])){
                    multipleDelete($pdo);
                }
                header("Location: clips_list.php");
            break;
        default:
			echo "<p>Opzione non riconosciuta</p>";
			echo "<a href=\"../index.php\">Home</a>";
			break;
    }
}


function newClip($pdo, $video, $person) {
    $start = $_POST["start_timing_trim"];
    $end = $_POST["end_timing_trim"];
    $start_number = getIntTimingScreen($start);
    $end_number = getIntTimingScreen($end);

    $start = str_replace(":", "", $start);
    $end = str_replace(":", "", $end);

    $filename = basename($video->getPath(), ".mp4");
    $clip_name = "clip_$filename"."_$start"."_$end.mp4";

    getClip($pdo, $start_number, $end_number, $clip_name, $video);

    $video = new Video(null, "video/$clip_name", basename($clip_name, ".mp4"), "Clip del video{$_SESSION["path_video"]}", $person->getEmail(), $video->getSession());
    return insertNewClip($pdo, $video, $_SESSION["path_video"]);
}

/**
 * @param float $start Il minutaggio iniziale per estrarre clip
 * @param float $end Il minutaggio finale per estrarre clip
 * @param string $clip_name nome della clip, inclusa di estensione
 **/
function getClip($pdo, $start, $end, $clip_name, $video){
    $clip_path = "../video/$clip_name";
    try{
        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open("../{$video->getPath()}");
        $clip = $video->clip(FFMpeg\Coordinate\TimeCode::fromSeconds($start), FFMpeg\Coordinate\TimeCode::fromSeconds($end-$start));
        $clip->save(new FFMpeg\Format\Video\X264(), $clip_path);
    } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
}

function multipleDelete($pdo){
	foreach($_POST["id"] as $el){
        try{
            $video = getVideoFromId($pdo, $el);
            unlink("../{$video->getPath()}");
            deleteVideoFromId($pdo, $el);
        } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
	}
}