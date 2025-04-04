<?php
include "../db/connection.php";
try {
    $requestMethod = trim($_SERVER["REQUEST_METHOD"]);
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid incomig JSON"]);
        return;
    }
    header("Content-Type: application/json; charset=utf-8");

    switch ($requestMethod) {
        // registration new user
        case "POST":
            // interface $data {
            //     login: string,
            //     password: string,
            //     role: {
            //          type: string,
            //          default: "user",
            //     }
            // }
            $userName = isset($data['login']) ? htmlspecialchars($data['login'], ENT_QUOTES, "UTF-8") : "";
            $userPass = isset($data['password']) ? htmlspecialchars($data['password'], ENT_QUOTES, "UTF-8") : "";

            // Check for not empty user
            if (empty($userName) || empty($userPass) || strlen($userPass) < 3) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid input. Username and password are required, and password must be at least 6 characters."]);
                exit;
            }

            // Check for duplicate user
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE login = :login");
            $stmt->bindParam(":login", $userName);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                http_response_code(409);
                echo json_encode(["error" => "User already exists."]);
                exit;
            }

            $hashedPassword = password_hash($userPass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (login, password) VALUES (:userName, :pass)");
            $stmt->bindParam(":userName", $userName);
            $stmt->bindParam(":pass", $hashedPassword);
            $stmt->execute();
            http_response_code(201);
            break;

        default:
            echo http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    http_response_code(500);
    return;
}