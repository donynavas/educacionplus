<?php
// Redirigir al archivo real en modules/profesor/
header("Location: modules/profesor/aula_virtual.php" . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
exit;
?>