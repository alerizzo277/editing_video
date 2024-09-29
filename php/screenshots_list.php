<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Screen.php';
include 'classes/Video.php';
include 'classes/Person.php';

include 'head.php';

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
    <title>Tutti gli screenshots</title>
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
    <h4 class="m-1">Tutti gli screenshots</h4>

    <div class="container mt-5">
        <form action="screen_manager.php?operation=multiple_screen_delete" method="post">
            <table class="table table-hover">
                <tr>
                    <th>Selezione</th>
                    <th>Immagine</th>
                    <th>Nome</th>
                    <th>Descrizione</th>
                </tr>
                <?php
                $pdo = get_connection();

                try {
                    $screenahots = getScreenshotsFromVideo($pdo, $video->getPath());
                    foreach ($screenahots as $el) {
                        echo <<<END
                    <tr class='clickable-row'>
                        <td><input type="checkbox" id="{$el->getId()}" name="id[]" value="{$el->getId()}"></td>
                        <td data-href='screen_details.php?id={$el->getId()}'><img id="{$el->getId()}" src="../{$el->getPath()}" alt="img" width="128" height="96"></td>
                        <td data-href='screen_details.php?id={$el->getId()}'>{$el->getName()}</td>
                        <td data-href='screen_details.php?id={$el->getId()}'>{$el->getNote()}</td>
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