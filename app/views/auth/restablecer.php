<?php
require_once '../../models/usuario.php';

$token = $_GET['token'] ?? null;

if (!$token) {
    die("Token inválido.");
}

$modelo = new Usuario();
$usuario = $modelo->validarToken($token);

if (!$usuario) {
    die("El enlace no es válido o ha expirado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restablecer Contraseña</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <style>
    body { font-family: 'Roboto', sans-serif; }
    .fade-in { animation: fadeIn 1s ease forwards; opacity: 0; }
    @keyframes fadeIn { to { opacity: 1; } }
    input:focus, button:focus {
      outline: none;
      box-shadow: 0 0 8px rgba(30,120,90,0.5);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-b from-green-100 via-blue-50 to-blue-100">

  <div class="relative max-w-sm w-full bg-white rounded-3xl shadow-2xl p-10 fade-in overflow-hidden">

    <h2 class="text-center text-2xl font-bold text-green-800 mb-6">
      Restablecer Contraseña
    </h2>

    <p class="text-center text-gray-600 mb-8">
      Hola <b><?= htmlspecialchars($usuario['nombre_usuario']) ?></b>, ingresa tu nueva contraseña.
    </p>

    <?php if(isset($_GET['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
        Las contraseñas no coinciden.
      </div>
    <?php endif; ?>

    <form action="../../controllers/authcontroller.php" method="POST" autocomplete="off">

      <input type="hidden" name="accion" value="restablecer">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

      <div class="mb-5">
        <label class="block text-gray-700 font-medium mb-2">Nueva Contraseña</label>
        <input type="password" name="nueva_contrasena"
          class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 transition"
          required />
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 font-medium mb-2">Confirmar Contraseña</label>
        <input type="password" name="confirmar_contrasena"
          class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 transition"
          required />
      </div>

      <button type="submit"
        class="w-full bg-green-600 hover:bg-blue-600 text-white py-3 rounded-xl font-semibold transition transform hover:scale-105">
        Actualizar Contraseña
      </button>

      <div class="mt-6 text-center">
        <a href="login.php" class="text-green-700 hover:text-blue-700 font-medium">
          Volver al inicio de sesión
        </a>
      </div>

    </form>

  </div>

</body>
</html>