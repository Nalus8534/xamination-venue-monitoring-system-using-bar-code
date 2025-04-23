<?php
include 'db_connection.php';
require __DIR__ . '/../vendor/autoload.php';

use Smalot\PdfParser\Parser;

ob_start();
header('Content-Type: application/json');

if ($_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['pdfFile']['tmp_name'];

    try {
        $parser = new Parser();
        $pdf = $parser->parseFile($fileTmpPath);
        $text = $pdf->getText();

        if (empty($text)) {
            ob_end_clean();
            echo json_encode(["status" => "error", "message" => "The extracted text is empty."]);
            exit();
        }

        ob_end_clean();
        echo json_encode(["status" => "success", "text" => $text]);
        exit();

    } catch (Exception $e) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Error parsing PDF: " . $e->getMessage()]);
        exit();
    }
} else {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "File upload error: " . $_FILES['pdfFile']['error']]);
    exit();
}
?>