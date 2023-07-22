<?php
session_start();
$_SESSION["path_video"] = "video/video.mp4";
$_SESSION["name_file_video"] = "video.mp4";
$file = "video.mp4";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <script>
        function segnaposto() {
            const xhttp = new XMLHttpRequest();
            var url = "mark.php?timing=" + $('#timing_video').val();
            xhttp.open("GET", url, true);
            xhttp.onreadystatechange = function() {
                if (this.readyState = 4 && this.status === 200) {
                    let timing = xhttp.responseText;
                    let id_timing = timing.replaceAll(":","");
                    if (document.getElementById(id_timing) === null && timing != "" && id_timing != ""){
                        $('#list_marks')[0].innerHTML += "<li id=\"" + id_timing + "\">" + timing + "</li>";
                    }
                }
            }
            xhttp.send();
        }
    </script>
    <title>Editing video</title>
    <h1>Editing video</h1>
</head>

<body>
    <video id="<?php echo strtok($file, '.') ?>" controls muted autoplay>
        <source src="<?php echo "video/" . $file ?>" type="video/mp4">
    </video>
    <form action="screen.php" method="post">
        <input type="text" name="timing_video" id="timing_video" readonly>
        <input type="button" id="mark" onclick="segnaposto()" value="Aggiungi Segnaposto">
        <input type="submit" value="Screen">
    </form>

    <div id="marks">
        <ul id="list_marks">
        </ul>
    </div>

</body>

</html>

<script>
    var video = $('#<?php echo strtok($file, '.') ?>');
    video.bind("timeupdate", function() {
        var stime = video[0].currentTime;
        stime = stime.toString();
        stime = stime.split(".").pop();
        stime = stime.substr(0, 3);

        $('#timing_video').val(fromSeconds(video[0].currentTime) + ':' + stime);
    });
</script>