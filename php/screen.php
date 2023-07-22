<?php
session_start();

require '../vendor/autoload.php';
require 'functions.php';

$timing_screen_string = $_POST["timing_video"];
$timing_screen = getIntTimingScreen($timing_screen_string);
$path_video = "../" . $_SESSION["path_video"];
$filename = $_SESSION["name_file_video"];

echo $path_video . "<br>";
echo $filename . "<br>";

$ffmpeg = FFMpeg\FFMpeg::create();
$video = $ffmpeg->open($path_video);

$screen_name = "../" . generateScreenName($filename, $timing_screen_string);
echo $screen_name . "<br>";


    $video
        ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($timing_screen))
        ->save($screen_name);


header("Location: ../index.php?timing_screen=$timing_screen");

?>