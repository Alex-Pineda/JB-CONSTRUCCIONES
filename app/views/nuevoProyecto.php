<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/data.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}
    $usuario = $_SESSION['usuario'];
    $rol = $usuario['rol_idrol'] ?? null;
    ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Proyecto - JB Construcciones</title>
    <link rel="icon" href="<?= BASE_URL ?>assets/img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

<div class="max-w-[1400px] mx-auto p-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Nuevo Proyecto</h1>
            <p class="text-gray-500 text-sm">Gestión y registro de proyectos</p>
        </div>

        <a href="gestionproyectos.php"
           class="bg-gray-800 text-white px-5 py-2 rounded-lg shadow hover:bg-gray-900 transition">
            ← Volver
        </a>
    </div>

    <!-- BUSCADOR -->
    <div class="bg-white rounded-xl shadow-lg p-5 mb-8 border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4 items-center">

            <input id="buscarCotizacion" type="text"
                placeholder="Buscar cotización por ID o documento..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition">

            <button type="button" onclick="buscarCotizacion()"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 shadow-md transition">
                Buscar
            </button>

        </div>
    </div>

    <!-- FORMULARIO -->
    <form id="formProyecto" class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 space-y-8">

        <input type="hidden" id="cotizacion_id">

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

            <!-- CLIENTE -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-5 text-gray-700 border-b pb-2">
                    Datos del Cliente
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <input id="nombre" placeholder="Nombre"
                        class="input">

                    <input id="apellido" placeholder="Apellido"
                        class="input">

                    <input id="numero_documento" placeholder="Documento"
                        class="input">

                    <select id="tipo_documento" class="border p-2 rounded-lg bg-white">
                        <option value="">Tipo documento</option>
                        <option value="CC">Cédula</option>
                        <option value="CE">Cédula Extranjería</option>
                        <option value="NIT">NIT</option>
                        <option value="PAS">Pasaporte</option>
                    </select>

                    <input id="correo" placeholder="Correo"
                        class="input md:col-span-2">

                    <input id="contacto" placeholder="Contacto"
                        class="input">

                    <input id="ubicacion" placeholder="Ubicación"
                        class="input">

                    <input id="direccion" placeholder="Dirección"
                        class="input md:col-span-2">

                </div>
            </div>

            <!-- PROYECTO -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold mb-5 text-gray-700 border-b pb-2">
                    Datos del Proyecto
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <input id="nombre_proyecto"
                        placeholder="Nombre del proyecto"
                        class="input md:col-span-2"
                        required>

                    <input type="date" id="fecha_inicio"
                        class="input"
                        required>

                    <input type="date" id="fecha_fin"
                        class="input"
                        required>

                    <textarea id="descripcion"
                        placeholder="Descripción del proyecto"
                        class="input md:col-span-2 h-24 resize-none"
                        required></textarea>

                </div>
            </div>

        </div>

        <!-- BOTONES -->
        <div class="flex justify-end gap-4 pt-6 border-t">

            <button type="button" onclick="this.form.reset()"
                class="bg-gray-300 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-400 transition">
                Limpiar
            </button>

            <button type="submit"
                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 shadow-md transition">
                Guardar Proyecto
            </button>

        </div>

    </form>

</div>

<script>
    // Usamos el BASE_URL de PHP para que los fetch funcionen en cualquier lado
    const BASE_URL = "<?= BASE_URL ?>";


    async function buscarCotizacion() {
        const valorInput = document.getElementById("buscarCotizacion").value.trim();
        if (!valorInput) {
            alert("Por favor ingrese un valor de búsqueda");
            return;
        }

        try {
            // CONEXIÓN INTEGRADA: Ruta absoluta usando BASE_URL
            const res = await fetch(`${BASE_URL}app/ajax/buscarCotizacion.php`, {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({busqueda: valorInput})
            });

            const data = await res.json();

            if (data.success) {
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
            } else {
                alert(data.error || "No encontrada");
            }
        } catch (error) {
            console.error(error);
            alert("Error al conectar con el servidor para buscar");
        }
    }

    document.getElementById("formProyecto").addEventListener("submit", async (e) => {
        e.preventDefault();

        const datos = {
            cotizacion_id: document.getElementById("cotizacion_id").value || null,
            nombre: document.getElementById("nombre").value,
            apellido: document.getElementById("apellido").value,
            correo: document.getElementById("correo").value,
            numero_documento: document.getElementById("numero_documento").value,
            ubicacion: document.getElementById("ubicacion").value,
            direccion: document.getElementById("direccion").value,
            descripcion: document.getElementById("descripcion").value,
            nombre_proyecto: document.getElementById("nombre_proyecto").value,
            fecha_inicio: document.getElementById("fecha_inicio").value,
            fecha_fin: document.getElementById("fecha_fin").value
        };

        try {
            // CONEXIÓN INTEGRADA: Ruta absoluta usando BASE_URL
            const res = await fetch(`${BASE_URL}app/ajax/guardarProyecto.php`, {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify(datos)
            });

            const r = await res.json();

            if (r.success) {
                alert("Proyecto creado ✔");
                // Redirección dinámica segura
                window.location.href = "gestionproyectos.php";
            } else {
                alert(r.error || "Error al guardar");
            }
        } catch (error) {
            console.error(error);
            alert("Error de conexión al intentar guardar");
        }
    });
</script>

</body>
</html>