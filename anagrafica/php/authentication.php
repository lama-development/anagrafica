<html style="text-align: center; font-family: 'Segoe UI', sans-serif;"></html>

<?php
include '../utenti.inc';
$auth = false;

// controllo se sono stati inseriti username e password nella pagina di login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
} else {
    echo "<br><h2>Esegui prima il login per accedere a questa pagina</h2><br>";
    echo "<a href='../html/login.html' style='background-color: #1b5efb; padding: 8px 25px; border-radius: 10px; color: white; text-decoration: none'>Torna al login</a>";
    exit;
}

// controllo se le credenziali inserite sono presenti nell'array utenti
foreach ($utenti as $key => $value) {
    if ($username == $key && $password == $value) {
        $auth = true;
        // creo la sessione e salvo l'username dell'utente autenticato nella sessione
        session_start();
        $_SESSION['username'] = $username;
    }
}

// se l'utente è autenticato lo reindirizzo alla pagina dashboard.php
if ($auth) {
    echo "<br><h2>Le credenziali sono corrette</h2><br>";
    header("Location: dashboard.php");
} else {
    echo "<br><h2>Le credenziali sono errate</h2><br>";
    echo "<a href='../html/login.html' style='background-color: #1b5efb; padding: 8px 25px; border-radius: 10px; color: white; text-decoration: none'>Ripeti login</a>";
}
?>