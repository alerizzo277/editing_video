<?php

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
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/functions.js"></script>
    <title>Editing Video</title>
</head>

<body class="h-100 bg-light">
    <nav class="navbar navbar-dark bg-primary">
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
                        <div class="col">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                        </div>
                        <div class="col">
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" value="email@gmail.com">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                        </div>
                        <div class="col">
                            <input type="password" class="form-control" id="password" placeholder="Password" value="password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <input type="submit" class="btn btn-secondary">
                        </div>
                    </div>
                </form>
        </div>
    </div>
</body>

</html>