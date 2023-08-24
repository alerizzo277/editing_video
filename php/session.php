<?php
session_start();

include '../vendor/autoload.php';
include "db_connection.php";
include "functions.php";
include "classes/Person.php";
include "classes/Video.php";

include 'head.php';

$id_session = intval($_GET["id"]);
$pdo = get_connection();
$videos = getVideosFromSession($pdo, $person->getEmail(), $id_session);
$cameras = getCamerasFromSession($pdo, $id_session);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../js/functions.js"></script>
        <title>Sessione</title>
        <h1>Tutti i video della sessione</h1>
    </head>

    <body>
        <a class="button" href="../index.php">Home</a><br>

        <div>
            <?php
                foreach($cameras as $el){
                    echo "<button onclick=\"showVideoFromCamera('camera_$el')\">Telecamera $el</button>\n";
                }
            ?>
            <button onclick="showVideoFromCamera('session_list')">Tutti i video</button>
        </div>

        <?php
            foreach($cameras as $cam){
                echo <<< END

                <div id='camera_{$cam}' hidden>
                <table class='paleBlueRows'>
                    <tr>Telecamera $cam</tr>
                    <tr>
                        <th>Miniatura</th>
                        <th>Telecamera</th>
                        <th>Nome</th>
                        <th>Note</th>
                    </tr>\n
                END;
                foreach($videos as $el){
                    if ($el->getCamera() == $cam){
                        if(file_exists("../".$el->getPath())){
                            $thumb = getVideoThumbnails($el->getPath());
                        }
                        $link = VIDEO_MANAGER . "?operation=select_video&id={$el->getId()}";
                        echo <<< END
                            <tr class='clickable-row'>
                                <td data-href='$link'><img src="$thumb" alt="thumb {$el->getName()}" width="128" height="96"></td>
                                <td data-href='$link'>{$el->getCamera()}</td>
                                <td data-href='$link'>{$el->getName()}</td>
                                <td data-href='$link'>{$el->getNote()}</td>
                            </tr>
                        END;
                    }
                }
                echo "\n</table>\n</div>\n";
            }
        ?>

      <div id="session_list">
            <table class="paleBlueRows">
                <tr>Tutti i video</tr>
                <tr>
                    <th>Miniatura</th>
                    <th>Telecamera</th>
                    <th>Nome</th>
                    <th>Note</th>
                </tr>
                <?php
                    foreach($videos as $el){
                        if(file_exists("../".$el->getPath())){
                            $thumb = getVideoThumbnails($el->getPath());
                        }
                        $link = VIDEO_MANAGER . "?operation=select_video&id={$el->getId()}";
                        echo <<< END
                        \n<tr class='clickable-row'>
                            <td data-href='$link'><img src="$thumb" alt="thumb {$el->getName()}" width="128" height="96"></td>
                            <td data-href='$link'>{$el->getCamera()}</td>
                            <td data-href='$link'>{$el->getName()}</td>
                            <td data-href='$link'>{$el->getNote()}</td>
                        </tr>\n
                        END;
                    }
                ?>
            </table>
        </div>
    </body>
</html>

<script>
    jQuery(document).ready(function($) {
        $(".clickable-row td").click(function() {
            window.location = $(this).data("href");
        });
    });

    function showVideoFromCamera(camera){
        console.log(camera);
        let el = document.getElementById(camera);
        console.log(el);
        if (el.hidden){
            el.hidden = false;
        }
        else{
            el.hidden = true;
        }
    }
</script>