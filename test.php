<?php
$file = __DIR__ . '/config/database.php';
if (file_exists($file)) {
    echo "✅ El archivo EXISTS: " . $file;
} else {
    echo "❌ El archivo NO EXISTE: " . $file;
    echo "<br>Archivos en config/:<br>";
    $files = scandir(__DIR__ . '/config/');
    print_r($files);
}
?>