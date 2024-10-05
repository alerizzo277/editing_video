<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Video.php';
include 'classes/Person.php';

include 'head.php';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js"></script>
    <title>Tutti le clip</title>
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
        <h4>Tutti le clip</h4>
        <svg onclick="history.back()" style="zoom: 2;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
        </svg>
    </div>

    <div class="container mt-5">
        <form action="clip_manager.php?operation=multiple_clip_delete" method="post">
            <table class="table table-striped">
                <tr>
                    <th>Selezione</th>
                    <th>Nome</th>
                    <th>Descrizione</th>
                </tr>

                <?php
                $pdo = get_connection();

                try {
                    $clips = getClipsFromVideo($pdo, $video->getPath());
                    foreach ($clips as $el) {
                        $link = "../" . VIDEO_MANAGER . "?operation=select_video&id={$el->getId()}";
                        echo <<<END
                    <tr class='clickable-row'>
                        <td><input type="checkbox" id="{$el->getId()}" name="id[]" value="{$el->getId()}"></td>
                        <td data-href='$link'>{$el->getName()}</td>
                        <td data-href='$link'>{$el->getNote()}</td>
                    </tr>\n
        END;
                    }
                } catch (Exception $e) {
                    echo 'Eccezione: ',  $e->getMessage(), "\n";
                }
                ?>
            </table>
            <input class="btn btn-danger" type="submit" value="Elimina">
        </form>
    </div>
</body>

</html>

<script>
    jQuery(document).ready(function($) {
        $(".clickable-row td:not(:first-child)").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>