<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/data.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idUsuario = $usuario['idusuario'];
$rol = $_SESSION['usuario']['idrol']?? 0;
$esAdmin = ($rol == 1);

$idproyecto = isset($_GET['idproyecto']) ? intval($_GET['idproyecto']) : 0;

if ($idproyecto <= 0) {
    header("Location: gestionproyectos.php");
    exit();
}

$db = new Data();
$conn = $db->getConnection();

/* ======================================================
   VALIDAR PROYECTO
====================================================== */

if ($esAdmin) {

    $sql = "SELECT * FROM proyecto WHERE idproyecto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$idproyecto]);

} else {

    $sql = "SELECT * FROM proyecto 
            WHERE idproyecto = ?
            AND usuario_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$idproyecto, $idUsuario]);
}

$proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$proyecto) {
    die("No autorizado para ver este proyecto");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Progreso de Obra</title>

<link rel="icon" href="<?= BASE_URL ?>assets/img/favicon.ico">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
<script src="https://cdn.tailwindcss.com"></script>

<style>
body{
font-family:Arial, Helvetica, sans-serif;
background:linear-gradient(to bottom right,#e3e6ff,#f3f4f6);
min-height:100vh;
}
.glass{
background:rgba(255,255,255,.45);
backdrop-filter:blur(10px);
border:1px solid rgba(22,26,126,.2);
box-shadow:0 8px 25px rgba(0,0,0,.08);
border-radius:1rem;
}
</style>
</head>

<body class="p-4">

<div class="max-w-6xl mx-auto">

<!-- HEADER -->
<div class="glass p-4 mb-3 flex justify-between items-center">
<div>
<h1 class="text-2xl font-bold text-gray-800">Progreso de Obra</h1>
<p class="text-sm text-gray-600">
<?= htmlspecialchars($proyecto['nombre_proyecto']) ?>
</p>
</div>

<a href="gestionproyectos.php"
class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-black">
Volver
</a>
</div>

<!-- AVANCE GENERAL -->
<div class="glass p-4 mb-3">
<div class="flex justify-between mb-2">
<span class="font-semibold">Avance total</span>
<span id="textoAvance"><?= $proyecto['porcentaje_avance'] ?>%</span>
</div>

<div class="w-full bg-gray-170 rounded-full h-3 overflow-hidden">
<div id="barraAvance"
class="bg-green-600 h-4"
style="width:<?= $proyecto['porcentaje_avance'] ?>%">
</div>
</div>
</div>

<?php if($esAdmin): ?>

<div class="glass p-6 mb-6">

<h2 class="text-xl font-bold text-gray-800 mb-3 border-b pb-2">Publicar nuevo avance
</h2>

<form id="formAvance" class="grid lg:grid-cols-2 gap-5">

<input type="hidden" id="idproyecto" value="<?= $idproyecto ?>">

<!-- COLUMNA IZQUIERDA -->
<div class="space-y-3">

<input id="titulo"
type="text"
placeholder="Título breve del avance"
class="border rounded-xl p-3 w-full bg-white/80 shadow-sm focus:ring-2 focus:ring-blue-600"
required>

<textarea id="descripcion"
rows="5"
placeholder="Describe lo realizado en este avance..."
class="border rounded-xl p-3 w-full bg-white/80 shadow-sm focus:ring-2 focus:ring-blue-600"></textarea>

<div class="grid md:grid-cols-2 gap-4">

<input id="youtube_video_id"
type="text"
placeholder="ID YouTube"
class="border rounded-xl p-3 w-full bg-white/80 shadow-sm"
required>

<input id="porcentaje_aporte"
type="number"
min="1"
max="100"
step="0.01"
placeholder="% progreso"
class="border rounded-xl p-3 w-full bg-white/80 shadow-sm"
required>

</div>

<select id="tipo_video"
class="border rounded-xl p-3 w-full bg-white/80 shadow-sm">
<option value="video">Video grabado</option>
<option value="vivo">En vivo</option>
</select>

</div>

<!-- COLUMNA DERECHA -->
<div class="space-y-5">

<div class="glass p-4 min-h-[200px] flex items-center justify-center">
<div id="previewVideo" class="w-full"></div>
</div>

<div class="grid grid-cols-2 gap-3">

<button type="button"
onclick="window.open('https://studio.youtube.com/channel/UCfj5-JGfOtYcKCD8_nvgn1g/videos/upload','_blank')"
class="bg-indigo-700 text-white py-3 rounded-xl hover:bg-indigo-900">
Canal
</button>

<button type="button"
onclick="window.open('https://studio.youtube.com/channel/UCfj5-JGfOtYcKCD8_nvgn1g/livestreaming','_blank')"
class="bg-red-600 text-white py-3 rounded-xl hover:bg-red-800">
En Vivo
</button>

<button type="reset"
class="bg-gray-400 text-white py-3 rounded-xl hover:bg-gray-600">
Limpiar
</button>

<button type="submit"
class="bg-green-600 text-white py-3 rounded-xl hover:bg-green-800 shadow-lg">
Publicar
</button>

</div>

</div>

</form>
</div>

<?php endif; ?>

<!-- LISTADO -->
<div class="mt-8 border-t-4 border-blue-700 pt-8">

<h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
Avances publicados
</h2>

<div id="contenedorVideos"
class="grid md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
</div>

</div>

<script>
const BASE_URL = "<?= BASE_URL ?>";
const ES_ADMIN = <?= $esAdmin ? 'true':'false' ?>;
const IDPROYECTO = <?= $idproyecto ?>;

/* =========================================
   PREVIEW YOUTUBE
========================================= */
const youtube = document.getElementById("youtube_video_id");

if(youtube){
youtube.addEventListener("input", ()=>{
const id = youtube.value.trim();

document.getElementById("previewVideo").innerHTML = id
? `
<iframe class="w-full h-48 rounded-xl"
src="https://www.youtube.com/embed/${id}"
allowfullscreen></iframe>
`
: '';
});
}

/* =========================================
   GUARDAR
========================================= */
const form = document.getElementById("formAvance");

if(form){

form.addEventListener("submit", async(e)=>{
e.preventDefault();

const datos = {
proyecto_id: IDPROYECTO,
titulo: document.getElementById("titulo").value,
descripcion: document.getElementById("descripcion").value,
youtube_video_id: document.getElementById("youtube_video_id").value,
tipo_video: document.getElementById("tipo_video").value,
porcentaje_aporte: document.getElementById("porcentaje_aporte").value
};

const res = await fetch(`${BASE_URL}app/ajax/guardarProgreso.php`,{
method:"POST",
headers:{'Content-Type':'application/json'},
body: JSON.stringify(datos)
});

const r = await res.json();

if(r.success){
    form.reset();
    document.getElementById("previewVideo").innerHTML='';
    cargarVideos();
    }
    else{

    alert(r.error || "Error");
    }

});

}

/* =========================================
   LISTAR
========================================= */
async function cargarVideos(){

const res = await fetch(`${BASE_URL}app/ajax/listarProgreso.php?idproyecto=${IDPROYECTO}`);
const data = await res.json();

const cont = document.getElementById("contenedorVideos");
cont.innerHTML = '';

data.data.forEach(v=>{

let botones = '';

if(ES_ADMIN){
botones = `
<button onclick="eliminar(${v.idprogreso})"
class="mt-3 bg-red-600 text-white px-3 py-1 rounded">
Eliminar
</button>
`;
}

const numero = (v.celular || '').replace(/\D/g, '');
const nombreAdmin = v.nombres || 'Administrador';

cont.innerHTML += `
<div class="glass overflow-hidden">

<iframe class="w-full h-52"
src="https://www.youtube.com/embed/${v.youtube_video_id}"
allowfullscreen></iframe>

<div class="p-4">

<h3 class="font-bold text-lg">${v.titulo}</h3>

<p class="text-sm text-gray-600 mt-2">
${v.descripcion ?? ''}
</p>

<div class="mt-2 text-sm">
Aporte: <b>${v.porcentaje_aporte}%</b>
</div>

<div class="mt-2 text-xs text-gray-500">
${v.fecha_publicacion}
</div>

${
!ES_ADMIN
?
`<a target="_blank"
href="https://wa.me/57${numero}?text=Hola ${nombreAdmin}, deseo consultar el avance de mi proyecto."
class="inline-block mt-3 bg-green-600 text-white px-3 py-2 rounded">
WhatsApp
</a>`
:
botones
}

</div>
</div>
`;

});

if(data.avance_total !== undefined){

document.getElementById("textoAvance").innerText =
data.avance_total + "%";

document.getElementById("barraAvance").style.width =
data.avance_total + "%";

}

}

/* =========================================
   ELIMINAR
========================================= */
async function eliminar(id){


const res = await fetch(`${BASE_URL}app/ajax/eliminarProgreso.php`,{
method:"POST",
headers:{'Content-Type':'application/json'},
body: JSON.stringify({idprogreso:id})
});

const r = await res.json();

if(r.success){
cargarVideos();
}else{
alert("No se pudo eliminar");
}

}

cargarVideos();

</script>
</body>
</html>