<?php
// Fehlermeldungen aktivieren (zum Debuggen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verbindung zur Datenbank herstellen
require dirname(__DIR__) . '/connect/connect.php';

$stmt = $pdo->prepare('SELECT * FROM `artikel`');
$stmt->execute();
$result_artikel = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM `bestellung`');
$stmt->execute();
$result_bestellung = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $id_artikel = filter_var($_POST['id_artikel'], FILTER_SANITIZE_NUMBER_INT);
    $id_bestellung = filter_var($_POST['id_bestellung'], FILTER_SANITIZE_NUMBER_INT);
    $menge = filter_var($_POST['menge'], FILTER_SANITIZE_NUMBER_INT);

    if ($id && $id_artikel && $id_bestellung && $menge) {
        try {
            $stmt = $pdo->prepare("INSERT INTO `bestelldetails` (`id`, `id_artikel`, `id_bestellung`, `menge`) VALUES (:id, :id_artikel, :id_bestellung, :menge)");
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':id_artikel', $id_artikel);
            $stmt->bindValue(':id_bestellung', $id_bestellung);
            $stmt->bindValue(':menge', $menge);
            $stmt->execute();

            header('location:bestelldetails.php');
            exit;
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neues Bestelldetail hinzufügen</title>
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
<body class="bg-light">
    <div class="container w-50">
        <h3 class="display-6 py-2 text-center text-dark">Bestelldetail Anlegen</h3>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="">
                <label for="id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id" name="id" required>

                <label for="id_artikel" class="form-label">Artikel</label>
                <select class="form-control" id="id_artikel" name="id_artikel" required>
                    <option value="">Bitte wählen</option>
                    <?php foreach ($result_artikel as $result): ?>
                        <option value="<?php echo $result['id'] ?>"><?php echo $result['bezeichnung'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="id_bestellung" class="form-label">Bestellung</label>
                <select class="form-control" id="id_bestellung" name="id_bestellung" required>
                    <option value="">Bitte wählen</option>
                    <?php foreach ($result_bestellung as $result): ?>
                        <option value="<?php echo $result['id'] ?>"><?php echo $result['id'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="menge" class="form-label">Menge</label>
                <input type="number" class="form-control" id="menge" min="1" name="menge" required>

            </div>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-primary">Bestelldetail hinzufügen</button>
            </div>
        </form>
        <a href="bestelldetails.php" class="back-button">Zurück zur Übersicht</a>
    </div>
</body>
</html>