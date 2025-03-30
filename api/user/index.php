<?php
include "../db/connection.php";
try {
    function getUri($path): string
    {
        $urlPath = parse_url(trim($_SERVER['REQUEST_URI']), PHP_URL_PATH);
        return str_replace("/api/$path", "", $urlPath);
    }

    $requestMethod = trim($_SERVER["REQUEST_METHOD"]);
    $requestUri = getUri("user");
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid incomig JSON"]);
        return;
    }
    header("Content-Type: application/json; charset=utf-8");

    switch ($requestUri) {
        case "/":
            switch ($requestMethod) {
                case "GET":
                    $stmt = $conn->prepare("SELECT * FROM users");
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    http_response_code(200);
                    echo json_encode($result);
                    break;

                // create new user
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
                    // $lastInsertedId = $conn->lastInsertId();
                    http_response_code(201);
                    break;

                default:
                    echo http_response_code(405);
                    echo json_encode(["error" => "Method not allowed"]);
                    break;
            }
            break;
        case preg_match("/\d+/", $requestUri, $matches) ? true : false:
            $id = $matches[0];
            switch ($requestMethod) {
                case "GET":
                    $id = $_GET["id"];
                    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
                    $stmt->bindParam(":id", $id);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode($user);
                    break;

                case 'PUT':
                    $stmt = $conn->prepare("UPDATE users SET login = :login WHERE id = :id");
                    $stmt->bindParam(":id", $data["id"]);
                    $stmt->bindParam(":login", $data["login"]);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(['success' => 'User  updated']);
                    break;

                case 'DELETE':
                    if (isset($_GET['id'])) {
                        $id = $_GET["id"];
                        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
                        $stmt->bindParam(":id", $id);
                        $stmt->execute();
                        echo json_encode(['success' => 'User  deleted']);
                    }
                    break;

                default:
                    echo http_response_code(405);
                    echo json_encode(["error" => "Method Not Allowed"]);
                    break;
            }
            break;
        default:
            echo http_response_code(405);
            echo json_encode(["error" => "Path not allowed"]);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    http_response_code(500);
    return;
}