<?php
  require_once __DIR__ . '/config/data.php';
  require_once __DIR__ . '/config/session.php';
  require_once __DIR__ . '/app/controllers/visita.php';


$data = new Data();
$conn = $data->getConnection();


  // Validar si está logueado
  if (!isset($_SESSION['usuario'])) {
      header("Location: " . BASE_URL . "index.php");
      exit();
  }

  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // Verificar sesión activa
  if (!isset($_SESSION['usuario'])) {
      header("Location: " . BASE_URL . "app/views/auth/login.php");
      exit();
  }

  // Verificar rol administrador
  if ($_SESSION['usuario']['rol'] !== 'administrador') {
      header("Location: " . BASE_URL . "index.php");
      exit();
  }

  $usuario = $_SESSION['usuario'];


  /* =========================
   TOTAL PROYECTOS
========================= */
$sqlProyectos = "SELECT COUNT(*) as total FROM proyecto";
$stmtProyectos = $conn->prepare($sqlProyectos);
$stmtProyectos->execute();

$totalProyectos = $stmtProyectos->fetch(PDO::FETCH_ASSOC)['total'];


/* =========================
   TOTAL COTIZACIONES
========================= */
$sqlCotizaciones = "SELECT COUNT(*) as total FROM cotizacion";
$stmtCotizaciones = $conn->prepare($sqlCotizaciones);
$stmtCotizaciones->execute();

$totalCotizaciones = $stmtCotizaciones->fetch(PDO::FETCH_ASSOC)['total'];


/* =========================
   TOTAL USUARIOS
========================= */
$sqlUsuarios = "SELECT COUNT(*) as total FROM usuario";
$stmtUsuarios = $conn->prepare($sqlUsuarios);
$stmtUsuarios->execute();

$totalUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)['total'];

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
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" />
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

        <a href="<?= BASE_URL ?>logout.php"
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
  
    // Variable global en JavaScript con el valor de PHP
    const BASE_URL = "<?= BASE_URL ?>";
</script>

<script>
const estadisticas = [

  {
    titulo: "Gestión de Proyectos",
    cantidad: <?= $totalProyectos ?>,
    icono: "🏗️",
    url: "/JB-CONSTRUCCIONES/app/views/gestionproyectos.php"
  },

  {
    titulo: "Cotizaciones Totales",
    cantidad: <?= $totalCotizaciones ?>,
    icono: "💰",
    url: " "
  },

  {
    titulo: "Usuarios Registrados",
    cantidad: <?= $totalUsuarios ?>,
    icono: "👥",
    url: " "
  }

];

const contenedorTarjetas = document.getElementById("tarjetas-estadisticas");

estadisticas.forEach(item => {

  const tarjeta = document.createElement("a");

  tarjeta.href = item.url;

  tarjeta.className = `
    bg-white rounded-lg shadow p-2 border border-gray-200
    hover:bg-indigo-100 hover:ring-2 hover:ring-indigo-400 transition cursor-pointer
    flex flex-col items-center text-center
  `;

  tarjeta.innerHTML = `
    <div class="text-3xl">${item.icono}</div>

    <h3 class="text-gray-600 font-semibold">
      ${item.titulo}
    </h3>

    <p class="text-3xl font-bold text-gray-800">
      ${item.cantidad}
    </p>
  `;

  contenedorTarjetas.appendChild(tarjeta);
});

</script>

<script>

const visitasPendientes = <?= json_encode($visitasPendientes ?? []) ?>;
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

  let filas = '';

  data.forEach(visita => {

    filas += `
      <tr class="border-b hover:bg-gray-50">

        <td class="px-4 py-2">
          ${visita.nombres} ${visita.apellidos}
        </td>

        <td class="px-4 py-2">
          ${visita.numero_documento}
        </td>

        <td class="px-4 py-2">
          ${visita.correo}
        </td>

        <td class="px-4 py-2">
          ${visita.contacto}
        </td>

        <td class="px-4 py-2">
          ${visita.ubicacion}
        </td>

        <td class="px-4 py-2">
          ${visita.direccion}
        </td>

        <td class="px-4 py-2">
          ${visita.fecha_visita ?? 'Sin fecha'}
        </td>

<td class="px-4 py-2 text-center">

  <div class="flex gap-2 justify-center">

    <button
      class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
      onclick="confirmarVisita(${visita.idcotizacion})"
    >
      Confirmar
    </button>

    <button
      class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
      onclick="cancelarVisita(${visita.idcotizacion})"
    >
      Cancelar
    </button>

  </div>

</td>
    `;
  });

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

    <tbody>
      ${filas}
    </tbody>
  `;

  listaContainer.appendChild(tabla);
}

cargarVisitas(visitasPendientes);


function confirmarVisita(idcotizacion)
{
   
  fetch(`${BASE_URL}app/controllers/visita.php`, {

        method: 'POST',

        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },

        body:
            `accion=confirmar&idcotizacion=${idcotizacion}`
    })

    .then(response => response.text())

    .then(resultado => {

        if (resultado === 'ok') {

            window.location.href =
                `${BASE_URL}app/views/nuevoProyecto.php?idcotizacion=${idcotizacion}`;

        } else {

            alert('Error al confirmar visita');
        }
    });
}



function cancelarVisita(idcotizacion)
{
    
    fetch(`${BASE_URL}app/controllers/visita.php`, {

        method: 'POST',

        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },

        body:
            `accion=cancelar&idcotizacion=${idcotizacion}`
    })

    .then(response => response.text())

    .then(resultado => {

        if (resultado === 'ok') {

            location.reload();

        } else {

            alert('Error al cancelar visita');
        }
    });
}

</script>

<script src="<?= BASE_URL ?>Scripts/main.js"></script>

</body>
</html>