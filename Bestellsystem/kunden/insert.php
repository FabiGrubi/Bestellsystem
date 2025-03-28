<?php
// Fehlermeldungen aktivieren (zum Debuggen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verbindung zur Datenbank herstellen
require dirname(__DIR__) . '/connect/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $nachname = trim($_POST['nachname']);
    $vorname = trim($_POST['vorname']);
    $telefon = floatval($_POST['telefon']);
    $email = trim($_POST['email']);

    if (!empty($nachname) && ($vorname) && $telefon >= 0 && ($email)) {
        try {
            // Einfügen der Daten, id wird automatisch inkrementiert
            $stmt = $pdo->prepare("INSERT INTO kunden (id, nachname, vorname, telefon, email) VALUES (:id, :nachname, :vorname, :telefon, :email)");
            $stmt->bindValue(":id", $id);
            $stmt->bindValue(':nachname', $nachname, PDO::PARAM_STR);
            $stmt->bindValue(':vorname', $vorname, PDO::PARAM_STR);
            $stmt->bindValue(':telefon', $telefon, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            // Erfolgreiche Umleitung nach kunden.php
            header('Location: kunden.php?success=1');
            exit;
        } catch (PDOException $e) {
            die("Fehler beim Einfügen: " . $e->getMessage());
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Bitte alle Felder korrekt ausfüllen!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neuen Kunden hinzufügen</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #6200ea;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #6200ea;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #6200ea;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #6200ea;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-button:hover {
            color: #3700b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Neuen Kunden hinzufügen</h1>
        <form action="" method="POST">
            <label for="ID">ID</label>  
            <input type="text" id="id" name="id" name="id" required>  

            <label for="nachname">Nachname:</label>
            <input type="text" id="nachname" name="nachname" required>

            <label for="vorname">Vorname:</label>
            <input type="text" id="vorname" name="vorname" required>

            <label for="telefon">Telefon:</label>
            <input type="number" id="telefon" name="telefon" step="1" min="0" required>

            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Kunden hinzufügen</button>
        </form>
        <a href="kunden.php" class="back-button">Zurück zur Übersicht</a>
    </div>
</body>
</html>