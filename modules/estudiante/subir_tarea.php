<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowed = [
        'application/pdf', 
        'application/msword', 
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'video/mp4', 
        'audio/mpeg'
    ];

    $file = $_FILES['archivo_tarea'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (in_array($mime, $allowed)) {
        $uploadDir = '../../assets/uploads/tareas/';
        $fileName = uniqid() . '_' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $uploadDir . $fileName);
        
        // Guardar ruta en BD tbl_entrega_actividad
        // ...
        echo "Tarea subida con éxito.";
    } else {
        echo "Formato de archivo no permitido.";
    }
}
?>
<!-- Formulario HTML con Bootstrap -->
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="archivo_tarea" class="form-control" required>
    <button type="submit" class="btn btn-primary mt-2">Entregar Tarea</button>
</form>