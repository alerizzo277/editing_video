<?php

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
    //header("Location: php/" . VIDEOS_LIST);
    header("Location: php/home.php");
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js"></script>
    <title>Editing Video</title>
</head>

<body class="h-100 bg-light">
    <nav class="navbar navbar-light bg-primary">
        <a class="navbar-brand">
            <img src="./assets/icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Editing Video
        </a>
    </nav>
    <div class="container d-flex justify-content-center mt-5 h-100">
            <div class="col-3">
                <img src="./assets/icon.png" width="280" height="280" class="d-inline-block align-top" alt="">
            </div>
            <div class="col-9">
                <form class="mt-5 p-4 bg-white border rounded w-50 container" action="<?php echo "php/home.php"?>">
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                        </div>
                        <div class="col-6">
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" value="email@gmail.com">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                        </div>
                        <div class="col-6">
                            <input type="password" class="form-control" id="password" placeholder="Password" value="password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <input type="submit" class="btn btn-secondary">
                        </div>
                    </div>
                </form>
        </div>
    </div>
</body>

</html>