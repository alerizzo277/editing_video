<?php
session_start();
$filename = strtok($_SESSION["name_file_video"], '.');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js"></script>
    <title>Clip video</title>
    <h1>Clip video</h1>
</head>

<body>
    <a href="../index.php">Home</a><br>
    <video id="<?php echo $filename ?>" controls muted autoplay>
            <source src="<?php echo "../" . $_SESSION["path_video"] ?>" type="video/mp4">
    </video>
    <form action="" method="post">
        <input type="text" name="timing_video" id="timing_video" readonly><br>
        <label>Timing inizio clip: </label><input type="text" name="start_timing_trim" id="start_timing_trim" readonly>
        <input type="button" onclick="getStartTimingTrim()" value="Prendi tempo iniziale"><br>
        <label>Timing fine clip: </label><input type="text" name="end_timing_trim" id="end_timing_trim" readonly>
        <input type="button" onclick="getEndTimingTrim()" value="Prendi tempo finale"><br>
    </form>
</body>

<div id="snackbar" class>Il tempo iniziale deve essere maggiore al tempo finale</div>

<script>
    //timing video a tempo reale
    var video = $('#<?php echo $filename ?>');
    video.bind("timeupdate", function() {

        var stime = video[0].currentTime;
        stime = stime.toString();
        stime = stime.split(".").pop();
        stime = stime.substr(0, 3);

        $('#timing_video').val(fromSeconds(video[0].currentTime) + ':' + stime);
    });
</script>