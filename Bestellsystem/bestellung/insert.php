<?php
// Fehlermeldungen aktivieren (zum Debuggen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verbindung zur Datenbank herstellen
require dirname(__DIR__) . '/connect/connect.php';

$stmt = $pdo->prepare('SELECT * FROM `kunden`');
$stmt->execute();
$result_kunden = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $id_kunde = $_POST['id_kunde'];
    $datum = $_POST['datum'];
    

    $stmt = $pdo->prepare("INSERT INTO `bestellung` (`id`, `id_kunde`, `datum`) VALUES (:id, :id_kunde, :datum)");
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':id_kunde', $id_kunde);
    $stmt->bindValue(':datum', $datum);
    $stmt->execute();
    
    header('location:bestellung.php');
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neue Bestellung hinzufügen</title>
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
        <h1>Neue Bestellung hinzufügen</h1>
        <form action="" method="POST">
            <label for="ID">ID</label>  
            <input type="text" id="id" name="id" name="id" required>  

            <label for="id_kunde" class="form-label">Kunde</label>
                <select class="form-control" id="id_kunde" name="id_kunde" required>
                    <option value="">Bitte wählen</option>
                    <?php foreach ($result_kunden as $result): ?>
                    <option value="<?php echo $result['id'] ?>"><?php echo $result['nachname'] . " " . $result['vorname'] ?></option>
                    <?php endforeach; ?>
                </select>

            <label for="datum">Datum:</label>
            <input type="date" id="datum" name="datum" required>

            <button type="submit">Bestellung hinzufügen</button>
        </form>
        <a href="bestellung.php" class="back-button">Zurück zur Übersicht</a>
    </div>
</body>
</html>