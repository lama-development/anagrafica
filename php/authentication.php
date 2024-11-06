<html style='text-align: center'><link rel="stylesheet" type="text/css" href="../html/styles.css"></html>

<?php
include '../utenti.inc';
$auth = false;

// controllo se sono stati inseriti username e password nella pagina di login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
} else {
    echo "<h1>Utente non autorizzato</h1>";
    echo "<p>Esegui il login per accedere a questa pagina</p><br>";
    echo "<a href='../html/index.html' class='login-btn'>Login</a>";
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
if ($auth) header("Location: dashboard.php");
else {
    echo "<br><h2>Le credenziali sono errate</h2><br>";
    echo "<a href='../html/index.html' class='login-btn'>Ripeti login</a>";
}
?>