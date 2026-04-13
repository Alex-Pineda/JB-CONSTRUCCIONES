<?php
  require_once __DIR__ . '/config/session.php';

  // Validar si está logueado
  if (!isset($_SESSION['usuario'])) {
      header("Location: /JB-CONSTRUCCIONES/index.php");
      exit();
  }

  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // Verificar sesión activa
  if (!isset($_SESSION['usuario'])) {
      header("Location: /JB-CONSTRUCCIONES/app/views/auth/login.php");
      exit();
  }

  // Verificar rol administrador
  if ($_SESSION['usuario']['rol'] !== 'administrador') {
      header("Location: /JB-CONSTRUCCIONES/index.php");
      exit();
  }

  $usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin | JB-CONSTRUCCIONES</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="/JB-CONSTRUCCIONES/assets/img/favicon.ico" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/JB-CONSTRUCCIONES/assets/css/style.css" />
</head>

<body class="bg-gray-100 font-['Roboto']">

<div class="flex min-h-screen">

  <!-- Sidebar real en PHP -->
  <?php require_once __DIR__ . '/app/views/layout/sidebar.php';?>


  <!-- Main -->
  <main class="flex-1 p-2">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-3xl font-bold">Dashboard</h2>

    <span class="text-gray-700 text-3xl font-medium">
        Bienvenido, <?= htmlspecialchars($usuario['nombre_usuario']); ?>
    </span>

        <a href="/JB-CONSTRUCCIONES/logout.php"
           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
          Cerrar sesión
        </a>
      </div>

    <!-- Panel de estadísticas -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mb-4" id="tarjetas-estadisticas">
    </div>

    <!-- Visitas Pendientes -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Visitas Pendientes</h3>

      <div id="lista-visitas" class="overflow-x-auto"></div>

      <div id="mensaje-vacio" class="text-gray-500 text-center py-4 hidden">
        No hay visitas pendientes por ahora.
      </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="fixed bottom-6 right-6 bg-green-500 text-white px-4 py-2 rounded shadow-lg hidden transition duration-300"></div>

  </main>

</div>

<!-- ================= SCRIPTS ================= -->

<script>
const estadisticas = [
 // { titulo: "Gestión de Usuarios", cantidad: 120, icono: "👥", url: "/JB-CONSTRUCCIONES/app/views/usuarios.html" },
  { titulo: "Gestión de Proyectos", cantidad: 18, icono: "🏗️", url: "/JB-CONSTRUCCIONES/app/views/gestionproyectos.php" },
 // { titulo: "Cotizaciones Generadas", cantidad: 40, icono: "💰", url: "/JB-CONSTRUCCIONES/app/views/admin/cotizaciones.html" },
 // { titulo: "Estado de Pagos", cantidad: 14, icono: "💳", url: "/JB-CONSTRUCCIONES/app/views/admin/pagos.html" },
 // { titulo: "Facturación Emitida", cantidad: 26, icono: "🧾", url: "/JB-CONSTRUCCIONES/app/views/admin/facturacion.html" },
 // { titulo: "Administrar Portafolios", cantidad: 48, icono: "📁", url: "/JB-CONSTRUCCIONES/app/views/portafolio.html" },
 // { titulo: "Moderación de Reseñas", cantidad: 19, icono: "⭐", url: "/JB-CONSTRUCCIONES/app/views/moderacionResenas.html" },
 // { titulo: "Gestión de Contenido", cantidad: 25, icono: "📝", url: "/JB-CONSTRUCCIONES/app/views/admin/blogs.html" }
];



const visitasPendientes = [
  {
    nombre: "Juan Pérez",
    documento: "123456789",
    correo: "juan.perez@example.com",
    contacto: "3001234567",
    ubicacion: "Antioquia - Medellín",
    direccion: "Cra 45 # 12-34",
    fechaVisita: "2025-10-20"
  }
];

const contenedorTarjetas = document.getElementById("tarjetas-estadisticas");

estadisticas.forEach(item => {
  const tarjeta = document.createElement("a");
  tarjeta.href = item.url;
  tarjeta.className = `
    bg-white rounded-lg shadow p-2 border border-gray-200
    hover:bg-indigo-100 hover:ring-2 hover:ring-indigo-400 transition
    flex flex-col items-center text-center
  `;

  tarjeta.innerHTML = `
    <div class="text-3xl">${item.icono}</div>
    <h3 class="text-gray-600 font-semibold">${item.titulo}</h3>
    <p class="text-3xl font-bold text-gray-800">${item.cantidad}</p>
  `;

  contenedorTarjetas.appendChild(tarjeta);
});

const listaContainer = document.getElementById('lista-visitas');
const mensajeVacio = document.getElementById('mensaje-vacio');

function cargarVisitas(data) {
  listaContainer.innerHTML = '';

  if (!data.length) {
    mensajeVacio.classList.remove('hidden');
    return;
  }

  mensajeVacio.classList.add('hidden');

  const tabla = document.createElement('table');
  tabla.className = 'min-w-full text-sm divide-y divide-gray-200';

  tabla.innerHTML = `
    <thead class="bg-gray-100 text-gray-700 text-left">
      <tr>
        <th class="px-4 py-2">Nombre</th>
        <th class="px-4 py-2">Documento</th>
        <th class="px-4 py-2">Correo</th>
        <th class="px-4 py-2">Contacto</th>
        <th class="px-4 py-2">Ubicación</th>
        <th class="px-4 py-2">Dirección</th>
        <th class="px-4 py-2">Fecha Visita</th>
        <th class="px-4 py-2 text-center">Acciones</th>
      </tr>
    </thead>
    <tbody id="tbody-visitas"></tbody>
  `;

  listaContainer.appendChild(tabla);
}

cargarVisitas(visitasPendientes);
</script>

<script src="/JB-CONSTRUCCIONES/Scripts/main.js"></script>

</body>
</html>