<?php
session_start();

include '../vendor/autoload.php';
include 'functions.php';
include 'db_connection.php';
include 'classes/Video.php';


$pdo = get_connection();

if (isset($_POST["start_timing_trim"]) && isset($_POST["end_timing_trim"])){
    $start = $_POST["start_timing_trim"];
    $end = $_POST["end_timing_trim"];

    $start_number = getIntTimingScreen($start);
    $end_number = getIntTimingScreen($end);

    $start = str_replace(":", "", $start);
    $end = str_replace(":", "", $end);
    $filename = getFilenameNoExtention($_SESSION["name_file_video"]);
    $clip_name = "clip_$filename"."_$start"."_$end.mp4";

    getClip($pdo, $start_number, $end_number, $clip_name);

    header("Location: ./clip.php?clip=$clip_name");
}


/**
 * @param float $start Il minutaggio iniziale per estrarre clip
 * @param float $end Il minutaggio finale per estrarre clip
 * @param string $clip_name nome della clip, inclusa di estensione
 **/
function getClip($pdo, $start, $end, $clip_name){
    $clip_path = "video/$clip_name";
    try{
        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open("../{$_SESSION["path_video"]}");
        $clip = $video->clip(FFMpeg\Coordinate\TimeCode::fromSeconds($start), FFMpeg\Coordinate\TimeCode::fromSeconds($end-$start));
        $clip->save(new FFMpeg\Format\Video\X264(), "../$clip_path");
    } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}


    //$autore = $_SESSION["email_user"];
    $autore = "vincenzo.italiano@gmail.com";
    $video = new Video($clip_path, getFilenameNoExtention($clip_name), "Clip del video{$_SESSION["path_video"]}", $autore);
    return insertNewClip($pdo, $video, $_SESSION["path_video"]);
}