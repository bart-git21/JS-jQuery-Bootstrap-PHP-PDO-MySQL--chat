<?php
include "../db/connection.php";
try {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid incomig JSON"]);
        return;
    }
    header("Content-Type: application/json; charset=utf-8");

    switch ($requestMethod) {
        // authentication
        case "POST":
            // interface incomingUserData {
            //     login: string,
            //     password: string,
            // }
            $userName = isset($data["login"]) ? htmlspecialchars($data["login"], ENT_QUOTES, "UTF-8") : "";
            $userPassword = isset($data["password"]) ? htmlspecialchars($data["password"], ENT_QUOTES, "UTF-8") : "";

            $stmt = $conn->prepare("SELECT password FROM users WHERE login = ?");
            $stmt->execute([$userName]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(["json" => $data, "user" => $user]);
            break;
        default:
            echo http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    http_response_code(500);
    return;
}