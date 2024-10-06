<?php
session_start();

include '../vendor/autoload.php';
include 'functions.php';
include 'db_connection.php';
include 'classes/Video.php';
include 'classes/Person.php';

include 'head.php';

$pdo = get_connection();

if(isset($_GET["operation"])){
    switch($_GET["operation"]){
        case "new_clip":
            if (isset($_POST["start_timing_trim"]) && isset($_POST["end_timing_trim"])){
                $clip = newClip($pdo, $video, $person);
                if(isset($clip)){
                    $clip = getVideoFromPath($pdo, $clip->getPath()); //mi serve estrarre l'id che il db ha assegnato alla nuova clip
                    header("Location: ". VIDEO_MANAGER ."?operation=select_video&id={$clip->getId()}");
                }
                else{header("Location: ". CLIP);}
            }
            break;  
        case "multiple_clip_delete":
                if(isset($_POST["id"])){
                    multipleDelete($pdo);
                }
                header("Location: ".CLIPS_LIST);
            break;
        default:
			echo "<p>Opzione non riconosciuta</p>";
			echo "<a href=\"../" . INDEX . "\">Home</a>";
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

    getClip($start_number, $end_number, $clip_name, $video);
    $clip = new Video(null, "storage_video/$clip_name", basename($clip_name, ".mp4"), "Clip del video{$video->getPath()}", $person->getEmail(), $video->getSession(), $video->getCamera());
    insertNewClip($pdo, $clip, $clip->getPath());

    return $clip;
}

/**
 * Estrae la clip dal video; la salva nella cartrella storage_video/
 */
function getClip($start, $end, $clip_name, $video){
    $clip_path = "../storage_video/$clip_name";
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