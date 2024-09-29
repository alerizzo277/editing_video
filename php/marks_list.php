<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Mark.php';
include 'classes/Video.php';
include 'classes/Person.php';

//if(isPageRefreshed()){
    setPreviusPage();
//}

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
        <title>Tutti i segnaposti</title>
        
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
        <!-- DA SISTEMARE <a href="<?php echo getPreviusPage()?>" class="button">Indierto</a><br>-->
        <h4 class="m-1">Tutti i segnaposti</h4>
        <div class="container">
            <form action="mark_manager.php?operation=multiple_mark_delete" method="post">
                <table class="table table-hover">
                    <tr>
                        <th>Selezione</th>
                        <th>Minutaggio</th>
                        <th>Nome</th>
                        <th>Descrizione</th>
                    </tr>
<?php
$pdo = get_connection();

try{               
    $marks = getMarksFromVideo($pdo, $video->getPath());
    foreach($marks as $el){
        echo <<<END
                    <tr class='clickable-row'>
                        <td><input type="checkbox" id="{$el->getId()}" name="id[]" value="{$el->getId()}"></td>
                        <td data-href='mark_details.php?id={$el->getId()}'>{$el->getTiming()}</td>
                        <td data-href='mark_details.php?id={$el->getId()}'>{$el->getName()}</td>
                        <td data-href='mark_details.php?id={$el->getId()}'>{$el->getNote()}</td>
                    </tr>\n
        END;
    }
} catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
?>
                </table>
                <input class="btn btn-danger" type="submit" value="Elimina">
                <!-- da disabilitare con js il pulsante elimina, se nessuna chkbox è selezionata-->
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