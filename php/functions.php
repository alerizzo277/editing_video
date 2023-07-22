<?php
/**
 * converte il timing del video in un array di interi
 * @param string $timing_screen La stringa è nel formato 00:00:000 (minuti:secondi:millesimi di secondi)
 * @return int il numero di secondi relativo al timing idicato 
 */
function getIntTimingScreen($timing_screen){

    $vet_timing = array();  
    $tok = strtok($timing_screen, ":");
    while ($tok !== false) {
        array_push($vet_timing, intval($tok));
        $tok = strtok(":");
    }

    $ris = $vet_timing[0] * 60 + $vet_timing[1] + $vet_timing[2] / 1000; //secondi (sommando anche i minuti)

    return $ris;
}

/**
 * genera il nome del file immagine dello screenshot
 * @param string $filename Il nome del file del video "nomefile.estenzione"
 * @param string $timing_screen La stringa è nel formato 00:00:000 (minuti:secondi:millesimi di secondi)
 * @param string $screen_folder La cartella dove vengono salvati gli screen, di defalut "screen/"
 * @param string $image_format Formato dell'immagine in cui salvare l'immagine, di default jpg
 * @return string la path completa, formattata, di dove salvare l'immagine
 */
function generateScreenName($filename, $timing_screen_string, $screen_folder = "screen/", $image_format = "jpg"){
    $name_video = strtok($filename,'.');
    $timing = "";
    $tok = strtok($timing_screen_string, ":");
    while ($tok !== false) {
        $timing .= $tok;
        $tok = strtok(":");
    }
    $screen_name = $screen_folder . "frame_{$name_video}_{$timing}." . $image_format;
    
    return $screen_name;
}