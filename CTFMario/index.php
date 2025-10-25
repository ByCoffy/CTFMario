<?php
$config = require __DIR__ . '/config.php';
$uploadsDir = basename($config['uploads_dir']);
$data = [];

if (file_exists($config['hints_file'])) {
    $data = json_decode(file_get_contents($config['hints_file']), true);
}
$challenge = $data['challenge'] ?? [];
$hints = $data['hints'] ?? [];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($challenge['title'] ?? 'Reto CTF') ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

  <?php if (empty($challenge) || empty($challenge['enabled']) || $challenge['enabled'] === false): ?>
    <div class="alert alert-warning text-center">
      <h3>🚧 Reto en preparación</h3>
      <p>Este reto aún no está disponible. Vuelve a intentarlo más tarde.</p>
    </div>
  <?php else: ?>

  <div class="card shadow-sm mx-auto" style="max-width: 700px;">
    <div class="card-body">
      <h1 class="text-center mb-4"><?= htmlspecialchars($challenge['title']) ?></h1>

      <?php if (!empty($challenge['description'])): ?>
        <div class="alert alert-info">
          <p><?= nl2br(htmlspecialchars($challenge['description'])) ?></p>
        </div>
      <?php endif; ?>

      <?php if (!empty($challenge['objectives'])): ?>
        <div class="alert alert-secondary">
          <h5>🎯 Objetivos del reto</h5>
          <ul class="mb-0">
            <?php foreach ($challenge['objectives'] as $obj): ?>
              <li><?= htmlspecialchars($obj) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if (!empty($challenge['resources'])): ?>
        <div class="alert alert-warning">
          <h5>📎 Documentación disponible</h5>
          <ul class="mb-0">
            <?php foreach ($challenge['resources'] as $res): 
              $desc = htmlspecialchars($res['description'] ?? '');
              $name = htmlspecialchars($res['name'] ?? '');
              $file = htmlspecialchars($res['file'] ?? '');
              $path = "{$uploadsDir}/{$file}";
            ?>
              <li class="mb-2">
                <?= $desc ? "<strong>{$desc}</strong><br>" : '' ?>
                <?php if ($file && file_exists(__DIR__ . "/{$path}")): ?>
                  <a class="btn btn-outline-primary btn-sm mt-1" href="<?= $path ?>" target="_blank">Descargar <?= $name ?></a>
                <?php else: ?>
                  <span class="text-muted small">Archivo no disponible</span>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <hr class="my-4">

      <?php if (empty($hints)): ?>
        <div class="alert alert-secondary text-center">
          <p>No hay pistas disponibles actualmente. Consulta más adelante.</p>
        </div>
      <?php else: ?>

      <form method="post" action="process.php">
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" name="email" id="email" class="form-control text-center" required placeholder="tu@correo.com">
        </div>

        <div class="mb-3">
          <label for="action" class="form-label">Acción</label>
          <select name="action" id="action" class="form-select text-center" onchange="toggleInputs()">
            <option value="ask_hint">Solicitar una pista</option>
            <option value="submit_flag">Entregar bandera final</option>
          </select>
        </div>

        <div id="hintDiv" class="mb-3">
          <label for="hint" class="form-label">Selecciona una pista</label>
          <select name="hint" id="hint" class="form-select text-center">
            <?php foreach ($hints as $h): ?>
              <option value="<?= $h['id'] ?>">Pista <?= $h['id'] ?> — <?= htmlspecialchars($h['title']) ?></option>
            <?php endforeach; ?>
            <option value="all">Mostrar todas</option>
          </select>
        </div>

        <div id="flagDiv" class="mb-3" style="display:none;">
          <label for="flag" class="form-label">Introduce tu FLAG</label>
          <input type="text" name="flag" id="flag" class="form-control text-center" placeholder="FLAG{mi_bandera}">
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </form>

      <?php endif; ?>

      <hr class="my-4">
      <div class="alert alert-secondary small">
        <strong>Instrucciones:</strong> Cada solicitud se registrará con tu correo y la fecha. Puedes pedir pistas o entregar tu bandera final.
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
function toggleInputs() {
  const action = document.getElementById('action').value;
  document.getElementById('hintDiv').style.display = action === 'ask_hint' ? 'block' : 'none';
  document.getElementById('flagDiv').style.display = action === 'submit_flag' ? 'block' : 'none';
}
</script>
</body>
</html>
