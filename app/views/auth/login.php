<?php
session_start();

if(isset($_SESSION['idusuario'])) {
    header("Location: ../../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?></title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <!-- Estilos internos originales -->
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

  <!-- Contenedor principal -->
  <div class="relative max-w-sm w-full bg-white rounded-3xl shadow-2xl p-10 fade-in overflow-hidden">

    <h2 class="text-center text-2xl font-bold text-green-800 mb-6">Iniciar Sesión</h2>
    <p class="text-center text-gray-600 mb-8">Ingrese sus credenciales para acceder</p>

    <?php if(isset($_GET['error'])): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
            Usuario o contraseña incorrectos
        </div>
    <?php endif; ?>

    <form action="../../controllers/authcontroller.php" method="POST" autocomplete="off">

      <div class="mb-5">
        <label class="block text-gray-700 font-medium mb-2">Nombre de Usuario</label>
        <input type="text" name="usuario"
          class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 transition" required />
      </div>

      <div class="mb-5">
        <label class="block text-gray-700 font-medium mb-2">Contraseña</label>
        <input type="password" name="contrasena"
          class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 transition" required />
      </div>

      <div class="flex justify-center mb-6">
        <a href="#" onclick="abrirModal()" class="text-green-700 hover:text-green-900 text-sm font-medium text-center">
          ¿Olvidó su usuario o contraseña?
        </a>
      </div>

      <button type="submit"
        class="w-full bg-green-600 hover:bg-blue-600 text-white py-3 rounded-xl font-semibold transition transform hover:scale-105">
        Iniciar Sesión
      </button>

      <div class="mt-6 text-center">
        <p class="text-gray-600 mb-2">¿No tienes una cuenta?</p>
        <a href="registro.php" class="text-green-700 hover:text-blue-700 font-medium">Regístrate aquí</a>
      </div>

    </form>
  </div>

  <!-- Modal recuperación -->
  <div id="modalRecuperacion" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-96 relative">
      <button onclick="cerrarModal()" class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-lg font-bold">✕</button>

      <h3 class="text-xl font-semibold text-green-700 mb-4 text-center">Recuperar Cuenta</h3>

      <p class="text-gray-600 text-sm text-center mb-4">
        Ingrese su correo electrónico registrado para recibir el enlace de recuperación de su cuenta.
      </p>

    <form action="../../controllers/authcontroller.php" method="POST" class="space-y-4">

    <input type="hidden" name="accion" value="recuperacion">

    <input type="email" name="correoRecuperacion"
        placeholder="Correo registrado"
        class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 transition" required />

    <button type="submit"
        class="w-full bg-green-600 hover:bg-blue-600 text-white py-3 rounded-xl font-semibold transition">
        Enviar enlace de recuperación
    </button>
    </form>

    </div>
  </div>

  <script>
    function abrirModal() {
      document.getElementById('modalRecuperacion').classList.remove('hidden');
    }
    function cerrarModal() {
      document.getElementById('modalRecuperacion').classList.add('hidden');
    }
  </script>

</body>
</html>