<?php
require 'config.php';

header('Content-Type: application/json'); // for fetch responses

// --- FETCH MODE (for modal) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch') {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(["error" => "Missing ID"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            SELECT o.id, o.user_id, o.ot_date, o.hours, o.rate
            FROM overtime o
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Record not found"]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
    exit;
}

// --- UPDATE MODE (form submission) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = $_POST['id'] ?? null;
    $user_id = $_POST['user_id'] ?? null;
    $ot_date = $_POST['ot_date'] ?? null;
    $hours   = $_POST['hours'] ?? null;
    $rate    = $_POST['rate'] ?? null;

    // Debug (uncomment if still failing)
    /*
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    exit;
    */

    if (empty($id) || empty($user_id) || empty($ot_date) || empty($hours) || empty($rate)) {
        die('❌ Missing required fields.');
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE overtime
            SET user_id = :user_id, ot_date = :ot_date, hours = :hours, rate = :rate
            WHERE id = :id
        ");
        $stmt->execute([
            ':user_id' => $user_id,
            ':ot_date' => $ot_date,
            ':hours'   => $hours,
            ':rate'    => $rate,
            ':id'      => $id
        ]);

        // Redirect back to main page
        header('Content-Type: text/html'); // reset header for redirect
        header('Location: overtime.php?status=updated');
        exit;
    } catch (PDOException $e) {
        die("❌ Update failed: " . $e->getMessage());
    }
}

die('❌ Invalid request.');
