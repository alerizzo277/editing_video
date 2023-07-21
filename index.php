<?php
    $file = "video.mp4";
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <title>Editing video</title>
        <h1>Editing video</h1>
    </head>

    <body>
        <video id = "<?php echo strtok($file, '.')?>" controls muted autoplay>
            <source src="<?php echo $file?>" type="video/mp4">
        </video>
        <form action="screen.php" method="post">
            <input type="text" name="ttime" id="ttile">
            <input type="submit" value="Screen">
        </form>
    </body>

</html>

<script>
    function fromSeconds(seconds, showHours) {
        if(showHours) {
            var hours = Math.floor(seconds / 3600),
            seconds = seconds - hours * 3600;
        }
        var minutes = ("0" + Math.floor(seconds/60)).slice(-2);
        var seconds = ("0" + parseInt(seconds%60,10)).slice(-2);

        if(showHours) {
            var timestring = hours + ":" + minutes + ":" + seconds;
        } else {
            var timestring = minutes + ":" + seconds;
        }
        return timestring;
    }
    
    var video = $('#<?php echo strtok($file, '.')?>');

    video.bind("timeupdate", function () {
        var stime = video[0].currentTime;
        stime = stime.toString();
        stime = stime.split(".").pop();
        stime = stime.substr(0,3);

        $('#ttile').val(fromSeconds(video[0].currentTime)+'.'+stime);
    });
</script>