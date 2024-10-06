<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Mark.php';
include 'classes/Screen.php';
include 'classes/Video.php';
include 'classes/Person.php';

include 'head.php';

$filename = basename($video->getPath(), ".mp4");
$pdo = get_connection();
setPreviusPage();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js"></script>
    <title>Editing</title>
</head>

<nav class="navbar navbar-dark bg-primary navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <img src="../assets/icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Editing Video
        </a>
    </div>
</nav>

<body>
    <div class="m-4">
        <h4>Editing Video</h4>
        <svg onclick="history.back()" style="zoom: 2;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
        </svg>
    </div>
    <div class="container mt-5">
        <video id="<?php echo $filename ?>" controls muted autoplay>
            <source src="<?php echo "../{$video->getPath()}" ?>" type="video/mp4">
        </video>
        <form action="screen_manager.php?operation=get_screen" method="post">
            <div class="input-group">
                <input type="text" class="form-control" name="timing_video" id="timing_video" readonly>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="mark" onclick="segnaposto()">Aggiungi Segnaposto</button>
                    <input class="btn btn-primary" type="submit" value="Screen"></input>
                </div>
            </div>
        </form>

        <div class="btn-group my-1">
            <a class="btn btn-secondary" href="<?php if ($video != null) {
                                                    echo CLIP . "?id={$video->getId()}\"";
                                                } ?>" class="button">Vai a Estrai Clip</a>
            <a class="btn btn-secondary" href="clips_list.php" class="button">Gestione clip</a>
            <a class="btn btn-secondary" href="marks_list.php" class="button">Gestione segnaposti</a>
            <a class="btn btn-secondary" href="video_details.php?id=<?php if ($video != null) {
                                                                        echo "{$video->getId()}\"";
                                                                    } ?>" class="button">Dettagli Video</a>
            <a class="btn btn-secondary" href="../<?php echo $video->getPath() ?>" class="button" download>Scarica</a>
        </div>

        <div id="mark_details" hidden>
            <form action="mark_manager.php?operation=new_mark" method="post">
                <fieldset>
                    <legend>Segnaposto</legend>
                    <div class="form-group">
                        <label for="timing_mark">Timing:</label>
                        <input type="text" class="form-control" name="timing_mark" id="timing_mark" readonly>
                    </div>
                    <div class="form-group">
                        <label for="mark_name">Nome:</label>
                        <input type="text" class="form-control" name="mark_name" id="mark_name">
                    </div>
                    <div class="form-group">
                        <label for="mark_name">Descrizione:</label>
                        <textarea class="form-control" id="mark_note" name="mark_note" rows="2" cols="30"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="document.getElementById('mark_details').hidden = true">Salva</button>
                </fieldset>
            </form>
        </div>

        <div id="marks">
            <table id="list_marks" class="table table-striped">
                <tr>
                    <th>Minutaggio</th>
                    <th>Nome</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                $marks = getMarksFromVideo($pdo, $video->getPath());
                try {
                    if ($marks != null) {
                        foreach ($marks as $el) {
                            $name = ($el->getName() == null) ? "-" : $el->getName();
                            $timing = timing_format_from_db_to_int($el->getTiming());
                            echo <<< END
                                    <div id="{$el->getId()}">
                                        <tr>
                                            <td>{$el->getTiming()}</td>
                                            <td>$name</td>
                                            <td><a class="btn btn-primary" href="mark_details.php?id={$el->getId()}">Dettagli</a></td>
                                            <td><a class="btn btn-primary" href="javascript:goToTiming(document.getElementById('{$filename}'), '$timing')">Vai al Timing</a></td>
                                        </tr>
                                    </div>\n
                                    END;
                        }
                    }
                } catch (Exception $e) {
                    echo 'Eccezione: ',  $e->getMessage(), "\n";
                }
                ?>
            </table>
        </div>

        <a href="screenshots_list.php" class="btn btn-secondary">Gestione screenshots</a>

        <div id="snackbar" hidden="true" class="mt-2 alert alert-danger">Esiste già un segnaposto con quel minutaggio</div>

        <div id="screen_area" class="row my-1">
            <?php
            $screenshots = getScreenshotsFromVideo($pdo, $video->getPath());
            try {
                foreach ($screenshots as $el) {
                    $img_name = ($el->getName() == null) ? substr($el->getPath(), strpos($el->getPath(), "/") + 1) : $el->getName();
                    echo <<< END
                            <div class="col m-1 p-2 bg-light rounded">
                                <a href="screen_details.php?id={$el->getId()}">
                                    <img id="{$el->getId()}" src="../{$el->getPath()}" alt="$img_name" width="426" height="240" class="w-100 shadow-1-strong rounded mb-1">
                                    <p class="text-center">{$img_name}</p>
                                </a>
                            </div>
                        END;
                }
            } catch (Exception $e) {
                echo 'Eccezione: ',  $e->getMessage(), "\n";
            }
            ?>
        </div>
        
    </div>
</body>

</html>

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

    function segnaposto() {
        const xhttp = new XMLHttpRequest();
        var url = "mark_manager.php? timing=" + $('#timing_video').val();
        xhttp.open("GET", url, true);
        xhttp.onreadystatechange = function() {
            if (this.readyState = 4 && this.status === 200) {
                let timing = xhttp.responseText;
                if (timing != "") {
                    $('#timing_mark')[0].value = timing;
                    $('#mark_details')[0].hidden = false;
                    $('#<?php echo $filename ?>')[0].pause();
                }
            }
        }
        xhttp.send();
    }

    window.onload = function() {
        let timing = findGetParameter("timing_screen");
        if (timing != null) {
            timing = parseFloat(timing);
            document.getElementById("<?php echo $filename ?>").currentTime = timing;
        }

        let message = findGetParameter("message");
        if (message == "mark_exists") {
            showSnackbar();
        }
    }

    function goToTiming(video, timing) {
        video.currentTime = timing;
        video.pause();
    }
</script>