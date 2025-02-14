<?php
header("Content-Type: application/json");

$host = "dpg-cund0m1opnds73d4uap0-a.frankfurt-postgres.render.com";
$dbname = "todo_db_bwbh";
$username = "todo_db_bwbh_user";
$password = "MrL2SCRRL5bziCrrOzY4U4fmWZJH6WBP";
$port = "5432";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
}

// fetch all todos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM todos ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
// add new todo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data["todo"]) || empty(trim($data["todo"]))) {
        die(json_encode(["error" => "Task cannot be empty"]));
    }

    $stmt = $pdo->prepare("INSERT INTO todos (todo) VALUES (:todo)");
    $stmt->execute([":todo" => $data["todo"]]);

    echo json_encode(["message" => "Task added successfully"]);
}
// update todo
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    if (!isset($data["id"]) || !isset($data["completed"])) {
        die(json_encode(["error" => "Invalid input"]));
    }

    $stmt = $pdo->prepare("UPDATE todos SET completed = :completed WHERE id = :id");
    $stmt->execute([":completed" => $data["completed"], ":id" => $data["id"]]);

    echo json_encode(["message" => "Task updated"]);
}
// delete todo
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['delete_all'])) {
        $stmt = $pdo->query("DELETE FROM todos");
        echo json_encode(["message" => "All tasks deleted"]);
    } else {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die(json_encode(["error" => "Task ID required"]));
        }

        $stmt = $pdo->prepare("DELETE FROM todos WHERE id = :id");
        $stmt->execute([":id" => $id]);

        echo json_encode(["message" => "Task deleted"]);
    }
}
?>
