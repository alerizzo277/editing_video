<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Mark.php';
include 'classes/Screen.php';
include 'classes/Video.php';
include 'classes/Person.php';

$pdo = get_connection();

setPreviusPage();

$video = null;
$filename = "";

if(isset($_SESSION["video"])){
    $video = unserialize($_SESSION["video"]);
    $filename = basename($video->getPath(), ".mp4");
} elseif (isset($_GET["video_deleted"])) {
    echo "<p class=\"message\">Video Eliminato correttamente</p>";
} else{
    http_response_code(404);
    include('error.php');
    die();
}

if(isset($_SESSION["person"])){
    $person = unserialize($_SESSION["person"]);
}
else{
    header("Location: ../".INDEX);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js"></script>
    <title>Dettagli Video</title>
    <h1>Dettagli Video</h1>
</head>

<body>
    <a class="button" href="../index.php">Home</a><br>
    <video id="<?php echo $filename ?>" controls muted autoplay>
        <source src="<?php if($video != null){echo "../{$video->getPath()}";} ?>" type="video/mp4">
    </video>

    <form action="<?php echo VIDEO_MANAGER?>?operation=update_video" method="post" onsubmit="confirm('Confermi?')">
        <fieldset>
            <legend>Modifica Nome e Descrizione</legend>
            <!--<input type="text" name="timing_video" id="timing_video" readonly>-->
            <label>Nome: </label>
            <input type="text" name="video_name" id="video_name" value="<?php if($video != null){echo $video->getName();} ?>"><br>
            <label>Descrizione</label>
            <textarea type="text" name="video_note" id="video_note"><?php if($video != null){echo $video->getNote();} ?></textarea>
            <input type="submit" value="Salva">
            <input type="submit" value="Elimina Video" formaction="<?php echo VIDEO_MANAGER?>?operation=delete_video">
        </fieldset>
    </form>

    <br>
    <div>
        <a href="editing_video.php" class="button">Editing Video</a>
        <a href="clips_list.php" class="button">Gestione clip</a>
        <a href="marks_list.php" class="button">Gestione segnaposti</a>
        <a href="screenshots_list.php" class="button">Gestione screenshots</a>
        <a href="../<?php echo $video->getPath() ?>" class="button" download>Scarica</a>
    </div>

    <button type="button" onclick="showMarks()" id="show_marks">Mostra i segnaposti</button>
    <button type="button" onclick="showScreenArea()" id="show_screen_area">Mostra gli screenshot</button>
    
    <div id="marks" hidden>
        <table id="list_marks" class="paleBlueRows">
            <tr>
                <th>Minutaggio</th>
                <th>Nome</th>
            </tr>
                <?php
                    $marks = getMarksFromVideo($pdo, $video->getPath());
                    try{
                        if ($marks != null){    
                            foreach ($marks as $el){
                                $name = ($el->getName() == null) ? "-" : $el->getName();
                                echo <<< END
                                <div id="{$el->getId()}">
                                    <tr>
                                        <td>{$el->getTiming()}</td>
                                        <td>$name</td>
                                        <td><a href="mark_details.php?id={$el->getId()}">Dettagli</a></td>
                                END;
                                $timing = timing_format_from_db_to_int($el->getTiming());
                                echo "<td><a href=\"javascript:goToTiming(document.getElementById('{$filename}'), '$timing')\">Vai al Timing</a></td>\n\t</tr>\n\t</div>\n";
                            }
                        }
                    } catch (Exception $e) {
                        echo 'Eccezione: ',  $e->getMessage(), "\n";
                    }
                ?>
        </table>       
    </div>

    <div id="screen_area" class="grid-container" hidden>
        <?php
            $screenshots = getScreenshotsFromVideo($pdo, $video->getPath());
            try{
                foreach ($screenshots as $el){
                    $img_name = ($el->getName() == null) ? basename($el->getPath(), ".jpg") : $el->getName();
                    echo <<< END
                        <div class="grid-item">
                            <a href="screen_details.php?id={$el->getId()}">
                                <img id="{$el->getId()}" src="../{$el->getPath()}" alt="$img_name" width="426" height="240">
                            </a>
                            <br>
                            <a href="screen_details.php?id={$el->getId()}">$img_name</a>
                        </div>\n
                    END;
                }
            } catch (Exception $e) {
                echo 'Eccezione: ',  $e->getMessage(), "\n";
            }
        ?>
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
        var url = "mark_manager.php?timing=" + $('#timing_video').val();
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
            document.getElementById("<?php echo $filename?>").currentTime = timing;
        }
    }

    function goToTiming(video, timing){
        video.currentTime = timing;
        video.pause();
    }

</script>