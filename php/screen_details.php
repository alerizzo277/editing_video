<?php
session_start();

include 'db_connection.php';
include 'functions.php';
include 'classes/Screen.php';

$pdo = get_connection();

if(isset($_GET["id"])){
    $screen = getScreenfromId($pdo, $_GET["id"]);
    var_dump($screen);
}