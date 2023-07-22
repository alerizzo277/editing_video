<?php
session_start();

require 'vendor/autoload.php';
require 'functions.php';

$timing_screen_string = $_POST["timing_video"];
$timing_screen = getIntTimingScreen($timing_screen_string);
$path_video = $_SESSION["path_video"];
$filename = $_SESSION["name_file_video"];


$ffmpeg = FFMpeg\FFMpeg::create();
$video = $ffmpeg->open($path_video);


$screen_name = generateScreenName($filename, $timing_screen_string);

$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($timing_screen))
    ->save("$screen_name");

?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $screen_name?></title>
    </head>
    <body>
        <img src="<?php echo $screen_name?>" alt="Qualcosa non va">
    </body>
</html>