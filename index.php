<?php
//serve per lo sviluppo del sito, andrà poi sostituito con il verp login.php


/*
in questa pagina vengono settate una cosa fondamentale per il funzionamento dell'editing:
- la persona loggata in SESSION come istanza della classse 'Person'. L'istanza verrà serializzata e ad ogni utilizzo de-serializzata
*/

session_start();

include "php/db_connection.php";
include "php/functions.php";
include "php/classes/Person.php";

$pdo = get_connection();

//dopo che l'uetnte si è loggato estraggo dal db la persona e la salvo serializzata in session

if(!isset($_SESSION["person"])){
    $person = getPersonaFromEmail($pdo, "vincenzo.italiano@gmail.com");
    $_SESSION["person"] = serialize($person);
}
else{
    header("Location: php/" . VIDEOS_LIST);
    //header("Location: php/home.php");
}
?>

<link rel="stylesheet" href="css/style.css" method="post">
<form action="<?php echo "php/".VIDEOS_LIST ?>">
    <fieldset>
        <legend>Test Login form</legend>
        <label>Email</label>
        <input type="email" value="email@gmail.com" readonly><br>
        <label>Password</label>
        <input type="password" value="password" readonly><br>
        <input type="submit">
    </fieldset>
</form>