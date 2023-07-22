<?php
session_start();

require 'functions.php';


if(isset($_GET["timing"])){
    echo $_GET["timing"];
}

if(isset($_POST["timing_mark"])){
    $timing = $_POST["timing_mark"];
    $name = $_POST["mark_name"];
    $note = $_POST["mark_note"];

    if ($name != ""){
        if($note != ""){

        }
    }
    echo $timing; echo "<br>";

    var_dump($name); echo "<br>";
    var_dump($note); echo "<br>";
}