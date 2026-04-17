<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../controllers/proyectocontroller.php';

// Validar si está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_idrol'] ?? null; // asegúrate que lo tengas en sesión
$usuario_id = $usuario['idusuario'];


$controller = new ProyectoController();

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol_idrol'] ?? null;
$usuario_id = $usuario['idusuario'];

if ($rol == 2) {
    // CLIENTE → SOLO SUS PROYECTOS
    $proyectos = $controller->listarPorUsuario($usuario_id);
} else {
    // ADMIN → TODOS
    $proyectos = $controller->listar();
}

/* ===== ESTADÍSTICAS ===== */
$total = count($proyectos);
$pendiente = 0;
$ejecucion = 0;
$pausado = 0;
$finalizado = 0;
$cancelado = 0;

foreach ($proyectos as $proy) {
    $estado = strtolower($proy['estado_proyecto']);
    if ($estado === 'pendiente') $pendiente++;
    if ($estado === 'ejecucion') $ejecucion++;
    if ($estado === 'pausado') $pausado++;
    if ($estado === 'finalizado') $finalizado++;
    if ($estado === 'cancelado') $cancelado++;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Proyectos</title>
  <link rel="icon" href="<?= BASE_URL ?>assets/img/favicon.ico" type="image/x-icon" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(135deg, #e8eaf6 0%, #f4f5f7 100%);
    }
  </style>

</head>


<header class="border-b-[3px] border-[#161a7e] py-4 px-6 shadow-sm flex justify-between items-center">
    <h1 class="text-xl font-semibold text-gray-700">Gestión Proyectos</h1>
    <button onclick="window.location.href='<?= BASE_URL ?>admin.php'"
    class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Inicio</button>
</header>

<div class="flex flex-col sm:flex-row gap-3 mt-8 mb-6 items-center justify-between px-4">

    <input id="buscarProyecto" type="text" placeholder="Buscar por nombre..."
        class="w-full sm:w-2/2 border border-[#161a7e]/40 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#161a7e]">

    <select id="estadoProyecto"
        class="w-full sm:w-1/3 border border-[#161a7e]/40 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-[#161a7e]">
        <option value="">Todos los estados</option>
        <option value="pendiente">Pendiente</option>
        <option value="ejecucion">Ejecución</option>
        <option value="pausado">Pausado</option>
        <option value="finalizado">Finalizado</option>
        <option value="cancelado">Cancelado</option>
    </select>

    <?php if ($rol != 2): ?>
    <div class="flex gap-2">
        <button onclick="window.location.href='<?= BASE_URL ?>app/views/nuevoProyecto.php'"
            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-800">
            Agregar
        </button>

        <button class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-800">
            Exportar
        </button>
    </div>
    <?php endif; ?>

</div>


<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 px-4 mt-6">
    

<?php
$stats = [
["Total",$total,"bg-white"],
["Pendiente",$pendiente,"bg-green-100"],
["Ejecución",$ejecucion,"bg-yellow-100"],
["Pausado",$pausado,"bg-blue-100"],
["Finalizado",$finalizado,"bg-indigo-100"],
["Cancelado",$cancelado,"bg-red-100"]
];

foreach($stats as $s):
?>

<div class="<?= $s[2] ?> rounded-lg p-4 shadow text-center">
<p class="text-sm text-gray-600"><?= $s[0] ?></p>
<p class="text-xl font-bold"><?= $s[1] ?></p>
</div>

<?php endforeach; ?>
</div>

<div id="contenedorCards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 px-4 py-6">
    
<?php if (!empty($proyectos)): ?>
<?php foreach ($proyectos as $p): 

$estado = strtolower($p['estado_proyecto']);

$badgeClass = match($estado) {
'pendiente' => 'bg-green-100 text-green-700 border-green-300',
'ejecucion' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
'pausado' => 'bg-blue-100 text-blue-700 border-blue-300',
'finalizado' => 'bg-indigo-100 text-indigo-700 border-indigo-300',
'cancelado' => 'bg-red-100 text-red-700 border-red-300',
default => 'bg-gray-100 text-gray-700 border-gray-300'
};
?>

<div class="card-proyecto border rounded-xl bg-white p-4 shadow-md hover:shadow-lg transition"
     data-nombre="<?= strtolower($p['nombre_proyecto']) ?>"
     data-cliente="<?= strtolower($p['cliente_nombre']) ?>"
     data-telefono="<?= strtolower($p['cliente_telefono']) ?>"
     data-correo="<?= strtolower($p['cliente_correo']) ?>"
     data-direccion="<?= strtolower($p['direccion']) ?>"
     data-estado="<?= strtolower($p['estado_proyecto']) ?>">
     
    <!-- CABECERA -->
    <div>
        <div class="flex justify-between items-start border-b pb-2 mb-3">
            <h3 class="font-semibold text-[#161a7e] leading-tight">
                <?= htmlspecialchars($p['nombre_proyecto']) ?>
            </h3>

            <span class="text-xs px-2 py-1 rounded-full border <?= $badgeClass ?>">
                <?= htmlspecialchars($p['estado_proyecto']) ?>
            </span>
        </div>

        <!-- CUERPO -->
        <div class="text-sm space-y-2">

            <div class="flex justify-between items-start gap-2">
                <div>
                    <p><strong>Cliente:</strong> 
                        <?= htmlspecialchars($p['cliente_nombre']) ?>
                    </p>

                    <p><strong>Contacto:</strong> 
                        <?= htmlspecialchars($p['cliente_telefono']) ?>
                    </p>
                </div>

                <a href="https://wa.me/57<?= preg_replace('/[^0-9]/', '', $p['cliente_telefono']) ?>"
                   target="_blank"
                   class="flex-shrink-0">
                    <img src="<?= BASE_URL ?>assets/img/whatsapp-fill.svg" 
                         alt="WhatsApp"
                         class="w-8 h-8 hover:scale-110 transition">
                </a>
            </div>

            <p>
                <strong>Correo:</strong>
                <a href="mailto:<?= htmlspecialchars($p['cliente_correo']) ?>"
                   class="text-blue-600 hover:underline break-all">
                   <?= htmlspecialchars($p['cliente_correo']) ?>
                </a>
            </p>

            <p>
                <strong>Dirección:</strong>
                <a href="#"
                   onclick="mostrarMapa('<?= addslashes($p['direccion']) ?>')"
                   class="text-blue-600 hover:underline break-words">
                   <?= htmlspecialchars($p['direccion']) ?>
                </a>
            </p>

        </div>
    </div>
    

    <div class="flex justify-end gap-2 mt-4 pt-3 border-t">
        <a href="<?= BASE_URL ?>progresoObra.php?id=<?= $p['idproyecto'] ?>"
           class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-800">
           Progreso
        </a>

        <!--

        <a href="eliminarProyecto.php?id=<?= $p['idproyecto'] ?>"
           onclick="return confirm('¿Seguro que deseas eliminar este proyecto?')"
           class="text-xs px-3 py-1 bg-red-600 text-white rounded hover:bg-red-800">
           Eliminar
        </a>

        -->


    </div>

</div>

<?php endforeach; ?>

<?php else: ?>

<p class="col-span-full text-center text-gray-600">
No hay proyectos registrados.
</p>

<?php endif; ?>

</div>


<div id="mapaModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg w-11/12 md:w-3/4 h-3/4 relative p-4">

    <button onclick="cerrarMapa()" 
    class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded-lg">X</button>

    <iframe id="mapaFrame" class="w-full h-full rounded"></iframe>

    <a id="verEnGoogleMaps" target="_blank"
    class="absolute bottom-2 right-2 bg-blue-600 text-white px-2 py-1 rounded">
    Abrir en Google Maps
    </a>

    </div>
</div>

<script>
    // Variable global en JavaScript con el valor de PHP
    const BASE_URL = "<?= BASE_URL ?>";
</script>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const buscador = document.getElementById("buscarProyecto");
    const filtroEstado = document.getElementById("estadoProyecto");
    const cards = document.querySelectorAll(".card-proyecto");

    function filtrarProyectos() {

        const texto = buscador.value.toLowerCase().trim();
        const estado = filtroEstado.value.toLowerCase();

        cards.forEach(card => {

            const nombre = card.dataset.nombre;
            const cliente = card.dataset.cliente;
            const telefono = card.dataset.telefono;
            const correo = card.dataset.correo;
            const direccion = card.dataset.direccion;
            const estadoCard = card.dataset.estado;

            const coincideTexto =
                nombre.includes(texto) ||
                cliente.includes(texto) ||
                telefono.includes(texto) ||
                correo.includes(texto) ||
                direccion.includes(texto);

            const coincideEstado =
                estado === "" || estadoCard.includes(estado);

            if ((texto === "" || coincideTexto) && coincideEstado) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }

        });

    }

    buscador.addEventListener("input", filtrarProyectos);
    filtroEstado.addEventListener("change", filtrarProyectos);

});


    function mostrarMapa(direccion) {
    const mapaModal = document.getElementById('mapaModal');
    const mapaFrame = document.getElementById('mapaFrame');
    const enlace = document.getElementById('verEnGoogleMaps');

    const direccionCodificada = encodeURIComponent(direccion);
    mapaFrame.src = `https://www.google.com/maps?q=${direccionCodificada}&output=embed`;
    enlace.href = `https://www.google.com/maps/dir/?api=1&destination=${direccionCodificada}`;

    mapaModal.classList.remove('hidden');
    }

    function cerrarMapa() {
    document.getElementById('mapaModal').classList.add('hidden');
    document.getElementById('mapaFrame').src = "";
    }
</script>

</Body>
