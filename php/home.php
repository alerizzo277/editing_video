<?php
session_start();

include 'functions.php';
include 'classes/Person.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../js/functions.js"></script>
        <title>Home</title>
        <h1>Home</h1>
    </head>
    <body> 
        <a href="<?php echo VIDEOS_LIST?>">Lista dei video</a><br>
        <a href="<?php echo SESSIONS_LIST?>">Lista delle sessioni</a>
    </body>
</html>