<?php
session_start();
include '../../config/database.php';

// Verificar que sea profesor
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 'profesor') {
    header("Location: ../../login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Obtener datos del profesor
$query = "SELECT p.id as id_profesor, per.primer_nombre, per.primer_apellido, per.email
          FROM tbl_profesor p
          JOIN tbl_persona per ON p.id_persona = per.id
          WHERE per.id_usuario = :user_id";
$stmt = $db->prepare($query);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$profesor = $stmt->fetch(PDO::FETCH_ASSOC);
$id_profesor = $profesor['id_profesor'] ?? 0;

$mensaje = '';
$tipo_mensaje = '';

// ===== PROCESAR ACCIONES POST =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    try {
        $db->beginTransaction();
        
        // === PUBLICAR RECURSO ===
        if ($accion == 'publicar_recurso') {
            $id_asignacion = filter_input(INPUT_POST, 'id_asignacion', FILTER_VALIDATE_INT);
            $titulo = trim($_POST['titulo'] ?? '');
            $tipo_recurso = $_POST['tipo_recurso'] ?? '';
            $contenido = $_POST['contenido'] ?? '';
            $url_recurso = filter_var($_POST['url_recurso'] ?? '', FILTER_VALIDATE_URL) ?: null;
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha_publicacion = date('Y-m-d H:i:s');
            $estado = 'publicado';
            
            // Validar que la asignación pertenece al profesor
            $check = $db->prepare("SELECT id FROM tbl_asignacion_docente WHERE id = ? AND id_profesor = ?");
            $check->execute([$id_asignacion, $id_profesor]);
            
            if ($check->rowCount() > 0) {
                $query = "INSERT INTO tbl_actividad (id_asignacion_docente, titulo, descripcion, tipo, 
                          contenido, url_recurso, fecha_programada, estado, recursos_url) 
                          VALUES (:id_asignacion, :titulo, :descripcion, :tipo, 
                                  :contenido, :url_recurso, :fecha_programada, :estado, :recursos)";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':id_asignacion' => $id_asignacion,
                    ':titulo' => $titulo,
                    ':descripcion' => $descripcion,
                    ':tipo' => $tipo_recurso,
                    ':contenido' => $contenido,
                    ':url_recurso' => $url_recurso,
                    ':fecha_programada' => $fecha_publicacion,
                    ':estado' => $estado,
                    ':recursos' => ''
                ]);
                
                $db->commit();
                $mensaje = 'Recurso publicado exitosamente';
                $tipo_mensaje = 'success';
            } else {
                throw new Exception("Asignación no válida");
            }
            
        } elseif ($accion == 'crear_tarea') {
            $id_asignacion = filter_input(INPUT_POST, 'id_asignacion', FILTER_VALIDATE_INT);
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha_entrega = $_POST['fecha_entrega'] ?? '';
            $nota_maxima = filter_input(INPUT_POST, 'nota_maxima', FILTER_VALIDATE_FLOAT) ?: 10;
            $archivo_adjunto = $_POST['archivo_adjunto'] ?? '';
            
            $check = $db->prepare("SELECT id FROM tbl_asignacion_docente WHERE id = ? AND id_profesor = ?");
            $check->execute([$id_asignacion, $id_profesor]);
            
            if ($check->rowCount() > 0) {
                $query = "INSERT INTO tbl_actividad (id_asignacion_docente, titulo, descripcion, tipo, 
                          fecha_limite, nota_maxima, estado, recursos_url) 
                          VALUES (:id_asignacion, :titulo, :descripcion, 'tarea', 
                                  :fecha_limite, :nota_maxima, 'activo', :recursos)";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':id_asignacion' => $id_asignacion,
                    ':titulo' => $titulo,
                    ':descripcion' => $descripcion,
                    ':fecha_limite' => $fecha_entrega,
                    ':nota_maxima' => $nota_maxima,
                    ':recursos' => $archivo_adjunto
                ]);
                
                $db->commit();
                $mensaje = 'Tarea creada exitosamente';
                $tipo_mensaje = 'success';
            }
            
        } elseif ($accion == 'crear_examen') {
            $id_asignacion = filter_input(INPUT_POST, 'id_asignacion', FILTER_VALIDATE_INT);
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha_programada = $_POST['fecha_programada'] ?? '';
            $duracion_minutos = filter_input(INPUT_POST, 'duracion_minutos', FILTER_VALIDATE_INT) ?: 60;
            $nota_maxima = filter_input(INPUT_POST, 'nota_maxima', FILTER_VALIDATE_FLOAT) ?: 10;
            
            $check = $db->prepare("SELECT id FROM tbl_asignacion_docente WHERE id = ? AND id_profesor = ?");
            $check->execute([$id_asignacion, $id_profesor]);
            
            if ($check->rowCount() > 0) {
                $query = "INSERT INTO tbl_actividad (id_asignacion_docente, titulo, descripcion, tipo, 
                          fecha_programada, duracion_minutos, nota_maxima, estado) 
                          VALUES (:id_asignacion, :titulo, :descripcion, 'examen', 
                                  :fecha_programada, :duracion, :nota_maxima, 'programado')";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':id_asignacion' => $id_asignacion,
                    ':titulo' => $titulo,
                    ':descripcion' => $descripcion,
                    ':fecha_programada' => $fecha_programada,
                    ':duracion' => $duracion_minutos,
                    ':nota_maxima' => $nota_maxima
                ]);
                
                $db->commit();
                $mensaje = 'Examen programado exitosamente';
                $tipo_mensaje = 'success';
            }
            
        } elseif ($accion == 'eliminar_recurso') {
            $id_actividad = filter_input(INPUT_POST, 'id_actividad', FILTER_VALIDATE_INT);
            
            $check = $db->prepare("SELECT a.id FROM tbl_actividad a
                                  JOIN tbl_asignacion_docente ad ON a.id_asignacion_docente = ad.id
                                  WHERE a.id = :id AND ad.id_profesor = :id_profesor");
            $check->execute([':id' => $id_actividad, ':id_profesor' => $id_profesor]);
            
            if ($check->fetch()) {
                $query = "UPDATE tbl_actividad SET estado = 'eliminado' WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->execute([':id' => $id_actividad]);
                $mensaje = 'Recurso eliminado';
                $tipo_mensaje = 'warning';
            }
            $db->commit();
        }
        
    } catch (Exception $e) {
        $db->rollBack();
        error_log("Error en aula_virtual.php: " . $e->getMessage());
        $mensaje = 'Error: ' . $e->getMessage();
        $tipo_mensaje = 'danger';
    }
}

// ===== OBTENER ASIGNACIONES DEL PROFESOR =====
$query = "SELECT ad.id, ad.anno, asig.nombre as asignatura_nombre, asig.codigo as asignatura_codigo,
          g.nombre as grado_nombre, g.nivel,
          s.nombre as seccion_nombre,
          COUNT(DISTINCT m.id) as total_estudiantes,
          COUNT(DISTINCT a.id) as total_actividades
          FROM tbl_asignacion_docente ad
          JOIN tbl_asignatura asig ON ad.id_asignatura = asig.id
          JOIN tbl_seccion s ON ad.id_seccion = s.id
          JOIN tbl_grado g ON s.id_grado = g.id
          LEFT JOIN tbl_matricula m ON s.id = m.id_seccion AND m.anno = ad.anno AND m.estado = 'activo'
          LEFT JOIN tbl_actividad a ON ad.id = a.id_asignacion_docente AND a.estado IN ('publicado', 'activo', 'programado')
          WHERE ad.id_profesor = :id_profesor
          GROUP BY ad.id
          ORDER BY g.nombre, s.nombre, asig.nombre";
$stmt = $db->prepare($query);
$stmt->bindValue(':id_profesor', $id_profesor, PDO::PARAM_INT);
$stmt->execute();
$asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== OBTENER ACTIVIDADES DE LA ASIGNACIÓN SELECCIONADA =====
$id_asignacion_seleccionada = $_GET['asignacion'] ?? ($asignaciones[0]['id'] ?? 0);
$filtro_tipo = $_GET['tipo'] ?? 'todos';
$actividades = [];
$estudiantes = [];

if ($id_asignacion_seleccionada) {
    // Consulta de actividades con LEFT JOIN para entregas
    $query_act = "SELECT a.id, a.titulo, a.descripcion, a.tipo, a.contenido, a.url_recurso, 
                 a.fecha_programada, a.fecha_limite, a.duracion_minutos, a.nota_maxima, a.estado,
                 COUNT(DISTINCT ea.id_estudiante) as total_entregas,  
                 AVG(ea.nota_obtenida) as promedio_notas
                 FROM tbl_actividad a
                 LEFT JOIN tbl_entrega_actividad ea ON a.id = ea.id_actividad 
                 WHERE a.id_asignacion_docente = :id_asignacion
                 AND a.estado IN ('publicado', 'activo', 'programado')";
    
    $params_act = [':id_asignacion' => $id_asignacion_seleccionada];
    
    if ($filtro_tipo != 'todos') {
        $query_act .= " AND a.tipo = :tipo";
        $params_act[':tipo'] = $filtro_tipo;
    }
    
    $query_act .= " GROUP BY a.id ORDER BY a.fecha_programada DESC";
    
    try {
        $stmt_act = $db->prepare($query_act);
        foreach ($params_act as $key => $value) {
            $stmt_act->bindValue($key, $value);
        }
        $stmt_act->execute();
        $actividades = $stmt_act->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en consulta actividades: " . $e->getMessage());
        $actividades = [];
    }
    
    // Obtener estudiantes de esta asignación
    try {
        $query_est = "SELECT e.id, p.primer_nombre, p.primer_apellido, p.email, p.celular, e.nie,
                     m.estado as estado_matricula
                     FROM tbl_matricula m
                     JOIN tbl_estudiante e ON m.id_estudiante = e.id
                     JOIN tbl_persona p ON e.id_persona = p.id
                     WHERE m.id_seccion = (SELECT id_seccion FROM tbl_asignacion_docente WHERE id = :id_asig)
                     AND m.anno = (SELECT anno FROM tbl_asignacion_docente WHERE id = :id_asig2)
                     AND m.estado = 'activo'
                     ORDER BY p.primer_apellido, p.primer_nombre";
        $stmt_est = $db->prepare($query_est);
        $stmt_est->bindValue(':id_asig', $id_asignacion_seleccionada, PDO::PARAM_INT);
        $stmt_est->bindValue(':id_asig2', $id_asignacion_seleccionada, PDO::PARAM_INT);
        $stmt_est->execute();
        $estudiantes = $stmt_est->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en consulta estudiantes: " . $e->getMessage());
        $estudiantes = [];
    }
}

// Tipos de recursos
$tipos_recurso = [
    'video' => ['label' => '🎬 Video', 'icon' => 'fa-video', 'color' => 'danger'],
    'youtube' => ['label' => '📺 YouTube', 'icon' => 'fa-youtube', 'color' => 'danger'],
    'articulo' => ['label' => '📄 Artículo', 'icon' => 'fa-file-alt', 'color' => 'info'],
    'referencia' => ['label' => '📚 Referencia', 'icon' => 'fa-book', 'color' => 'primary'],
    'podcast' => ['label' => '🎧 Podcast', 'icon' => 'fa-podcast', 'color' => 'purple'],
    'revista' => ['label' => '📰 Revista', 'icon' => 'fa-newspaper', 'color' => 'warning'],
    'enlace' => ['label' => '🔗 Enlace', 'icon' => 'fa-link', 'color' => 'secondary'],
    'tarea' => ['label' => '📝 Tarea', 'icon' => 'fa-clipboard-list', 'color' => 'warning'],
    'examen' => ['label' => '📋 Examen', 'icon' => 'fa-file-alt', 'color' => 'danger']
];

// Estados de actividad
$estados_actividad = [
    'publicado' => ['label' => 'Publicado', 'class' => 'bg-success'],
    'activo' => ['label' => 'Activo', 'class' => 'bg-primary'],
    'programado' => ['label' => 'Programado', 'class' => 'bg-secondary'],
    'cerrado' => ['label' => 'Cerrado', 'class' => 'bg-dark'],
    'eliminado' => ['label' => 'Eliminado', 'class' => 'bg-light text-muted']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula Virtual - <?= htmlspecialchars($profesor['primer_nombre']) ?> - Educación Plus</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    
    <style>
        :root { --primary: #2c3e50; --secondary: #3498db; --success: #2ecc71; --warning: #f39c12; --danger: #e74c3c; --sidebar-width: 260px; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-width); background: var(--primary); color: white; padding-top: 20px; z-index: 1000; overflow-y: auto; }
        .sidebar .nav-link { color: rgba(255,255,255,0.85); padding: 12px 20px; margin: 2px 0; border-radius: 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.15); }
        .sidebar .nav-link i { width: 24px; text-align: center; margin-right: 8px; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .card-custom { background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border: none; margin-bottom: 20px; }
        .resource-card { border-left: 4px solid var(--secondary); transition: all 0.2s; }
        .resource-card:hover { transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,0,0,0.12); }
        .resource-card.video { border-left-color: #e74c3c; }
        .resource-card.youtube { border-left-color: #ff0000; }
        .resource-card.articulo { border-left-color: #3498db; }
        .resource-card.referencia { border-left-color: #9b59b6; }
        .resource-card.podcast { border-left-color: #e67e22; }
        .resource-card.tarea { border-left-color: #f39c12; }
        .resource-card.examen { border-left-color: #c0392b; }
        .badge-recurso { padding: 4px 12px; border-radius: 15px; font-size: 0.75rem; font-weight: 600; }
        .student-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--secondary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem; }
        .class-header { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; }
        .quick-action { padding: 15px; border-radius: 10px; background: white; text-align: center; cursor: pointer; transition: all 0.2s; border: 2px solid transparent; }
        .quick-action:hover { border-color: var(--secondary); transform: translateY(-3px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .quick-action i { font-size: 1.5rem; margin-bottom: 8px; display: block; }
        .embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; }
        .embed-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
        
        /* ✅ Select2 en modales - z-index fix */
        .select2-container { z-index: 10060 !important; }
        .select2-dropdown { z-index: 10060 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
        .modal-open .select2-container--open { z-index: 10060 !important; }
        
        @media (max-width: 992px) { .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="text-center mb-4 px-3">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-weight: 700;">
                    <?= strtoupper(substr($profesor['primer_nombre'], 0, 1)) ?>
                </div>
                <div class="text-start">
                    <div class="fw-bold"><?= htmlspecialchars($profesor['primer_nombre']) ?></div>
                    <small class="text-white-50">Profesor</small>
                </div>
            </div>
        </div>
        
        <nav class="nav flex-column px-2">
            <a class="nav-link active" href="../../index.php"><i class="fas fa-chalkboard-teacher"></i> Dashboard</a>
            <a class="nav-link active" href="#"><i class="fas fa-chalkboard-teacher"></i> Mi Aula Virtual</a>
            <a class="nav-link" href="gestionar_estudiantes.php"><i class="fas fa-user-graduate"></i> Estudiantes</a>
            <a class="nav-link" href="calificaciones.php"><i class="fas fa-star"></i> Calificaciones</a>
            <a class="nav-link" href="reportes.php"><i class="fas fa-chart-bar"></i> Reportes</a>
            <a class="nav-link" href="../../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </nav>
        
        <div class="mt-4 px-3">
            <small class="text-white-50">Mis Asignaciones</small>
            <div class="mt-2">
                <?php foreach ($asignaciones as $asig): ?>
                <a href="?asignacion=<?= $asig['id'] ?>" class="d-block text-white-50 text-decoration-none py-1 px-2 rounded small <?= $id_asignacion_seleccionada == $asig['id'] ? 'bg-white-10 text-white' : 'hover-bg-white-10' ?>">
                    <i class="fas fa-chevron-right me-1 small"></i>
                    <?= htmlspecialchars($asig['asignatura_nombre']) ?> - <?= htmlspecialchars($asig['grado_nombre']) ?> <?= htmlspecialchars($asig['seccion_nombre']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="class-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h3 class="mb-2"><i class="fas fa-chalkboard"></i> Aula Virtual</h3>
                    <?php if ($id_asignacion_seleccionada): 
                        $asig_actual = current(array_filter($asignaciones, fn($a) => $a['id'] == $id_asignacion_seleccionada));
                    ?>
                    <p class="mb-1 opacity-75">
                        <i class="fas fa-book"></i> <?= htmlspecialchars($asig_actual['asignatura_nombre']) ?>
                        <span class="mx-2">•</span>
                        <i class="fas fa-layer-group"></i> <?= htmlspecialchars($asig_actual['grado_nombre']) ?> <?= htmlspecialchars($asig_actual['seccion_nombre']) ?>
                    </p>
                    <p class="mb-0 small opacity-75">
                        <i class="fas fa-users"></i> <?= $asig_actual['total_estudiantes'] ?> estudiantes • 
                        <i class="fas fa-tasks"></i> <?= count($actividades) ?> actividades
                    </p>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalRecurso">
                        <i class="fas fa-plus"></i> Publicar Recurso
                    </button>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTarea">
                        <i class="fas fa-clipboard-list"></i> Nueva Tarea
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show">
            <i class="fas fa-<?= $tipo_mensaje == 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!$id_asignacion_seleccionada): ?>
        <div class="text-center py-5">
            <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
            <h4>Selecciona una asignación para comenzar</h4>
            <p class="text-muted">Elige una de tus clases asignadas en el menú lateral</p>
        </div>
        <?php else: ?>
        
        <!-- Quick Actions -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="quick-action" data-bs-toggle="modal" data-bs-target="#modalRecurso" data-tipo="video">
                    <i class="fas fa-video text-danger"></i>
                    <small>Video</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="quick-action" data-bs-toggle="modal" data-bs-target="#modalRecurso" data-tipo="youtube">
                    <i class="fab fa-youtube text-danger"></i>
                    <small>YouTube</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="quick-action" data-bs-toggle="modal" data-bs-target="#modalRecurso" data-tipo="articulo">
                    <i class="fas fa-file-alt text-info"></i>
                    <small>Artículo</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="quick-action" data-bs-toggle="modal" data-bs-target="#modalTarea">
                    <i class="fas fa-clipboard-list text-warning"></i>
                    <small>Tarea</small>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills mb-4" id="aulaTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="pill" href="#recursos"><i class="fas fa-folder-open"></i> Recursos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#estudiantes"><i class="fas fa-users"></i> Estudiantes (<?= count($estudiantes) ?>)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#estadisticas"><i class="fas fa-chart-line"></i> Estadísticas</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Recursos Tab -->
            <div class="tab-pane fade show active" id="recursos">
                <!-- Filtros -->
                <div class="card-custom p-3 mb-4">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-muted small">Filtrar por tipo:</span>
                        <?php foreach ($tipos_recurso as $key => $tipo): ?>
                        <a href="?asignacion=<?= $id_asignacion_seleccionada ?>&tipo=<?= $key ?>" 
                           class="badge <?= $filtro_tipo == $key ? 'bg-primary' : 'bg-light text-dark' ?> text-decoration-none">
                            <i class="fas <?= $tipo['icon'] ?>"></i> <?= $tipo['label'] ?>
                        </a>
                        <?php endforeach; ?>
                        <?php if ($filtro_tipo != 'todos'): ?>
                        <a href="?asignacion=<?= $id_asignacion_seleccionada ?>" class="badge bg-secondary text-decoration-none">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Lista de Recursos -->
                <?php if (empty($actividades)): ?>
                <div class="card-custom p-5 text-center">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5>No hay recursos publicados aún</h5>
                    <p class="text-muted">Comienza publicando videos, artículos o creando tareas para tus estudiantes</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRecurso">
                        <i class="fas fa-plus"></i> Publicar Primer Recurso
                    </button>
                </div>
                <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($actividades as $actividad): 
                        $tipo = $tipos_recurso[$actividad['tipo']] ?? ['label' => $actividad['tipo'], 'icon' => 'fa-file', 'color' => 'secondary'];
                        $estado = $estados_actividad[$actividad['estado']] ?? ['label' => $actividad['estado'], 'class' => 'bg-secondary'];
                    ?>
                    <div class="col-lg-6">
                        <div class="card-custom resource-card <?= htmlspecialchars($actividad['tipo']) ?> p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge-recurso bg-<?= $tipo['color'] ?> text-white">
                                        <i class="fas <?= $tipo['icon'] ?>"></i> <?= $tipo['label'] ?>
                                    </span>
                                    <span class="badge <?= $estado['class'] ?>"><?= $estado['label'] ?></span>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye"></i> Ver</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Editar</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este recurso?');">
                                                <input type="hidden" name="accion" value="eliminar_recurso">
                                                <input type="hidden" name="id_actividad" value="<?= $actividad['id'] ?>">
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash"></i> Eliminar</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <h6 class="mb-2"><?= htmlspecialchars($actividad['titulo']) ?></h6>
                            
                            <?php if ($actividad['descripcion']): ?>
                            <p class="text-muted small mb-2"><?= nl2br(htmlspecialchars(substr($actividad['descripcion'], 0, 150))) ?><?= strlen($actividad['descripcion']) > 150 ? '...' : '' ?></p>
                            <?php endif; ?>
                            
                            <!-- Preview según tipo -->
                            <?php if ($actividad['tipo'] == 'youtube' && $actividad['url_recurso']): 
                                preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $actividad['url_recurso'], $matches);
                                $video_id = $matches[1] ?? '';
                                if ($video_id):
                            ?>
                            <div class="embed-container mb-2">
                                <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($video_id) ?>" allowfullscreen></iframe>
                            </div>
                            <?php endif; endif; ?>
                            
                            <?php if ($actividad['tipo'] == 'video' && $actividad['url_recurso']): ?>
                            <div class="embed-container mb-2">
                                <video controls class="w-100 rounded">
                                    <source src="<?= htmlspecialchars($actividad['url_recurso']) ?>" type="video/mp4">
                                    Tu navegador no soporta video.
                                </video>
                            </div>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($actividad['fecha_programada'])) ?>
                                    <?php if ($actividad['fecha_limite']): ?>
                                    • <i class="fas fa-clock"></i> Entrega: <?= date('d/m', strtotime($actividad['fecha_limite'])) ?>
                                    <?php endif; ?>
                                    <?php if ($actividad['duracion_minutos']): ?>
                                    • <i class="fas fa-stopwatch"></i> <?= $actividad['duracion_minutos'] ?> min
                                    <?php endif; ?>
                                </small>
                                <?php if ($actividad['total_entregas'] > 0): ?>
                                <small class="text-primary">
                                    <i class="fas fa-inbox"></i> <?= $actividad['total_entregas'] ?> entregas
                                    <?php if ($actividad['promedio_notas']): ?>
                                    • 📊 <?= number_format($actividad['promedio_notas'], 1) ?>/10
                                    <?php endif; ?>
                                </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Estudiantes Tab -->
            <div class="tab-pane fade" id="estudiantes">
                <div class="card-custom">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Estudiantes de <?= htmlspecialchars($asig_actual['asignatura_nombre']) ?></h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>NIE</th>
                                        <th>Contacto</th>
                                        <th>Actividades</th>
                                        <th>Promedio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($estudiantes)): ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No hay estudiantes matriculados</td></tr>
                                    <?php else: ?>
                                    <?php foreach ($estudiantes as $est): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="student-avatar"><?= strtoupper(substr($est['primer_nombre'], 0, 1)) ?></div>
                                                <div>
                                                    <strong><?= htmlspecialchars($est['primer_nombre'] . ' ' . $est['primer_apellido']) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td><small class="text-muted"><?= htmlspecialchars($est['nie']) ?></small></td>
                                        <td>
                                            <small><i class="fas fa-envelope"></i> <?= htmlspecialchars(substr($est['email'] ?? 'N/A', 0, 20)) ?><?= strlen($est['email'] ?? '') > 20 ? '...' : '' ?></small>
                                        </td>
                                        <td><span class="badge bg-info">0</span></td>
                                        <td>-</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="verPerfilEstudiante(<?= $est['id'] ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Tab -->
            <div class="tab-pane fade" id="estadisticas">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card-custom p-4 text-center">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h3><?= count($estudiantes) ?></h3>
                            <p class="text-muted mb-0">Estudiantes Matriculados</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-custom p-4 text-center">
                            <i class="fas fa-tasks fa-3x text-warning mb-3"></i>
                            <h3><?= count($actividades) ?></h3>
                            <p class="text-muted mb-0">Actividades Publicadas</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-custom p-4 text-center">
                            <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
                            <h3>-</h3>
                            <p class="text-muted mb-0">Promedio General</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-custom p-4 mt-4">
                    <h5><i class="fas fa-chart-pie"></i> Distribución de Recursos</h5>
                    <div class="row mt-3">
                        <?php 
                        $tipos_count = [];
                        foreach ($actividades as $act) {
                            $tipos_count[$act['tipo']] = ($tipos_count[$act['tipo']] ?? 0) + 1;
                        }
                        foreach ($tipos_count as $tipo => $count): 
                            $tipo_info = $tipos_recurso[$tipo] ?? ['label' => $tipo, 'color' => 'secondary'];
                        ?>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas <?= $tipo_info['icon'] ?> text-<?= $tipo_info['color'] ?>"></i>
                                <span><?= $tipo_info['label'] ?>: <strong><?= $count ?></strong></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal Publicar Recurso -->
    <div class="modal fade" id="modalRecurso" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Publicar Recurso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formRecurso">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="publicar_recurso">
                        <input type="hidden" name="id_asignacion" value="<?= $id_asignacion_seleccionada ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Tipo de Recurso *</label>
                            <select name="tipo_recurso" class="form-select select-recurso" required>
                                <option value="">Seleccionar tipo</option>
                                <?php foreach ($tipos_recurso as $key => $tipo): if (!in_array($key, ['tarea', 'examen'])): ?>
                                <option value="<?= $key ?>">
                                    <i class="fas <?= $tipo['icon'] ?>"></i> <?= $tipo['label'] ?>
                                </option>
                                <?php endif; endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Título *</label>
                            <input type="text" name="titulo" class="form-control" required placeholder="Título del recurso">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Breve descripción del recurso..."></textarea>
                        </div>
                        
                        <!-- ✅ Campo URL unificado y dinámico -->
                        <div id="campo_url_unificado" class="mb-3 d-none">
                            <label class="form-label" id="label_url">URL / Enlace *</label>
                            <input type="url" name="url_recurso" class="form-control" id="input_url_recurso" placeholder="https://...">
                            <small class="text-muted" id="help_url">Enlace al recurso</small>
                        </div>
                        
                        <div id="campos_contenido" class="mb-3 d-none">
                            <label class="form-label">Contenido</label>
                            <textarea name="contenido" class="form-control" rows="4" placeholder="Texto del artículo, referencia bibliográfica, etc."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Publicar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Tarea -->
    <div class="modal fade" id="modalTarea" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-clipboard-list"></i> Nueva Tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formTarea">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="crear_tarea">
                        <input type="hidden" name="id_asignacion" value="<?= $id_asignacion_seleccionada ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Título de la Tarea *</label>
                            <input type="text" name="titulo" class="form-control" required placeholder="Ej: Ejercicios de la Unidad 3">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Instrucciones *</label>
                            <textarea name="descripcion" class="form-control" rows="4" required placeholder="Describe claramente lo que deben hacer los estudiantes..."></textarea>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Entrega *</label>
                                <input type="datetime-local" name="fecha_entrega" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nota Máxima *</label>
                                <input type="number" name="nota_maxima" class="form-control" value="10" min="0" max="100" step="0.1">
                            </div>
                        </div>
                        
                        <div class="mb-3 mt-3">
                            <label class="form-label">Archivo Adjunto (opcional)</label>
                            <input type="text" name="archivo_adjunto" class="form-control" placeholder="URL del archivo o instrucciones de entrega">
                            <small class="text-muted">Puedes subir el archivo a Google Drive, Dropbox, etc. y pegar el enlace</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Crear Tarea</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // ===== Select2 con soporte para modales =====
        function initSelect2($element, parent) {
            $element.select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: parent || $('body'),
                language: {
                    noResults: () => "No se encontraron resultados",
                    searching: () => "Buscando..."
                }
            });
        }
        
        // Inicializar selects generales
        initSelect2($('select.form-select:not([multiple])'));
        
        // Re-inicializar al abrir modal de recurso
        $('#modalRecurso').on('shown.bs.modal', function() {
            const $modal = $(this);
            $modal.find('select.select-recurso').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                initSelect2($(this), $modal);
            });
        });
        
        // Limpiar al cerrar modal
        $('#modalRecurso').on('hidden.bs.modal', function() {
            $(this).find('select').select2('destroy');
            $(this).find('form')[0].reset();
            $('#campo_url_unificado, #campos_contenido').addClass('d-none');
            $('input, textarea').removeClass('is-invalid');
        });
        
        // ===== Mostrar campos según tipo de recurso =====
        function mostrarCamposRecurso(tipo) {
            $('#campo_url_unificado, #campos_contenido').addClass('d-none');
            
            const labelUrl = $('#label_url');
            const inputUrl = $('#input_url_recurso');
            const helpUrl = $('#help_url');
            
            if (['youtube', 'video', 'enlace', 'podcast'].includes(tipo)) {
                $('#campo_url_unificado').removeClass('d-none');
                
                const config = {
                    youtube: { label: 'Enlace de YouTube *', placeholder: 'https://www.youtube.com/watch?v=...', help: 'El video se incrustará automáticamente' },
                    video: { label: 'URL del Video *', placeholder: 'https://ejemplo.com/video.mp4', help: 'Enlace directo al archivo MP4' },
                    podcast: { label: 'URL del Podcast *', placeholder: 'https://ejemplo.com/audio.mp3', help: 'Enlace al archivo de audio' },
                    enlace: { label: 'URL / Enlace *', placeholder: 'https://...', help: 'Enlace a recurso externo' }
                };
                
                const c = config[tipo] || config.enlace;
                labelUrl.text(c.label);
                inputUrl.attr('placeholder', c.placeholder).prop('required', true);
                helpUrl.text(c.help);
            } else {
                $('#input_url_recurso').prop('required', false);
            }
            
            if (['articulo', 'referencia', 'revista'].includes(tipo)) {
                $('#campos_contenido').removeClass('d-none');
            }
        }
        
        // Conectar con el select
        $('select[name="tipo_recurso"]').on('change', function() {
            mostrarCamposRecurso($(this).val());
        });
        
        // Quick actions con tipo pre-seleccionado
        document.querySelectorAll('.quick-action').forEach(btn => {
            btn.addEventListener('click', function() {
                const tipo = this.dataset.tipo;
                if (tipo) {
                    const select = $('select[name="tipo_recurso"]');
                    select.val(tipo).trigger('change');
                    $('#modalRecurso').modal('show');
                }
            });
        });
        
        // ===== Validación del formulario de recurso =====
        $('#formRecurso').on('submit', function(e) {
            const tipo = $('select[name="tipo_recurso"]').val();
            const urlInput = $('#input_url_recurso');
            const requiereUrl = ['youtube', 'video', 'enlace', 'podcast'];
            
            // Validar URL si es requerida
            if (requiereUrl.includes(tipo) && !urlInput.val().trim()) {
                e.preventDefault();
                urlInput.addClass('is-invalid').focus();
                return false;
            }
            
            // Validar título
            const titulo = $('input[name="titulo"]').val().trim();
            if (!titulo) {
                e.preventDefault();
                $('input[name="titulo"]').addClass('is-invalid').focus();
                return false;
            }
            
            return true;
        });
        
        // ===== Validación del formulario de tarea =====
        $('#formTarea').on('submit', function(e) {
            const titulo = $('input[name="titulo"]').val().trim();
            const descripcion = $('textarea[name="descripcion"]').val().trim();
            
            if (!titulo || !descripcion) {
                e.preventDefault();
                if (!titulo) $('input[name="titulo"]').addClass('is-invalid').focus();
                if (!descripcion) $('textarea[name="descripcion"]').addClass('is-invalid').focus();
                return false;
            }
            return true;
        });
        
        // Limpiar errores al escribir
        $('input, textarea, select').on('input change', function() {
            $(this).removeClass('is-invalid');
        });
        
        // Sidebar responsive
        if (window.innerWidth < 992) {
            $('#sidebar').addClass('active');
        }
        
        // Función global para ver perfil de estudiante
        window.verPerfilEstudiante = function(id) {
            console.log('Ver estudiante ID:', id);
            // Aquí puedes implementar la lógica para ver el perfil
        };
    });
    </script>
</body>
</html>