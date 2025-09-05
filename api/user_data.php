<?php
// Oturum başlatılmamışsa başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once '../database/db.php';
/*
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
*/

//GÜVENLİ VERSİYON
header("Access-Control-Allow-Origin: https://localhost:8000");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //GÜVENLİ OLMAYAN VERSİYON
    $userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

    /* 
    //GÜVENLİ VERSİYON: Kullanıcı kimlik doğrulaması yapılır
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'Unauthorized access']);
        exit;
    }
    $userId = $_SESSION['user_id']; // Sadece kendi verilerine erişim sağlanır
    */

    if ($userId > 0) {
        try {
            //GÜVENLİ OLMAYAN VERSİYON
            $query = "SELECT * FROM users WHERE id = $userId";
            $result = $pdo->query($query);
            $user = $result->fetch(PDO::FETCH_ASSOC);

            /* 
            //GÜVENLİ VERSİYON
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            */

            if ($user) {
                echo json_encode($user);
            } else {
                echo json_encode(['error' => 'User not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid user ID']);
    }
}
?>