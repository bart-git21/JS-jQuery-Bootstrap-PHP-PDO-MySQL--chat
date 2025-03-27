<?php
include "../db/connection.php";
$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");

switch ($requestMethod) {
    case "POST":
        $json = file_get_contents("php://input");
        // interface $user {
        //     login: string,
        //     password: string,
        // }
        if ($json) {
            $user = json_decode($json, true);
        } else {
            echo json_encode(["error" => "Invalid JSON"]);
            http_response_code(400);
            return;
        }

        $userName = isset($user['login']) ? htmlspecialchars($user['login'], ENT_QUOTES, "UTF-8") : "";
        $userPass = isset($user['password']) ? htmlspecialchars($user['password'], ENT_QUOTES, "UTF-8") : "";
        $hashedPassword = password_hash($userPass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (login, password) VALUES (:userName, :pass)");
        $stmt->bindParam(":userName", $userName);
        $stmt->bindParam(":pass", $hashedPassword);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
            http_response_code(500);
            return;
        }

        $lastInsertedId = $conn->lastInsertId();
        http_response_code(201);
        break;
    default:
        echo http_response_code(405);
        echo json_encode(["error" => "Method Not Allowed"]);
        break;
}
