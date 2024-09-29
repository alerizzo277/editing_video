<?php
session_start();

include 'functions.php';
include 'classes/Person.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../js/functions.js"></script>
        <title>Home</title>
    </head>
    <nav class="navbar navbar-light bg-primary">
        <a class="navbar-brand">
            <img src="../assets/icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Editing Video
        </a>
    </nav>
    <body> 
        <a href="<?php echo VIDEOS_LIST?>">Lista dei video</a><br>
        <a href="<?php echo SESSIONS_LIST?>">Lista delle sessioni</a>
    </body>
</html>