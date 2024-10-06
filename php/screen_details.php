<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Screen.php';
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
    <title>Dettagli Screen</title>
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
        <h4>Dettagli Screen</h4>
        <svg onclick="history.back()" style="zoom: 2;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
        </svg>
    </div>

    <div class="container">

    <?php
    $pdo = get_connection();

    if (isset($_GET["id"])) {
        $screen = getScreenfromId($pdo, $_GET["id"]);
        if ($screen != null) {
            echo <<< END
            <div class="screen_details">
                <img id="{$screen->getId()}" src="../{$screen->getPath()}" alt="img" class="rounded">
                <form action="screen_manager.php?operation=update_screen&id={$screen->getId()}" method="post">
                <fieldset>
                    <legend>Dettagli Screenshot</legend>
                    <div class="form-group">
                        <label for="screen_name">Nome:</label>
                        <input class="form-control" type="text" name="screen_name" id="screen_name" value="{$screen->getName()}"><br>
                    </div>
                    <div class="form-group">
                        <label for="screen_note">Descrizione:</label>
                        <textarea class="form-control" id="screen_note" name="screen_note" rows="2" cols="30" style="resize: none;">{$screen->getNote()}</textarea>
                    </div>
                    <div class="my-1">
                        <input type="submit" class="btn btn-primary" value="Salva">
                        <input type="submit" class="btn btn-danger" value="Elmina" formaction="screen_manager.php?operation=delete_screen&id={$screen->getId()}">
                        <a href="../{$screen->getPath()}" class="btn btn-secondary" download>Scarica</a>
                    </div>
        END;
            if (isset($_GET["updated"])) {
                echo "<div id=\"snackbar\" class=\"show\">Screenshot modificato correttamente</div>";
            }
        } else {
            if (!isset($_GET["screen_deleted"])) {
                echo "<p id=\"screen_mess\">Screenshot non trovato</p>";
            } else {
                if ($_GET["screen_deleted"] == "true") {
                    //echo "screen eliminato correttamente<br>";
                    //echo getPreviusPage();
                    header("Location: " . getPreviusPage());
                }
            }
        }
    }
    ?>
</body>

</html>

<script>
    if (findGetParameter("updated") != null) {
        window.onload = showSnackbar();
    }
</script>