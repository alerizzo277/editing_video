<?php
session_start();

require 'functions.php';
require 'db_connection.php';

$conn = get_connection();

$ris = $conn->query("SELECT * FROM persone");
//var_dump($ris);

//devo studiare con calma come funziona la connessione e come si fanno le query

$timing = $_POST["timing_mark"];
$name = $_POST["mark_name"];
$note = $_POST["mark_note"];

if(isset($_GET["timing"])){
    echo $_GET["timing"];
}
