<?php
session_start();

include "db_connection.php";
include "functions.php";
include "classes/Person.php";
include "classes/Video.php";

include 'head.php';

//$id_session = $_GET["session"];
$id_session = 1;
$pdo = get_connection();
$videos = getVideosFromSession($pdo, $person->getEmail(), $id_session);
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
        <table class="paleBlueRows">
            <tr>
                <th>Nome</th>
                <th>Note</th>
            </tr>
            <?php
                foreach($videos as $el){
                    $link = VIDEO_MANAGER . "?operation=select_video&id={$el->getId()}";
                    echo <<< END
                    \n<tr class='clickable-row'>
                        <td data-href='$link'>{$el->getName()}</td>
                        <td data-href='$link'>{$el->getNote()}</td>
                    </tr>\n
                    END;
                }
            ?>
        </table>
    </body>
</html>

<script>
    jQuery(document).ready(function($) {
    $(".clickable-row td").click(function() {
        window.location = $(this).data("href");
    });
});
</script>