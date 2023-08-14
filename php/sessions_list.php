<?php
session_start();

include 'functions.php';
include 'db_connection.php';
include 'classes/Person.php';
include 'classes/Video.php';
include 'classes/Session.php';

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
        <table class="paleBlueRows">
            <tr>
                <th>Sessione</th>
                <th>Data e Ora inizio</th>
                <th>Data e Ora fine</th>
            </tr>
            <?php
                foreach($sessions as $el){
                    echo <<< END
                        <tr>
                            <td>{$el->getId()}</td>
                            <td>{$el->getStartDateTime()}</td>
                            <td>{$el->getEndDateTime()}</td>
                        </tr>\n
                    END;
                }
            ?>
        </table>
    </body>
</html>

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