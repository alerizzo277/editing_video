<?php
session_start();

include 'functions.php';
include 'db_connection.php';
include 'classes/Mark.php';

$pdo = get_connection();
?>

<h1>Dettagli Segnaposto</h1>

<?php

if(isset($_GET["id"])){
    $mark = getMarkFromId($pdo, $_GET["id"]);
    echo <<<END
    <div id="mark_details_edit">
        <form action="mark_manager.php?operation=update_mark&id={$mark->getId()}" method="post">
            <fieldset>
                <legend>Dettagli Segnaposto</legend>
                
                <label for="timing_mark">Timing:</label>
                <input type="text" name="timing_mark" id="timing_mark" value="{$mark->getTiming()}" readonly><br>

                <label for="mark_name">Nome:</label>
                <input type="text" name="mark_name" id="mark_name" value="{$mark->getName()}"><br>

                <label for="mark_name">Descrizione:</label>
                <textarea id="mark_note" name="mark_note" rows="2" cols="30">{$mark->getNote()}</textarea>

                <input type="submit" value="Salva">
                <input type="submit" value="Elmina" formaction="mark_manager.php?operation=delete_mark&id={$mark->getId()}">
            </fieldset>
        </form>
    </div>
    END;
}
else{
    echo "<p>ERRORE: Segnaposto non trovato</p>";
}

?>