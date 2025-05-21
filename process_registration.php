<?php

// 1) Enable full error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 2) Load DB connection
require_once __DIR__ . '/db_config.php';

// 3) Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// 4) Retrieve & trim inputs
$owner_name   = trim($_POST['owner_name']   ?? '');
$phone_number = trim($_POST['phone_number'] ?? '');
$stand_code   = trim($_POST['stand_code']   ?? '');
$category     = trim($_POST['category']     ?? '');

// 5) Basic validation
if ($owner_name === '' || $phone_number === '' || $stand_code === '' || $category === '') {
    die('Error: All fields are required.');
}

// 6) Prepare & bind
$stmt = $conn->prepare(
    "INSERT INTO stands (owner_name, phone_number, stand_code, category) 
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $owner_name, $phone_number, $stand_code, $category);

// 7) Execute & render appropriate page
try {
    $stmt->execute();
    // SUCCESS: render a styled HTML page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Succès de l'enregistrement!</title>
      <link rel="stylesheet" href="style.css">
      <style>
        /* quick overrides for the success page */
        body { display: flex; align-items: center; justify-content: center; height: 100vh; }
        .success-box {
          background: #1a1a1a;
          color: #fff;
          padding: 2rem;
          border-radius: 12px;
          text-align: center;
          font-family: 'Orbitron', sans-serif;
          box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .success-box h1 { font-size: 2.5rem; margin-bottom: 1rem; }
        .success-box p { font-size: 1.2rem; margin-bottom: 1.5rem; }
        .success-box a {
          display: inline-block;
          padding: 0.75rem 1.5rem;
          background: #00e676;
          color: #000;
          text-decoration: none;
          border-radius: 8px;
          font-weight: bold;
        }
        .success-box a:hover { background: #00c853; }
      </style>
    </head>
    <body>
      <div class="success-box">
        <h1>🎉 Stand Enregistré!</h1>
        <p>Merci, <strong><?= htmlspecialchars($owner_name) ?></strong>.<br>
           Le code de votre stand <strong><?= htmlspecialchars($stand_code) ?></strong> est dans la catégorie <em><?= htmlspecialchars($category) ?></em></p>
        <a href="index.html">Enregistrer un autre Stand</a>
      </div>
    </body>
    </html>
    <?php
} catch (mysqli_sql_exception $e) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Erreur lors de l'engeristrement</title>
      <link rel="stylesheet" href="style.css">
      <style>
        body { display: flex; align-items: center; justify-content: center; height: 100vh; }
        .error-box {
          background: #330000;
          color: #fff;
          padding: 2rem;
          border-radius: 12px;
          text-align: center;
          font-family: 'Orbitron', sans-serif;
          box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .error-box h1 { font-size: 2rem; margin-bottom: 1rem; }
        .error-box p { font-size: 1rem; margin-bottom: 1.5rem; }
        .error-box a {
          display: inline-block;
          padding: 0.5rem 1rem;
          background: #ff1744;
          color: #fff;
          text-decoration: none;
          border-radius: 8px;
          font-weight: bold;
        }
        .error-box a:hover { background: #d50000; }
      </style>
    </head>
    <body>
      <div class="error-box">
        <h1>❌ Oops!</h1>
        <p>Erreur lors de l'engeristrement<br>
           Error: <?= htmlspecialchars($e->getMessage()) ?></p>
        <a href="index.html">Réessayer</a>
      </div>
    </body>
    </html>
    <?php
}

// 8) Clean up
$stmt->close();
$conn->close();
?>