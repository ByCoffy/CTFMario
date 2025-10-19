<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$config = require __DIR__ . '/config.php';
$hintsFile  = $config['hints_file'];
$logsFile   = $config['logs_file'];
$uploadsDir = basename($config['uploads_dir']);

function load_json($file) {
    if (!file_exists($file)) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function append_log($file, $entry) {
    $logs = [];
    if (file_exists($file)) {
        $logs = json_decode(file_get_contents($file), true);
        if (!is_array($logs)) $logs = [];
    }
    $logs[] = $entry;
    file_put_contents($file, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$email = trim($_POST['email'] ?? '');
$action = $_POST['action'] ?? '';
$hintRequested = $_POST['hint'] ?? '';
$flag = trim($_POST['flag'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('<div class="alert alert-danger text-center p-4">Correo inválido. <a href="index.php">Volver</a></div>');
}

$data = load_json($hintsFile);
$hints = $data['hints'] ?? [];

if ($action === 'ask_hint') {
    $entry = [
        'type' => 'hint',
        'email' => $email,
        'hint' => $hintRequested,
        'timestamp' => gmdate('Y-m-d\TH:i:s\Z')
    ];
    append_log($logsFile, $entry);

    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<div class="container py-5">';

    if (empty($hints)) {
        echo "<div class='alert alert-secondary text-center'><h4>No hay pistas disponibles.</h4><a href='index.php' class='btn btn-secondary mt-3'>Volver</a></div>";
        exit;
    }

    if ($hintRequested === 'all') {
        echo "<div class='alert alert-info text-center'><h3>Todas las pistas</h3></div>";
        foreach ($hints as $h) showHint($h, $uploadsDir);
    } else {
        foreach ($hints as $h) {
            if ((string)$h['id'] === (string)$hintRequested) {
                showHint($h, $uploadsDir);
                break;
            }
        }
    }

    echo '<a href="index.php" class="btn btn-secondary mt-3">Volver</a>';
    echo '</div>';
    exit;
}

if ($action === 'submit_flag') {
    if ($flag === '') {
        exit('<div class="alert alert-danger text-center p-4">Debes introducir una FLAG. <a href="index.php">Volver</a></div>');
    }

    $entry = [
        'type' => 'flag',
        'email' => $email,
        'flag' => $flag,
        'timestamp' => gmdate('Y-m-d\TH:i:s\Z')
    ];
    append_log($logsFile, $entry);

    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<div class="container py-5">';
    echo '<div class="alert alert-success text-center"><h4>¡Bandera recibida!</h4><p>Tu FLAG se ha registrado correctamente.</p></div>';
    echo '<a href="index.php" class="btn btn-secondary mt-3">Volver</a>';
    echo '</div>';
    exit;
}

function showHint($h, $uploadsDir) {
    echo "<div class='alert alert-light border mb-3'>";
    echo "<h5>{$h['title']}</h5>";
    if ($h['type'] === 'text') {
        echo "<p>" . nl2br(htmlspecialchars($h['content'])) . "</p>";
    } elseif ($h['type'] === 'file') {
        $desc = htmlspecialchars($h['description'] ?? '');
        $file = htmlspecialchars($h['content']);
        echo "<p>{$desc}</p>";
        echo "<a href='{$uploadsDir}/{$file}' target='_blank' class='btn btn-outline-primary btn-sm'>Descargar {$file}</a>";
    }
    echo "</div>";
}
