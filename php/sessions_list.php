<?php
session_start();

include 'functions.php';
include 'db_connection.php';
include 'classes/Person.php';
include 'classes/Video.php';
include 'classes/Session.php';
include 'classes/Reservation.php';
include 'classes/Team.php';
include 'classes/Training.php';
include 'classes/Game.php';

include 'head.php';

$pdo = get_connection();
$sessions = getSessionsFromEmail($pdo, $person->getEmail());
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../js/functions.js"></script>
        <title>Tutte le sessioni</title>
        <h1>Tutte le sessioni</h1>
    </head>
    <body>
        <a class="button" href="../index.php">Home</a><br>
        <table class="paleBlueRows">
            <tr>
                <th>Sessione</th>
                <th>Squadra</th>
                <th>Sport</th>
                <th>Evento</th>
                <th>Data e Ora inizio</th>
                <th>Data e Ora fine</th>
            </tr>
            <?php
                foreach($sessions as $el){
                    $reservation = getReservationFromId($pdo, $el->getId());
                    $team = getTeamFromId($pdo, $reservation->getTeam());
                    $match = getMatchFromId($pdo, $reservation->getId());
                    $event = ($match != null) ? "Partita" : "Allenamento";
                    $link = SESSION . "?id=" . $el->getId();
                    echo <<< END
                        <tr class='clickable-row'>
                            <td data-href='$link'>{$el->getId()}</td>
                            <td data-href='$link'>{$team->getName()}</td>
                            <td data-href='$link'>{$team->getSport()}</td>
                            <td data-href='$link'>$event</td>
                            <td data-href='$link'>{$el->getStartDateTime()}</td>
                            <td data-href='$link'>{$el->getEndDateTime()}</td>
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



<?php

/*
SELECT
    SR.id,
    SR.autore,
    SR.data_ora_inizio,
    SR.data_ora_fine,
    P.nota,
    P.id_squadra
FROM
    sessioni_registrazione SR
INNER JOIN prenotazioni P ON
    SR.prenotazione = P.id
*/

?>