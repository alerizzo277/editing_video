<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Video.php';
include 'classes/Person.php';

include 'head.php';

$filename = basename($video->getName(), ".mp4");
$pdo = get_connection();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js"></script>
    <title>Clip video</title>
</head>
<nav class="navbar navbar-dark bg-primary navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <img src="../assets/icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Editing Video
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo VIDEOS_LIST ?>">Video</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<body>

    <div class="m-4">
        <h4>Clip</h4>
        <svg onclick="history.back()" style="zoom: 2;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
        </svg>
    </div>

    <div class="container">
        <video id="<?php echo $video->getId() ?>" controls muted autoplay>
            <source src="<?php echo "../{$video->getPath()}"; ?>" type="video/mp4">
        </video>

        <form action="clip_manager.php?operation=new_clip" method="post">
            <div class="form-group">
                <label for="timing_video">Timing video:</label>
                <input type="text" class="form-control" name="timing_video" id="timing_video" readonly>
            </div>
            <div class="form-group">
                <label for="start_timing_trim">Timing inizio clip:</label>
                <input type="text" class="form-control" name="start_timing_trim" id="start_timing_trim" readonly>
                <button type="button" class="btn btn-primary ml-2 my-1" onclick="getStartTimingTrim()">Prendi tempo iniziale</button>
            </div>
            <div class="form-group">
                <label for="end_timing_trim">Timing fine clip:</label>
                <input type="text" class="form-control" name="end_timing_trim" id="end_timing_trim" readonly>
                <button type="button" class="btn btn-primary ml-2 my-1" onclick="getEndTimingTrim()">Prendi tempo finale</button>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success" id="trim_video" value="EstraiClip" disabled>EstraiClip</button>
            </div>
        </form>

        <div id="clip" hidden>
            <legend>Clip Estratta</legend>
            <video controls muted width="240" height="160">
                <?php
                if (isset($_GET["clip"])) {
                    $id = intval($_GET["clip"]);
                    $clip = getVideoFromId($pdo, $id);
                    echo "<source src=\"../{$clip->getPath()}\" type=\"video/mp4\">";
                }
                ?>
            </video>
        </div>

        <div id="snackbar" hidden="true" class="mt-2 alert alert-danger">Il tempo iniziale deve essere maggiore al tempo finale</div>
    </div>
</body>

<script>
    //timing video a tempo reale
    var video = $('#<?php echo $video->getId() ?>');
    video.bind("timeupdate", function() {

        var stime = video[0].currentTime;
        stime = stime.toString();
        stime = stime.split(".").pop();
        stime = stime.substr(0, 3);

        $('#timing_video').val(fromSeconds(video[0].currentTime) + ':' + stime);
    });


    var clip = findGetParameter("clip");
    if (clip != null) {
        document.getElementById("clip").hidden = false;
    }
</script>