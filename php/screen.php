<?php
session_start();

require '../vendor/autoload.php';
require 'functions.php';
require 'db_connection.php';

$path_video = "../" . $_SESSION["path_video"];
$filename = $_SESSION["name_file_video"];

$timing_screen_string = $_POST["timing_video"];
$timing_screen = getIntTimingScreen($timing_screen_string);

//echo $path_video . "<br>";
//echo $filename . "<br>";

$ffmpeg = FFMpeg\FFMpeg::create();
$video = $ffmpeg->open($path_video);

$screen_name = generateScreenName($filename, $timing_screen_string);
//echo $screen_name . "<br>";


$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($timing_screen))
    ->save("../" . $screen_name);


$pdo = get_connection();
$query = 'INSERT INTO screenshots(locazione, video) VALUES (:locazione, :video)';
$statement = $pdo->prepare($query);
$statement->execute([
    ':locazione' => $screen_name,
    ':video' => $_SESSION["path_video"],
]);

header("Location: ../index.php?timing_screen=$timing_screen");

?>