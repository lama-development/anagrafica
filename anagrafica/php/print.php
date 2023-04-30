<html style="padding: 1rem; font-family: 'Segoe UI', sans-serif;"></html>

<?php
$file = "../database.txt";
$found = false;
$count = 0;

// ottengo tutti gli utenti in un array di stringhe (ognuna delle quali è un utente)
$users = file_get_contents($file);
$users = explode("\n", $users);
// rimuovo l'ultimo elemento dell'array (che è vuoto)
unset($users[count($users) - 1]);

// stampa utenti dato il nome e cognome
if (isset($_POST["cerca"])) {
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);
    echo "<h1>Cerca utente</h1>Hai cercato: <b>" . $nome . " " . $cognome . "</b><br>";
    foreach ($users as $user) {
        // divido la stringa in un array di stringhe (ognuna delle quali è un dato dell'utente)
        $user = explode(";", $user);
        // controllo se il nome e cognome inseriti sono uguali a quelli dell'utente corrente
        if (strtolower($user[0]) == strtolower($nome) && strtolower($user[1]) == strtolower($cognome)) {
            $count++;
            $found = true;
            echo "<br><b>Nome: </b>" . $user[0] . "<br><b>Cognome: </b>" . $user[1] . "<br><b>Sesso: </b>" . $user[2] . "<br><b>Data di nascita: </b>" . $user[3] . "<br><b>Comune di residenza: </b>" . $user[4] . "<br><b>Telefono: </b>" . $user[5] . "<br>";
        }
    }
}

// stampa utenti dato il sesso
if (isset($_POST['stampa_sesso'])) {
    $sex = $_POST['sesso'];
    echo "<h1>Stampa utenti per sesso</h1>Hai cercato: <b>" . $sex . "</b><br>";
    // se il sesso è "Tutti" stampo tutti gli utenti
    if ($sex == "Tutti") {
        foreach ($users as $user) {
            $user = explode(";", $user);
            $count++;
            $found = true;
            echo "<br><b>Nome: </b>" . $user[0] . "<br><b>Cognome: </b>" . $user[1] . "<br><b>Sesso: </b>" . $user[2] . "<br><b>Data di nascita: </b>" . $user[3] . "<br><b>Comune di residenza: </b>" . $user[4] . "<br><b>Telefono: </b>" . $user[5] . "<br>";
        }
    } else {
        foreach ($users as $user) {
            $user = explode(";", $user);
            // controllo se il sesso inserito è uguale a quello dell'utente corrente
            if ($user[2] == $sex) {
                $count++;
                $found = true;
                echo "<br><b>Nome: </b>" . $user[0] . "<br><b>Cognome: </b>" . $user[1] . "<br><b>Sesso: </b>" . $user[2] . "<br><b>Data di nascita: </b>" . $user[3] . "<br><b>Comune di residenza: </b>" . $user[4] . "<br><b>Telefono: </b>" . $user[5] . "<br>";
            }
        }
    }
}

// stampa utenti data l'età
if (isset($_POST['stampa_eta'])) {
    $age_query = $_POST['eta'];
    echo "<h1>Stampa utenti per età</h1>Hai cercato: <b>" . $age_query . "</b> anni<br>";
    foreach ($users as $user) {
        // divido la stringa in un array di stringhe (ognuna delle quali è un dato dell'utente)
        $user = explode(";", $user);
        // prendo la data di nascita
        $birth=$user[3];
        // prendo la data corrente
        $current = date("Y-m-d");
        // calcolo la differenza tra le due date
        $diff = date_diff(date_create($birth), date_create($current));
        // controllo se l'anno calcolato è uguale a quello di ricerca
        if ($diff->format('%y') == $age_query) {
            $count++;
            echo "<br><b>Nome: </b>" . $user[0] . "<br><b>Cognome: </b>" . $user[1] . "<br><b>Sesso: </b>" . $user[2] . "<br><b>Data di nascita: </b>" . $user[3] . "<br><b>Comune di residenza: </b>" . $user[4] . "<br><b>Telefono: </b>" . $user[5] . "<br>";
            $found = true;
        }
    }
}

// print results
if (!$found) echo "<br><b style='color: red'>Nessun utente trovato</b><br>";
else echo "<br><b style='color: green'>[{$count} record]</b><br>";
echo "<br><br><a href='dashboard.php' style='background-color: #1b5efb; padding: 8px 25px; border-radius: 10px; color: white; text-decoration: none'>Torna indietro</a>";

?>
