<?php
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /JB-CONSTRUCCIONES/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nuevo Proyecto</title>

<link rel="stylesheet" href="/JB-CONSTRUCCIONES/assets/css/style.css">
<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Nuevo Proyecto</h1>

        <a href="gestionproyectos.php"
        class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800">
            Volver
        </a>
    </div>

    <!-- BUSCADOR -->
    <div class="bg-white rounded-xl shadow-md p-4 mb-6 flex flex-col md:flex-row gap-4 items-center">

        <input id="buscarCotizacion" type="text"
            placeholder="Buscar por ID cotización o documento..."
            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">

        <button onclick="buscarCotizacion()"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Buscar
        </button>

    </div>

    <!-- FORMULARIO -->
    <form id="formProyecto" class="bg-white rounded-xl shadow-md p-6 space-y-6">

        <input type="hidden" id="cotizacion_id">

        <!-- GRID PRINCIPAL -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- DATOS CLIENTE -->
            <div class="bg-gray-50 p-4 rounded-lg border">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Datos del Cliente</h2>

                <div class="grid grid-cols-2 gap-4">

                    <input id="nombre" placeholder="Nombre"
                    class="border p-2 rounded-lg">

                    <input id="apellido" placeholder="Apellido"
                    class="border p-2 rounded-lg">

                    <input id="correo" placeholder="Correo"
                    class="border p-2 rounded-lg col-span-2">

                    <input id="contacto" placeholder="Contacto"
                    class="border p-2 rounded-lg">

                    <input id="ubicacion" placeholder="Ubicación"
                    class="border p-2 rounded-lg">

                    <input id="direccion" placeholder="Dirección"
                    class="border p-2 rounded-lg col-span-2">

                </div>
            </div>

            <!-- DATOS PROYECTO -->
            <div class="bg-gray-50 p-4 rounded-lg border">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Datos del Proyecto</h2>

                <div class="grid grid-cols-2 gap-4">

                    <input id="nombre_proyecto" placeholder="Nombre del proyecto"
                    class="border p-2 rounded-lg col-span-2">

                    <input type="date" id="fecha_inicio"
                    class="border p-2 rounded-lg">

                    <input type="date" id="fecha_fin"
                    class="border p-2 rounded-lg">

                    <textarea id="descripcion" placeholder="Descripción del proyecto"
                    class="border p-2 rounded-lg col-span-2"></textarea>

                </div>
            </div>

        </div>

        <!-- BOTONES -->
        <div class="flex justify-end gap-4 pt-4 border-t">

            <button type="button"
            onclick="document.getElementById('formProyecto').reset()"
            class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
                Limpiar
            </button>

            <button type="submit"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                Guardar Proyecto
            </button>

        </div>

    </form>

</div>

<script>

async function buscarCotizacion() {

    const valor = document.getElementById("buscarCotizacion").value;

    const res = await fetch('/JB-CONSTRUCCIONES/app/ajax/buscarCotizacion.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({busqueda: valor})
    });

    const data = await res.json();

    if (!data.success) {
        alert("No encontrada");
        return;
    }

    const c = data.data;

    document.getElementById("cotizacion_id").value = c.idcotizacion;
    document.getElementById("nombre").value = c.nombres;
    document.getElementById("apellido").value = c.apellidos;
    document.getElementById("correo").value = c.correo;
    document.getElementById("contacto").value = c.contacto;
    document.getElementById("ubicacion").value = c.ubicacion;
    document.getElementById("direccion").value = c.direccion;
    document.getElementById("descripcion").value = c.descripcion;

    alert("Cotización cargada ✔");
}

// GUARDAR
document.getElementById("formProyecto").addEventListener("submit", async e => {
    e.preventDefault();

    const datos = {
        cotizacion_id: document.getElementById("cotizacion_id").value || null,
        nombre: document.getElementById("nombre").value,
        apellido: document.getElementById("apellido").value,
        correo: document.getElementById("correo").value,
        contacto: document.getElementById("contacto").value,
        ubicacion: document.getElementById("ubicacion").value,
        direccion: document.getElementById("direccion").value,
        descripcion: document.getElementById("descripcion").value,
        nombre_proyecto: document.getElementById("nombre_proyecto").value,
        fecha_inicio: document.getElementById("fecha_inicio").value,
        fecha_fin: document.getElementById("fecha_fin").value
    };

    const res = await fetch('/JB-CONSTRUCCIONES/app/ajax/guardarProyecto.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify(datos)
    });

    const r = await res.json();

    if (r.success) {
        alert("Proyecto creado ✔");
        window.location.href = "gestionproyectos.php";
    } else {
        alert("Error al guardar");
    }
});

</script>

</body>
</html>