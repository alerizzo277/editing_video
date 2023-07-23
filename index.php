<?php
session_start();
$_SESSION["path_video"] = "video/video.mp4";
$_SESSION["name_file_video"] = "video.mp4";
$_SESSION["screen_html"] = "";
$filename = strtok($_SESSION["name_file_video"], '.');

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
    <form action="php/screen.php" method="post">
        <input type="text" name="timing_video" id="timing_video" readonly>
        <input type="button" id="mark" onclick="segnaposto()" value="Aggiungi Segnaposto">
        <input type="submit" value="Screen">
    </form>


    <div id="mark_details" hidden>
        <form action="php/mark.php" method="post">
            <fieldset>
                <legend>Segnaposto</legend>
                <label for="timing_mark">Timing:</label>
                <input type="text" name="timing_mark" id="timing_mark" readonly><br>

                <label for="mark_name">Titolo:</label>
                <input type="text" name="mark_name" id="mark_name"><br>

                <label for="mark_name">Descrizione:</label>
                <textarea id="mark_note" name="mark_note" rows="2" cols="30"></textarea>

                <input type="submit" value="Salva" onclick="document.getElementById('mark_details').hidden = true">
            </fieldset>
        </form>
    </div>

    <div id="marks">
        <ul id="list_marks">
        </ul>
    </div>

    <div id="screen_area">
        <?php
        foreach (glob('screen/*.*') as $img_path) {
            $imgname = substr($img_path, strpos($img_path, "/") + 1);
            echo "<div class=\"mini_screen\" id=\"$imgname\">";
            echo "\t<img id=\"$imgname\" src=\"$img_path\" alt=\"$imgname\" width=\"426\" height=\"240\"><br>";
            echo "\t<label>$imgname</label>";
            echo "</div>";
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
        var url = "php/mark.php?timing=" + $('#timing_video').val();
        xhttp.open("GET", url, true);
        xhttp.onreadystatechange = function() {
            if (this.readyState = 4 && this.status === 200) {
                let timing = xhttp.responseText;
                let id_timing = timing.replaceAll(":", "");
                if (document.getElementById(id_timing) === null && timing != "" && id_timing != "") {
                    //$('#list_marks')[0].innerHTML += "<li id=\"" + id_timing + "\">" + timing + "</li>";
                    $('#timing_mark')[0].value = timing;
                    $('#mark_details')[0].hidden = false;
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

</script>