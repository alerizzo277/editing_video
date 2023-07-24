<?php
session_start();

include 'functions.php';
include 'db_connection.php';
include 'classes/Mark.php';

$pdo = get_connection();


if(isset($_GET["timing"])){
    echo $_GET["timing"];
}

if(isset($_GET["operation"])){
	switch ($_GET["operation"]){
		case "new_mark":
			echo "new mark<br>";
			if(isset($_POST["timing_mark"])){
				$timing = $_POST["timing_mark"];
				$timing = timing_format_db($timing);//fromato corretto per db
				$name = ($_POST["mark_name"] == "") ? null : $_POST["mark_name"];
				$note = ($_POST["mark_note"] == "") ? null : $_POST["mark_note"];
				$video = $_SESSION["path_video"];
				$mark = new Mark($timing, $name, $note, $video);
				echo insertNewMark($pdo, $mark);
				echo "<br>{$mark->getID()}";
			}
			break;	
		case "update_mark":
			if(isset($_POST["timing_mark"])){
				$timing = $_POST["timing_mark"];
				$timing = timing_format_db($timing);//fromato corretto per db
				$name = ($_POST["mark_name"] == "") ? null : $_POST["mark_name"];
				$note = ($_POST["mark_note"] == "") ? null : $_POST["mark_note"];
				$video = $_SESSION["path_video"];
				$id = $_GET["id"];
				$mark = new Mark($timing, $name, $note, $video, $id);
				echo updateMarkFromId($pdo, $mark);
				echo "<br>{$mark->getId()}";
			}
			break;
		case "delete_mark":
			if(isset($_GET["id"])){
				$id = $_GET["id"];
				deleteMarkFromId($pdo, $id);
			}
			break;
	}
	
	$timing = getIntTimingScreen($_POST["timing_mark"]);
	header("Location: ../index.php?timing_screen=$timing");
}