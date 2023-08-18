<?php
if(isset($_SESSION["video"])){
    $video = unserialize($_SESSION["video"]);
}
else{
    header("Location: ../".INDEX);
}

if(isset($_SESSION["person"])){
    $person = unserialize($_SESSION["person"]);
}
else{
    header("Location: ../".INDEX);
}