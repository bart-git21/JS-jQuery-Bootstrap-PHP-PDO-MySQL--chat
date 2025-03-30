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

            // Check for not empty incoming data
            if (empty($userName) && empty($userPassword)) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid input data. Name or password are required."]);
                exit;
            }

            $stmt = $conn->prepare("SELECT password FROM users WHERE login = ?");
            $stmt->execute([$userName]);
            $hashedPassword = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // check for existing user
            if (!$hashedPassword) {
                http_response_code(401);
                echo json_encode(["error" => "Invalid name or password."]);
                exit;
            } 

            // check for correct password
            $isCorrectPassword = password_verify($userPassword, $hashedPassword["password"]);
            if (!$isCorrectPassword) {
                http_response_code(401);
                echo json_encode(["error" => "Invalid name or password."]);
                exit;
            } else {
                echo json_encode(["password" => $hashedPassword]);
            }
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