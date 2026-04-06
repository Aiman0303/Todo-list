<?php
include("db.php");

$method = $_SERVER["REQUEST_METHOD"];

// GET (all todos / single todo)
if ($method == "GET") {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT * FROM todos WHERE id=$id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(["message" => "Todo not found"]);
        }
    } else {
        $sql = "SELECT * FROM todos ORDER BY id DESC";
        $result = $conn->query($sql);
        $todos = [];
        while ($row = $result->fetch_assoc()) {
            $todos[] = $row;
        }
        echo json_encode($todos);
    }
}

// POST (create todo)
if ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["title"]) || empty(trim($data["title"]))) {
        echo json_encode(["message" => "Title is required"]);
        exit;
    }

    $title = $conn->real_escape_string($data["title"]);

    $sql = "INSERT INTO todos (title) VALUES ('$title')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Todo added successfully"]);
    } else {
        echo json_encode(["message" => "Failed to add todo: " . $conn->error]);
    }
}

// PUT (update todo)
if ($method == "PUT") {
    if (!isset($_GET["id"])) {
        echo json_encode(["message" => "Todo ID is required"]);
        exit;
    }

    $id = $_GET["id"];
    $data = json_decode(file_get_contents("php://input"), true);

    $updates = [];
    if (isset($data["title"])) $updates[] = "title='" . $conn->real_escape_string($data["title"]) . "'";
    if (isset($data["status"])) $updates[] = "status='" . $conn->real_escape_string($data["status"]) . "'";

    if (count($updates) == 0) {
        echo json_encode(["message" => "No data to update"]);
        exit;
    }

    $sql = "UPDATE todos SET " . implode(",", $updates) . " WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Todo updated successfully"]);
    } else {
        echo json_encode(["message" => "Failed to update todo: " . $conn->error]);
    }
}

// DELETE (delete todo)
if ($method == "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["id"])) {
        echo json_encode(["message" => "Todo ID is required"]);
        exit;
    }

    $id = $data["id"];
    $sql = "DELETE FROM todos WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Todo deleted successfully"]);
    } else {
        echo json_encode(["message" => "Failed to delete todo: " . $conn->error]);
    }
}

$conn->close();
?>