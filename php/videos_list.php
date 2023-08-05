<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Mark.php';
include 'classes/Screen.php';
include 'classes/Video.php';
include 'classes/Person.php';

$pdo = get_connection();

if(isset($_SESSION["person"])){//se la person a è salvata, significa che è loggato
    $person = unserialize($_SESSION["person"]);
}
else {
    header("Location: " . INDEX);
}

setPreviusPage();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../js/functions.js"></script>
        <title>Scelta Video</title>
        <h1>Scelta video</h1>
    </head>
    <body>
        <form action="<?php echo VIDEO_MANAGER;?>?operation=multiple_video_delete" method="post" onsubmit="confirm('Sicuro di eliminare i video selezionati?')">
            <table class="paleBlueRows">
                <tr>
                    <th>Scelta</th>
                    <th>Nome</th>
                </tr>
                <?php
                try{               
                    //$videos = getVideosFromUser($pdo, "vincenzo.italiano@gmail.com");
                    $videos = getVideosFromUser($pdo, $person->getEmail());
                    foreach($videos as $el){
                        myVarDump($el);
                        echo <<<END
                                    <tr class='clickable-row'>
                                        <td><input type="checkbox" id="{$el->getId()}" name="id[]" value="{$el->getId()}"></td>        
                        END;
                        echo "\n<td data-href='".VIDEO_MANAGER."?operation=select_video&id={$el->getId()}'>{$el->getName()}</td></tr>\n";
                    }
                } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
                ?>
            </table>
            <input type="submit" value="Elimina">
        </form>
    </body>
</html>

<script>
    jQuery(document).ready(function($) {
    $(".clickable-row td:not(:first-child)").click(function() {
        window.location = $(this).data("href");
    });
});
</script>