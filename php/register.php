<html>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../html/styles.css" />

<body>
    <form action='' method="post">
        <div class="login-box">
            <h1>Registrati</h1>
            Utente<br><input type="text" name="username" required> <br><br>
            Password<br><input type="password" name="password" required> <br><br>
            <input type="submit" name="register" value="Registrati"><br>
            <div style="text-align: center;">
                <br><br>Hai già un account? <a href="../html/login.html">Accedi</a>
            </div>
        </div>
    </form>
</body>

</html>

<?php
if (isset($_POST['register'])) {
    include '../utenti.inc';
    $duplicate = false;
    // controllo se l'username esiste già nell'array degli utenti
    foreach ($utenti as $key => $value) {
        if ($_POST['username'] == $key) {
            $duplicate = true;
            break;
        }
    }

    if ($duplicate) echo "<script>alert('Username già utilizzato')</script>";
    else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        // array che contiene l'username e la password del nuovo utente registrato
        $new_array = array($username => $password);
        // unisco l'array degli utenti già esistente con quello nuovo
        $utenti += $new_array;

        // sovrascrivo l'array degli utenti presente nel file utenti.inc con quello aggiornato
        $file = fopen("../utenti.inc", "w");
        fwrite($file, "<?php\n\$utenti = array(");
        foreach ($utenti as $key => $value) {
            // se l'elemento è l'ultimo scrivo solo la coppia chiave-valore senza la virgola
            if ($key == array_key_last($utenti)) fwrite($file, "'{$key}' => '{$value}'");
            // altrimenti scrivo la coppia chiave-valore seguita da una virgola (perchè ci sarà poi un'altra coppia chiave-valore)
            else fwrite($file, "'{$key}' => '{$value}', ");
        }
        fwrite($file, ");\n?>");
        fclose($file);

        header('Location: ../html/login.html');
    }
}
?>