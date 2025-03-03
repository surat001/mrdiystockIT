<?php
$filePath = __DIR__ . "/uploads/1739640985_111111111111111.jpg";

if (!file_exists($filePath)) {
    die("File not found: " . $filePath);
}

if (unlink($filePath)) {
    echo "File deleted successfully!";
} else {
    echo "Failed to delete file!";
}
?>
