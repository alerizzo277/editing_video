<a class="button">Home</a>
<p>Errore:</p>
<p>
    <?php
        switch (http_response_code()){
            case 404:
                echo "Risorsa non trovata";
                break;
            default:
                echo "Generico";
                break;
        }
    ?>
</p>