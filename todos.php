<?php
header("Content-Type: application/json");

// Esimerkkitodo-lista
$todos = [
    ["id" => 1, "todo" => "Tee PHP-sovellus", "completed" => false],
    ["id" => 2, "todo" => "Testaa API", "completed" => true]
];

// Haetaan kaikki TODO:t
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($todos);
}

// Lisätään uusi TODO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $newTodo = [
        "id" => count($todos) + 1,
        "todo" => $data["todo"],
        "completed" => false
    ];
    $todos[] = $newTodo;
    echo json_encode($newTodo);
}

// Päivitetään TODO
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $_GET['id'];
    foreach ($todos as &$todo) {
        if ($todo["id"] == $id) {
            $todo["completed"] = $data["completed"];
            echo json_encode($todo);
            exit;
        }
    }
}

// Poistetaan TODO
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];
    $todos = array_filter($todos, fn($todo) => $todo["id"] != $id);
    echo json_encode(["message" => "Todo deleted"]);
}
?>
