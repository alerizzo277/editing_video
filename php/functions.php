<?php

/**
 * converte il timing del video indicato dall'appostio box nel browser in un intero
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
 * @param string $timing_screen La stringa è nel formato 00:00:000 (minuti:secondi:millesimi di secondo)
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

/**
 * @param string $timing Il timing è formattato minuti:secondi:millesimi di secondo
 * @return string Il fromato richiesto dal db: hh:mm:ss.ssss
*/
function timing_format_db($timing){
    $hh = 0; $mm = 0;
    $db_format = "";

    $tok = strtok($timing, ":");

    //estraggo i minuti
    $mm = intval($tok);
    
    //calcolo il numero di ore
    if($mm >= 60){
        $hh = intdiv($mm, 60);
        $mm -= $hh * 60;//calcolo i min rimanenti (es. 63 min sono 1h e 3 min)
    }

    //concateno le ore
    $db_format .= "$hh:";
    //concateno i minuti
    $db_format .= ($mm < 10) ? "0$mm:" : "$mm:"; // se i minuti sono < 10, aggiungo lo 0 (es. 3 minuti devo aggiungere 03, non solo 3)
    
    //concateno i secondi
    $tok = strtok(":");
    $db_format .= "$tok";   
    
    //concateno i millesimi di secondo
    $tok = strtok(":");
    $db_format .= ".$tok";
    
    return $db_format;
}

/**
 * Riceve in input la stringa che specifica il timing formattato secondo il db (hh:mm:ss.sss) in un intero minuti.secondi
 * @param string $timing La stringa che specifica il timing formattato secondo il db (hh:mm:ss.sss)
 * @return integer timing espresso in secondi.millisecondi
 */
function timing_format_from_db_to_int($timing){
    $ss = 0.0;

    //estraggo le ore e le converto in secondi
    $tok = strtok($timing, ":");
    $ss += intval($tok) * 60 * 60;

    //estraggo i minuti e li converto in secondi
    $tok = strtok(":");
    $ss += intval($tok) * 60;
    
    //estraggo e sommo i secondi
    $tok = strtok(".");
    $ss += (intval($tok));

    //estraggo e sommi i millisecondi
    $tok = strtok("");
    $ss += (intval($tok) / 1000);

    return $ss;
}

/**
 * Restituisce i mark salvati nel db come vettore di istanse della classe Mark
 * @param PDO La connessione al db
 * @return array Un array di istane della classe Mark, che rappresentano i mark salvati nel db
 */
function getMarks($pdo){
    $marks = array();
    $timing = "";
    $name = "";
    $note = "";

    $query = "SELECT id, minutaggio, nome, note FROM segnaposti";
    $statement = $pdo->query($query);
    $publishers = $statement->fetchAll(PDO::FETCH_ASSOC);
    if ($publishers) {
        foreach ($publishers as $publisher) {
            try{                
                $id = $publisher['id'];
                $timing = $publisher['minutaggio'];
                $name = $publisher['nome'];
                $note = $publisher['note'];
                $mark = new Mark($timing, $name, $note, null, $id);
                array_push($marks, $mark);
            } catch (Exception $e) {
                echo 'Eccezione: ',  $e->getMessage(), "\n";
            }
        }
    }

    return $marks;
}

/**
 * Restituisce un mark salvato nel db con l'id specificato
 * @param PDO La connessione al db
 * @param integer $id l'id che identifica il mark
 * @return Mark il mark cercato, come istanza della classe Mark
 */
function getMarkFromId($pdo, $id){
    $mark = null;
    $query = "SELECT * FROM segnaposti WHERE id={$_GET["id"]}";
    $statement = $pdo->query($query);
    $publishers = $statement->fetchAll(PDO::FETCH_ASSOC);
    if ($publishers) {
        foreach ($publishers as $publisher) {
            try{                
                $id = $publisher['id'];
                $timing = $publisher['minutaggio'];
                $name = $publisher['nome'];
                $note = $publisher['note'];
                $mark = new Mark($timing, $name, $note, null, $id);
            } catch (Exception $e) {
                echo 'Eccezione: ',  $e->getMessage(), "\n";
            }
        }
    }

    return $mark;
}

/**
 * Inserisce nel db una nuovo record nella tabella 'Segnaposti'
 * @param PDO La connessione al db
 * @param Mark $mark Istanza della classe Mark, che contiene i valori da inserie
 * @return bool true se l'inserimento ha successo, altrimenti false
 */
function insertNewMark($pdo, $mark){
    $ris = null;
    try{
        $query = 'INSERT INTO segnaposti(minutaggio, video, nome, note) VALUES (:minutaggio, :video, :nome, :note)';
        $statement = $pdo->prepare($query);
        $ris = $statement->execute([
            ':minutaggio' => $mark->getTiming(),
            ':video' => $mark->getPathVideo(),
            ':nome' => $mark->getName(),
            ':note' => $mark->getNote(),
        ]);
    } catch (Exception $e){}
    return $ris;
}

/**
 * Aggiorna un segnaposto nel db con l'id specificato nell'istanza della classe Mark
 * @param PDO La connessione al db
 * @param Mark $mark Istanza della classe Mark, che contiene i valori da aggiornare
 * @return bool true se l'aggiornamento ha successo, altrimenti false
 */
function updateMarkFromId($pdo, $mark){
    
   $query = "UPDATE segnaposti SET minutaggio=:minutaggio, nome=:nome, note=:note WHERE id=:id";
   $statement = $pdo->prepare($query);
   $ris = $statement->execute([
       ':minutaggio' => $mark->getTiming(),
       ':nome' => $mark->getName(),
       ':note' => $mark->getNote(),
       ':id' => $mark->getId(),
    ]);
    
   return $ris;
}