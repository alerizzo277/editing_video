<?php
session_start();

include 'functions.php';
include 'db_connection.php';
include 'classes/Mark.php';

$pdo = get_connection();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js"></script>
    <title>Dettagli Segnaposto</title>
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
        <h4>Dettagli Segnaposto</h4>
        <svg onclick="history.back()" style="zoom: 2;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
        </svg>
    </div>
    <div class="container">
        <?php
        if (isset($_GET["id"])) {
            $mark = getMarkFromId($pdo, $_GET["id"]);
            echo <<<END
                    <div id="mark_details_edit">
                        <form action="mark_manager.php?operation=update_mark&id={$mark->getId()}" method="post">
                            <fieldset>
                                <legend>Dettagli Segnaposto</legend>
                                <div class="form-group">
                                    <label for="timing_mark">Timing:</label>
                                    <input type="text" class="form-control" name="timing_mark" id="timing_mark" value="{$mark->getTiming()}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="mark_name">Nome:</label>
                                    <input type="text" class="form-control" name="mark_name" id="mark_name" value="{$mark->getName()}">
                                </div>
                                <div class="form-group">
                                    <label for="mark_note">Descrizione:</label>
                                    <textarea class="form-control" id="mark_note" name="mark_note" rows="2" cols="30" style="resize: none;">{$mark->getNote()}</textarea>
                                </div>
                                <div class="mt-1">
                                    <button type="submit" class="btn btn-primary">Salva</button>
                                    <button type="submit" class="btn btn-danger" formaction="mark_manager.php?operation=delete_mark&id={$mark->getId()}">Elimina</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
        END;
        } else {
            echo "<p>ERRORE: Segnaposto non trovato</p>";
        }
        ?>
    </div>
</body>

</html>