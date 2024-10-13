<?php
include_once '../db/config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $conn->set_charset("utf8");

        // Get the item ID from the POST data
        $itemId = $_POST['item_id'];

        // Delete the item from the inspections table
        $stmt = $conn->prepare("DELETE FROM setad.inspections WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $stmt->close();

        // Redirect or output a success message
        echo json_encode(['success' => true, 'message' => 'ردیف شماره '.$itemId.' حذف شد.']);
        exit();

    } catch (mysqli_sql_exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
}
