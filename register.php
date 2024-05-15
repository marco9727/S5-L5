<?php
session_start();

// Connessione al database
require_once './config.php';
require_once './Database.php';
require_once './User.php';


$db = new Database();

// Inizializzazione variabili e messaggi di errore
$username = $password = $confirm_password = $role = "";
$username_err = $password_err = $confirm_password_err = $role_err = "";

// Elaborazione dei dati del modulo quando viene inviato il modulo
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Controlla se il nome utente è stato inserito
    if (empty(trim($_POST["username"]))) {
        $username_err = "Inserisci il nome utente.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Controlla se la password è stata inserita
    if (empty(trim($_POST["password"]))) {
        $password_err = "Inserisci la password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Conferma la password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Conferma la password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Le password non corrispondono.";
        }
    }

    // Controlla se il ruolo è stato selezionato
    if (empty(trim($_POST["role"]))) {
        $role_err = "Seleziona il ruolo.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Verifica se ci sono errori di input prima di inserire nel database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err)) {

        // Prepara una dichiarazione di inserimento
        $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";

        if ($stmt = $db->getConnection()->prepare($sql)) {

            // Imposta i parametri
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Cifra la password
            $param_role = $role;

            // Esegue la dichiarazione preparata
            $stmt->bindParam(':username', $param_username);
            $stmt->bindParam(':password', $param_password);
            $stmt->bindParam(':role', $param_role);

            if ($stmt->execute()) {
                // Reindirizza alla pagina di login
                header("location: login.php");
                exit();
            } else {
                echo "Qualcosa è andato storto. Si prega di riprovare più tardi.";
            }

            // Chiude la dichiarazione
            unset($stmt);
        }
    }

    // Chiude la connessione al database
    unset($db);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .wrapper {
            width: 360px;
            padding: 20px;
            margin: auto;
        }
        /* Aggiunta di stili per allineare gli input e le etichette */
        .wrapper label {
            display: block;
            margin-bottom: 5px;
        }
        .wrapper input[type="text"],
        .wrapper input[type="password"],
        .wrapper select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .wrapper input[type="submit"],
        .wrapper input[type="reset"] {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .wrapper input[type="submit"]:hover,
        .wrapper input[type="reset"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Registrazione</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Nome Utente</label>
                <input type="text" name="username" value="<?php echo $username; ?>" required>
                <span><?php echo $username_err; ?></span>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" value="<?php echo $password; ?>" required>
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <label>Conferma Password</label>
                <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>" required>
                <span><?php echo $confirm_password_err; ?></span>
            </div>
            <div>
                <label>Ruolo</label>
                <select name="role" required>
                    <option value="">Seleziona Ruolo</option>
                    <option value="user">Utente</option>
                    <option value="admin">Amministratore</option>
                </select>
                <span><?php echo $role_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Registrati">
                <input type="reset" value="Reset">
            </div>
            <p>Hai già un account? <a href="login.php">Accedi qui</a>.</p>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>