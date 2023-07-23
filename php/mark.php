<?php
session_start();

require 'functions.php';
require 'db_connection.php';

$pdo = get_connection();

if(isset($_GET["timing"])){
    echo $_GET["timing"];
}

if(isset($_POST["timing_mark"])){
	$timing = $_POST["timing_mark"]; echo $timing;
	$name = ($_POST["mark_name"] == "") ? null : $_POST["mark_name"];
	$note = ($_POST["mark_note"] == "") ? null : $_POST["mark_note"];
	$video = $_SESSION["path_video"];

	$query = 'INSERT INTO segnaposti(minutaggio, video, nome, note) VALUES (:minutaggio, :video, :nome, :note)';
	$statement = $pdo->prepare($query);
	/*$statement->execute([
		':minutaggio' => $timing,
		':video' => $video,
		':nome' => $name,
		':note' => $note,
	]);*/
}