<?php
$config = require __DIR__ . '/config.php';
$logsFile = $config['logs_file'];
$ADMIN_USER = $config['admin_user'];
$ADMIN_PASS = $config['admin_pass'];

if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $ADMIN_USER ||
    $_SERVER['PHP_AUTH_PW'] !== $ADMIN_PASS) {
    header('WWW-Authenticate: Basic realm="CTF Admin"');
    header('HTTP/1.0 401 Unauthorized');
    exit('Acceso denegado');
}

$logs = [];
if (file_exists($logsFile)) {
    $logs = json_decode(file_get_contents($logsFile), true);
    if (!is_array($logs)) $logs = [];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel de Administración - CTF Hint Full</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h1 class="mb-4 text-center">📋 Registro de Actividad</h1>
      <?php if (empty($logs)): ?>
        <div class="alert alert-secondary text-center">No hay registros aún.</div>
      <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Correo</th>
              <th>Tipo</th>
              <th>Detalle</th>
              <th>Fecha (UTC)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($logs as $i => $r): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['type']) ?></td>
                <td>
                  <?php
                    if ($r['type'] === 'hint') echo "Pista " . htmlspecialchars($r['hint']);
                    elseif ($r['type'] === 'flag') echo "<strong>" . htmlspecialchars($r['flag']) . "</strong>";
                  ?>
                </td>
                <td><?= htmlspecialchars($r['timestamp']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
      <div class="text-center mt-3">
        <a href="index.php" class="btn btn-outline-primary">Volver al inicio</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
