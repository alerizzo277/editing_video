<?php
session_start();

include 'php/db_connection.php';
include 'php/functions.php';
include 'php/classes/Mark.php';
include 'php/classes/Screen.php';
include 'php/classes/Video.php';

$_SESSION["email_user"] = "vincenzo.italiano@gmail.com";
$_SESSION["path_video"] = "video/video.mp4";
$_SESSION["name_file_video"] = "video.mp4";
$_SESSION["screen_html"] = "";
$filename = strtok($_SESSION["name_file_video"], '.');
$pdo = get_connection();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/functions.js"></script>
    <title>Editing video</title>
    <h1>Editing video</h1>
</head>

<body>
    <video id="<?php echo $filename ?>" controls muted autoplay>
        <source src="<?php echo $_SESSION["path_video"] ?>" type="video/mp4">
    </video>
    <form action="php/screen_manager.php?operation=get_screen" method="post">
        <input type="text" name="timing_video" id="timing_video" readonly>
        <input type="button" id="mark" onclick="segnaposto()" value="Aggiungi Segnaposto">
        <input type="submit" value="Screen">
    </form>

    <br><a 
    <?php
        $video = getVideoFromPath($pdo, $_SESSION["path_video"]);
        if($video != null){echo "href=\"php/clip.php?id={$video->getId()}\"";}
    ?>
    >Estrai Clip</a><br>

    <br><a href="php/clips_list.php">Clips</a><br>

    <br><a href="php/marks_list.php">Segnaposti</a>

    <div id="mark_details" hidden>
        <form action="php/mark_manager.php?operation=new_mark" method="post">
            <fieldset>
                <legend>Segnaposto</legend>
                
                <label for="timing_mark">Timing:</label>
                <input type="text" name="timing_mark" id="timing_mark" readonly><br>

                <label for="mark_name">Nome:</label>
                <input type="text" name="mark_name" id="mark_name"><br>

                <label for="mark_name">Descrizione:</label>
                <textarea id="mark_note" name="mark_note" rows="2" cols="30"></textarea>

                <input type="submit" value="Salva" onclick="document.getElementById('mark_details').hidden = true">
            </fieldset>
        </form>
    </div>

    <div id="marks">
        <table id="list_marks" class="paleBlueRows">
            <tr>
                <th>Minutaggio</th>
                <th>Nome</th>
            </tr>
                <?php
                    $marks = getMarksFromVideo($pdo, $_SESSION["path_video"]);
                    try{
                        if ($marks != null){    
                            foreach ($marks as $el){
                                $name = ($el->getName() == null) ? "-" : $el->getName();
                                echo <<< END
                                <div id="{$el->getId()}">
                                    <tr>
                                        <td>{$el->getTiming()}</td>
                                        <td>$name</td>
                                        <td><a href="php/mark_details.php?id={$el->getId()}">Dettagli</a></td>
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

    <br><a href="php/screenshots_list.php">Screenshots</a>

    <div id="screen_area" class="grid-container">
        <?php
            $screenshots = getScreenshotsFromVideo($pdo, $_SESSION["path_video"]);
            try{
                foreach ($screenshots as $el){
                    $img_name = substr($el->getPath(), strpos($el->getPath(), "/") + 1);
                    echo <<< END
                        <div class="grid-item">
                            <a href="php/screen_details.php?id={$el->getId()}">
                                <img id="{$el->getId()}" src="{$el->getPath()}" alt="$img_name" width="426" height="240">
                            </a>
                            <br>
                            <a href="php/screen_details.php?id={$el->getId()}&timing_video="">
                    END;
                    echo ($el->getName() == null) ? $img_name : $el->getName();
                    echo "</a>\n\t</div>\n";
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
        var url = "php/mark_manager.php?timing=" + $('#timing_video').val();
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