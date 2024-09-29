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
    header("Location: ../" . INDEX);
}

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
        <title>Video</title>
    </head>
    <nav class="navbar navbar-dark bg-primary navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img src="../assets/icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
                Editing Video
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo VIDEOS_LIST ?>">Video</a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" href="<?php echo SESSIONS_LIST ?>">Sessioni</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
    <body>
        <div class="container mt-5">
            <form action="<?php echo VIDEO_MANAGER;?>?operation=multiple_video_delete" method="post" onsubmit="confirm('Sicuro di eliminare i video selezionati?')">
                <table class="table table-hover">
                    <tr>
                        <th></th>
                        <th>Nome</th>
                    </tr>
                    <?php
                    try{               
                        $videos = getVideosFromUser($pdo, $person->getEmail());
                        foreach($videos as $el){
                            $link = VIDEO_MANAGER . "?operation=select_video&id={$el->getId()}";
                            echo <<<END
                                    <tr class='clickable-row'>
                                        <td data-href='$link'><input type="checkbox" id="{$el->getId()}" name="id[]" value="{$el->getId()}"></td>        
                                        <td data-href='$link'>{$el->getName()}</td>
                                    </tr>\n
                            END;
                        }
                    } catch (Exception $e) {echo 'Eccezione: ',  $e->getMessage(), "\n";}
                    ?>
                </table>
                <input type="submit" value="Elimina" class="btn btn-danger">
            </form>
        </div>
    </body>
</html>

<script>
    jQuery(document).ready(function($) {
        $(".clickable-row td").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>