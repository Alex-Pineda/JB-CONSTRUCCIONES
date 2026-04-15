<?php 

require_once __DIR__ . '/../../../config/data.php'; 

if(isset($_GET['error'])): ?>
<div class="fixed top-4 right-4 bg-red-100 text-red-700 px-4 py-2 rounded shadow">
    <?= htmlspecialchars($_GET['error']) ?>
</div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - JB Constructores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .fade-in { animation: fadeIn 1s ease forwards; opacity: 0; }
        @keyframes fadeIn { to { opacity: 1; } }
        input:focus, select:focus, button:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(30, 120, 90, 0.5);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-b from-green-100 via-blue-50 to-blue-100">

<div class="relative max-w-2xl w-full bg-white rounded-3xl shadow-2xl p-10 fade-in overflow-hidden">

    <h2 class="text-center text-2xl font-bold text-green-800 mb-2">Registro de Usuario</h2>
    <p class="text-center text-gray-600 mb-8">Complete sus datos para registrarse</p>

    <form action="<?= BASE_URL ?>app/controllers/authcontroller.php" method="POST" class="relative z-10">

        <input type="hidden" name="accion" value="registro">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <input type="text" name="nombres" required
                    class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500"
                    placeholder="Nombres">
            </div>

            <div>
                <input type="text" name="apellidos" required
                    class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500"
                    placeholder="Apellidos">
            </div>

            <div>
                <select name="tipo_documento" required
                    class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500">
                    <option value="">Tipo de Documento</option>
                    <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                    <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                    <option value="Pasaporte">Pasaporte</option>
                    <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                </select>
            </div>

            <div>
                <input type="text" name="numero_documento" required
                    class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500"
                    placeholder="Ingrese su número de documento">
            </div>

            <div>
                <input type="tel" name="celular" required
                    class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500"
                    placeholder="Número de Teléfono">
            </div>

            <div>
                <input type="email" name="correo" required
                    class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500"
                    placeholder="Correo Electrónico">
            </div>

            <div>
                <input type="text" name="nombre_usuario" required
                    class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500"
                    placeholder="Crea un usuario">
            </div>

            <div>
                <input type="password" name="password" required
                    class="w-full border border-green-300 rounded-xl p-3 focus:ring-2 focus:ring-green-500"
                    placeholder="Crea una contraseña">
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center text-gray-700">
                    <input type="checkbox" name="acepta_terminos" value="1"
                        class="rounded border-gray-300 text-green-500 focus:ring-green-500" required>
                    <span class="ml-2">
                        Acepto el tratamiento de datos personales según la política de privacidad
                    </span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-center gap-4">
            <button type="submit"
                class="bg-green-600 hover:bg-blue-600 text-white py-3 rounded-xl font-semibold transition transform hover:scale-105 min-w-[10rem]">
                Registrarse
            </button>

            <a href="<?= BASE_URL ?>index.php"
                class="bg-gray-600 hover:bg-gray-700 text-white py-3 rounded-xl font-semibold transition transform hover:scale-105 min-w-[10rem] text-center">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    // Variable global en JavaScript con el valor de PHP
    const BASE_URL = "<?= BASE_URL ?>";
</script>

</body>
</html>