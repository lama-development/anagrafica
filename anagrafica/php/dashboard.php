<?php
session_start();
// controllo se l'utente è loggato (se l'username è presente nella sessione)
if (isset($_SESSION['username'])) {
    // se lo è, mostro il messaggio di benvenuto
    echo "<h1>Dashboard</h1>";
    echo "<div class='welcome'>Benvenuto, " . $_SESSION['username'] . "</div>";
    // mostro il pulsante di logout, che è un form con un singolo pulsante che fa un post alla stessa pagina
    echo "<form method='post'><input type='submit' name='logout' value='Logout' style='background-color: red; padding: 8px 15px; border-radius: 10px; color: white; text-decoration: none; margin-left: 1rem'></form><br>";
    // controllo se è stato fatto un post alla pagina
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // se l'utente ha cliccato sul pulsante di logout, distruggo la sessione e lo reindirizzo alla pagina di login
        if (isset($_POST['logout'])) {
            session_destroy();
            header("Location: ../html/login.html");
        }
    }
} else {
    // se l'utente non è loggato, mostro un messaggio di errore e un pulsante per tornare alla pagina di login
    echo "<html style='text-align: center; font-family: Segoe UI; sans-serif;'>";
    echo "<br><h2>Esegui prima il login per accedere a questa pagina</h2><br>";
    echo "<a href='../html/login.html' style='background-color: #1b5efb; padding: 8px 25px; border-radius: 10px; color: white; text-decoration: none'>Torna al login</a>";
    echo "</html>";
    exit;
}
?>

<html>

<head>
    <title>Anagrafica - Dashboard</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../html/styles.css" />
</head>

<body>
    <div class="grid">
        <form method="post" action=''>
            <div class="card">
                <div class="container">
                    <div class="title">Aggiungi utente</div>
                    <p>Nome<br><input type="text" name="nome" required></p>
                    <p>Cognome<br><input type="text" name="cognome" required></p>
                    <p>Sesso<br>
                        <input type="radio" name="sesso" id="maschio" value="M" required>
                        <label for="maschio">Maschio</label><br>
                        <input type="radio" name="sesso" id="femmina" value="F">
                        <label for="femmina">Femmina</label>
                    </p>
                    <p>Data di nascita<br><input type="date" name="data_n" required></p>
                    <p>Comune di residenza<br><input type="text" name="comune" maxlength='2' onkeyup='this.value = this.value.toUpperCase();' required></p>
                    <p>Numero di telefono<br><input type="number" name="n_telefono" required></p>
                    <input type="submit" name="aggiungi" value="Aggiungi">
                </div>
            </div>
        </form>
        <form method="post" action=''>
            <div class="card">
                <div class="container">
                    <div class="title">Cancella utente</div>
                    <p>Nome<br><input type="text" name="nome" required></p>
                    <p>Cognome<br><input type="text" name="cognome" required></p>
                    <input type="submit" name="cancella" value="Cancella">
                </div>
            </div>
        </form>
        <form method="post" action=''>
            <div class="card">
                <div class="container">
                    <div class="title">Modifica utente</div>
                    <p>Numero di telefono<br><input type="number" name="vecchio_telefono" required></p>
                    <p>Nuovo numero di telefono<br><input type="number" name="nuovo_telefono" required></p>
                    <input type="submit" name="modifica_telefono" value="Modifica">
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="grid">
        <form method="post" action="print.php">
            <div class="card">
                <div class="container">
                    <div class="title">Cerca utente</div>
                    <p>Nome<br><input type="text" name="nome" required></p>
                    <p>Cognome<br><input type="text" name="cognome" required></p>
                    <input type="submit" name="cerca" value="Cerca">
                </div>
            </div>
        </form>
        <form method="post" action="print.php">
            <div class="card">
                <div class="container">
                    <div class="title">Stampa utenti</div>
                    <p><label>Filtra per sesso<br>
                            <select name="sesso">
                                <option value="Tutti">Tutti</option>
                                <option value="M">Maschi</option>
                                <option value="F">Femmine</option>
                            </select>
                        </label></p>
                    <input type="submit" name="stampa_sesso" value="Stampa">
                </div>
            </div>
        </form>
        <form method="post" action="print.php">
            <div class="card">
                <div class="container">
                    <div class="title">Stampa utenti</div>
                    <p><label>Filtra per età<br>
                            <input type="number" name="eta" max="120" required>
                        </label></p>
                    <input type="submit" name="stampa_eta" value="Stampa">
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="grid">
        <form method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="container">
                    <div class="title">Upload anagrafica</div>
                    <p>Solo file di estensione .txt</p>
                    <p><input type="file" name="new_file"></p>
                    <input type="submit" name="carica_file" value="Carica">
                </div>
            </div>
        </form>
    </div>
</body>

</html>

<?php

// path del file    
$file = "../database.txt";
// apro il file in modalità append+ (https://i.imgur.com/eTXeOKp.png)
$fp = fopen($file, 'a+');
// ottengo tutti gli utenti in un array di stringhe (ognuna delle quali è un utente)
$users = file_get_contents($file);
$users = explode("\n", $users);
// rimuovo l'ultimo elemento dell'array (che è vuoto)
unset($users[count($users) - 1]);

// aggiungi utente
if (isset($_POST["aggiungi"])) {
    $duplicate = false;
    // ottengo i dati dell'utente dal form
    $name = trim($_POST['nome']);
    $surname = trim($_POST['cognome']);
    $sex = trim($_POST['sesso']);
    $date = trim($_POST['data_n']);
    $city = trim($_POST['comune']);
    $phone = trim($_POST['n_telefono']);
    // creo la stringa da scrivere nel file
    $new_user = $name . ";" . $surname . ";" . $sex . ";" . $date . ";" . $city . ";" . $phone . "\n";
    // controllo se il file è stato aperto correttamente
    if (!$fp) {
        echo "<script>alert('Errore nell'apertura del file');</script>";
        exit;
    } else {
        // se l'utente è già presente nel database, non lo aggiungo
        foreach ($users as $user) {
            $user = explode(";", $user);
            if ($user[0] == $name && $user[1] == $surname && $user[2] == $sex && $user[3] == $date && $user[4] == $city && $user[5] == $phone) {
                $duplicate = true;
                break;
            }
        }
        if ($duplicate) echo "<script>alert('Utente già presente nel database');</script>";
        else {
            // ottengo il lock esclusivo sul file per evitare che altri processi scrivano contemporaneamente
            flock($fp, LOCK_EX);
            // scrivo sul file la stringa che contiene i dati dell'utente
            fwrite($fp, $new_user);
            echo "<script>alert('Utente registrato correttamente');</script>";
            // rilascio il lock sul file
            flock($fp, LOCK_UN);
        }
    }
}

if (isset($_POST["cancella"])) {
    $found = false;
    // ottengo i dati dell'utente dal form
    $name = trim($_POST['nome']);
    $surname = trim($_POST['cognome']);
    // creo un array di stringhe vuoto (che conterrà gli utenti che non voglio cancellare)
    $new_users = array();
    foreach ($users as $user) {
        $user = explode(";", $user);
        // se l'utente non è quello che voglio cancellare, lo aggiungo nel nuovo array
        if (strtolower($user[0]) != strtolower($name) || strtolower($user[1]) != strtolower($surname)) {
            array_push($new_users, $user[0] . ";" . $user[1] . ";" . $user[2] . ";" . $user[3] . ";" . $user[4] . ";" . $user[5] . "\n");
        } else $found = true;
    }
    if (!$found) echo "<script>alert('Utente non trovato');</script>";
    else {
        // se l'utente è stato trovato, sovrascrivo il file con il nuovo array
        // implode() converte l'array in una stringa
        $new_users = implode($new_users);
        file_put_contents($file, $new_users);
        echo "<script>alert('Utente cancellato correttamente');</script>";
    }
}

// modifica il numero di telefono
if (isset($_POST["modifica_telefono"])) {
    $found = false;
    // ottengo i dati dell'utente dal form
    $old_phone = trim($_POST['vecchio_telefono']);
    $new_phone = trim($_POST['nuovo_telefono']);
    // creo un array di stringhe vuoto (che conterrà gli utenti che non voglio modificare)
    $new_users = array();
    foreach ($users as $user) {
        $user = explode(";", $user);
        // se l'utente non è quello che voglio modificare, lo aggiungo nel nuovo array
        if ($user[5] != $old_phone) {
            array_push($new_users, $user[0] . ";" . $user[1] . ";" . $user[2] . ";" . $user[3] . ";" . $user[4] . ";" . $user[5] . "\n");
        } else {
            // se l'utente è quello che voglio modificare, lo aggiungo nel nuovo array con il nuovo numero di telefono
            $found = true;
            array_push($new_users, $user[0] . ";" . $user[1] . ";" . $user[2] . ";" . $user[3] . ";" . $user[4] . ";" . $new_phone . "\n");
        }
    }
    if (!$found) echo "<script>alert('Telefono non trovato');</script>";
    else {
        // se il telefono è stato trovato, sovrascrivo il file con il nuovo array
        $new_users = implode("", $new_users);
        file_put_contents($file, $new_users);
        echo "<script>alert('Numero di telefono modificato correttamente');</script>";
    }
}

// upload del file
if (isset($_POST["carica_file"])) {
    // ottengo il nome del file che ho caricato
    $file_name = $_FILES['new_file']['name'];
    // suddivido il nome del file dall'estensione e lo piazzo in un array di stringhe --> ['database', 'txt']
    $array = explode('.', $file_name);
    // ottengo l'estensione del file, che è l'ultimo elemento dell'array
    $extension = end($array);

    // controllo se l'estensione del file è valida
    if ($extension != "txt") {
        echo "<script>alert('Estensione non valida;</script>";
    } else {
        // ottengo il percoso temporaneo del file che ho caricato sul server
        $file_tmp = $_FILES['new_file']['tmp_name'];
        // sposto il file dal percorso temporaneo al percorso definitivo
        move_uploaded_file($file_tmp, $file);
        echo "<script>alert('File caricato correttamente');</script>";
    }
}

?>