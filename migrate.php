<?php
$host = "dpg-cund0m1opnds73d4uap0-a.frankfurt-postgres.render.com";
$dbname = "todo_db_bwbh";
$username = "todo_db_bwbh_user";
$password = "MrL2SCRRL5bziCrrOzY4U4fmWZJH6WBP";
$port = "5432";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS todos (
        id SERIAL PRIMARY KEY,
        todo TEXT NOT NULL,
        completed BOOLEAN DEFAULT FALSE
    )";

    $pdo->exec($sql);
    echo "✅ Table created successfully!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
