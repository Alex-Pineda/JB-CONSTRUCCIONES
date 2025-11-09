<?php
// ─────────────────────────────────────────────
//  RESTABLECER CONTRASEÑA - JB CONSTRUCTORES
// ─────────────────────────────────────────────

// Capturamos el token desde el enlace recibido
$token = isset($_GET['token']) ? $_GET['token'] : null;

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevaContrasena = $_POST['nuevaContrasena'];
    $confirmarContrasena = $_POST['confirmarContrasena'];

    if ($nuevaContrasena === $confirmarContrasena) {
        // En esta parte se conectará la base de datos más adelante.
        // Ejemplo de actualización (una vez tengamos la tabla de usuarios):
        // $sql = "UPDATE usuarios SET contrasena = '$hash' WHERE token = '$token'";
        // mysqli_query($conexion, $sql);

        $mensaje = "✅ Tu contraseña se ha restablecido correctamente.";
    } else {
        $mensaje = "❌ Las contraseñas no coinciden. Intenta nuevamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restablecer Contraseña - JB Constructores</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Roboto', sans-serif; }
    .fade-in { animation: fadeIn 1s ease forwards; opacity: 0; }
    @keyframes fadeIn { to { opacity: 1; } }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-green-100 via-blue-50 to-blue-100 px-4">

  <div class="bg-white shadow-2xl rounded-3xl p-8 w-full max-w-md fade-in">
    <h2 class="text-center text-2xl font-bold text-green-800 mb-6">Restablecer Contraseña</h2>
    
    <?php if (!empty($mensaje)): ?>
      <div class="mb-4 text-center font-medium <?= str_contains($mensaje, '❌') ? 'text-red-600' : 'text-green-700' ?>">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>

    <?php if (!$token): ?>
      <p class="text-center text-red-600 font-semibold mb-4">❌ Enlace inválido o expirado.</p>
    <?php else: ?>
      <p class="text-gray-700 text-center mb-6">
        Ingresa tu nueva contraseña para la cuenta vinculada.
      </p>

      <form method="POST" class="space-y-4">
        <input type="password" name="nuevaContrasena" placeholder="Nueva contraseña"
          class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 transition" required />
        
        <input type="password" name="confirmarContrasena" placeholder="Confirmar contraseña"
          class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500 transition" required />

        <button type="submit"
          class="w-full bg-green-600 hover:bg-blue-600 text-white py-3 rounded-xl font-semibold transition transform hover:scale-105">
          Restablecer Contraseña
        </button>
      </form>
    <?php endif; ?>

    <div class="mt-6 text-center">
      <a href="inicioSesion.html" class="text-green-700 hover:text-blue-700 font-medium">
        Volver al inicio de sesión
      </a>
    </div>
  </div>

</body>
</html>
<?php
