<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) ) {
    $data = json_decode(file_get_contents('php://input'), true);
    $descriptionHtml = $data['descriptionHtml'];
    $file = fopen("report.html", "w");
    fwrite($file, $descriptionHtml);
    fclose($file);
    echo json_encode(['success' => true]);
    exit;
}
else {
    echo json_encode(['success' => false]);
    exit;
}

?>

