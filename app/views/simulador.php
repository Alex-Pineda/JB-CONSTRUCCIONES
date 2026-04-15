<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/data.php';
require_once __DIR__ . '/../controllers/ServicioController.php';


ini_set('display_errors', 1);
error_reporting(E_ALL);

$controller = new ServicioController();
$servicios = $controller->listarPorCategoria();

// Agrupar por categoría
$categorias = [
    'Obra negra' => [],
    'Obra gris' => [],
    'Obra blanca' => [],
    'Mantenimiento' => []
];

    foreach ($servicios as $s) {

        $categoria = strtolower(trim($s['categoria']));

        if ($categoria === 'obra negra') {
            $categorias['Obra negra'][] = $s;

        } elseif ($categoria === 'obra gris') {
            $categorias['Obra gris'][] = $s;

        } elseif ($categoria === 'obra blanca') {
            $categorias['Obra blanca'][] = $s;

        } elseif ($categoria === 'mantenimiento') {
            $categorias['Mantenimiento'][] = $s;
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simulador de Cotizaciones</title>

  <link rel="icon" href="<?= BASE_URL ?>assets/img/favicon.ico" type="image/x-icon" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
  <style>
    .input-estilo {
      width: 100%;
      height: 2.5rem; 
      padding: 0 0.5rem; /* px-4 */
      margin-top: 0.25rem; /* mt-1 */
      border-radius: 0.375rem; /* rounded-md */
      border: 1px solid #81E6D9; /* border-teal-300 */
      background-color: #F0FDFA; /* bg-teal-50 */
      color: #030d0c; /* text-teal-700 */
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); /* shadow-sm */
      transition: all 0.15s ease-in-out;
    }

    .input-estilo::placeholder {
      color: #606b54; /* placeholder-teal-500 */
    }

    .input-estilo:focus {
      outline: none;
      border-color: #14B8A6; /* border-teal-500 */
      box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.4); /* focus:ring */
    }

    textarea.input-estilo {
      height: auto;
      padding-top: 0.75rem;
      padding-bottom: 0.75rem;
      resize: none;
    }
</style>
</head>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <div class="flex-1 flex flex-col overflow-hidden">
      
      <!-- HEADER -->
      <header class="bg-transparent border-b-[3px] border-[#161a7e] shadow-none">
        <div class="w-full max-w-5xl mx-auto flex items-center justify-between px-6 py-4">
          <h1 class="text-2xl font-semibold text-teal-700">SIMULADOR</h1>
          <button 
            onclick="window.location.href='/JB-CONSTRUCCIONES/index.php'" 
            class="px-3 py-1 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700 transition duration-200">Inicio</button>
        </div>

      </header>

      <!-- MAIN (Scroll solo aquí) -->
    <main class="flex-1 overflow-y-auto pt-2 pr-2 pl-2 pb-2 bg-gray-100">

        <div class="w-full max-w mx-auto">
          <p class="text-teal-700 text-2xl font-bold text-center mt-8 -mb-8" id="simulador-titulo"></p>

          <!-- FORMULARIO -->
          <form id="formCotizacion" class="contenedor-principal">

            <!-- SECCIÓN DATOS Y SERVICIOS CON BORDE -->
            <div class="border border-teal-300 rounded-2xl p-2 space-y-10 bg-white shadow-md">

              <!-- DATOS GENERALES -->
              <section>
                <h2 class="text-center font-semibold text-teal-700 mb-6 text-2xl">Datos Generales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                  <input type="text" id="nombre" class="input-estilo" placeholder="Nombre">
                  <input type="text" id="apellido" class="input-estilo" placeholder="Apellido">
                  <select id="tipoDocumento" class="input-estilo">
                    <option value="">Tipo de Documento</option>
                    <option value="dni">DNI</option>
                    <option value="cedula">Cédula</option>
                    <option value="pasaporte">Pasaporte</option>
                    <option value="otro">Otro</option>
                  </select>
                  <input type="text" id="numeroDocumento" class="input-estilo" placeholder="Número de Documento">
                  <input type="email" id="correo" class="input-estilo" placeholder="Correo Electrónico">
                  <input type="tel" id="contacto" class="input-estilo" placeholder="Número de Contacto">
                  <input type="text" id="ubicacion" class="input-estilo" placeholder="Departamento - Ciudad">
                  <input type="text" id="direccion" class="input-estilo" placeholder="Dirección del Proyecto">
                  <div class="md:col-span-2">
                    <textarea id="descripcion" rows="3" class="input-estilo resize-none" placeholder="Descripción del trabajo a realizar"></textarea>
                  </div>
                </div>
              </section>

              <!-- Contacto personalizado -->
              <div class="flex flex-col md:flex-row items-center gap-4 bg-teal-50 border border-teal-200 rounded-lg p-6 mt-6">
                <label for="contactoPersonalizado" class="text-teal-800 font-medium flex-1">
                  ¿Deseas que te contactemos para una valoración personalizada del servicio?
                </label>
                <div class="flex items-center gap-2">
                  <input type="checkbox" id="contactoPersonalizado" name="contactoPersonalizado"
                    class="h-5 w-5 text-teal-600 border-teal-400 rounded focus:ring-teal-300">
                  <span class="text-teal-700">Sí, deseo ser contactado</span>
                </div>
              </div>

              <!-- Fecha estimada visita -->
              <div id="campoFechaVisita" class="hidden flex flex-col items-center mt-4">
                <label for="fechaVisita" class="block text-teal-800 font-semibold mb-2 text-center">
                  Por favor, indique la fecha estimada para la visita especializada:
                </label>
                <input type="date" id="fechaVisita" name="fechaVisita"
                  class="w-full md:w-1/3 px-4 py-2 border border-teal-300 rounded-md focus:ring-2 focus:ring-teal-400 focus:outline-none">
              </div>

              <!-- SERVICIOS -->
              <section>
                <h2 class="text-center font-semibold text-teal-700 mb-6 text-2xl">
                  Selecciona los Servicios y sus m²
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                  <div id="obraNegraOpciones" class="space-y-3 flex-1">
                    <?php foreach ($categorias['Obra negra'] as $s): ?>
                        <div class="flex items-center space-x-3 servicio-item">

                            <input type="checkbox"
                                class="servicio-check"
                                data-id="<?= $s['idservicio'] ?>"
                                data-precio="<?= $s['precio_base'] ?>">

                            <label class="text-sm">
                                <?= htmlspecialchars($s['nombre_servicio']) ?>
                            </label>

                            <input type="number"
                                class="m2 hidden border rounded px-2 py-1 text-center"
                                placeholder="m²">
                        </div>
                    <?php endforeach; ?>
                    </div>

                  <div id="obraGrisOpciones" class="space-y-3 flex-1">
                    <?php foreach ($categorias['Obra gris'] as $s): ?>
                        <div class="flex items-center space-x-3 servicio-item">

                            <input type="checkbox"
                                class="servicio-check"
                                data-id="<?= $s['idservicio'] ?>"
                                data-precio="<?= $s['precio_base'] ?>">

                            <label class="text-sm">
                                <?= htmlspecialchars($s['nombre_servicio']) ?>
                            </label>

                            <input type="number"
                                class="m2 hidden border rounded px-2 py-1 text-center"
                                placeholder="m²">
                        </div>
                    <?php endforeach; ?>
                    </div>
                        
                  <div id="obraBlancaOpciones" class="space-y-3 flex-1">
                    <?php foreach ($categorias['Obra blanca'] as $s): ?>
                        <div class="flex items-center space-x-3 servicio-item">

                            <input type="checkbox"
                                class="servicio-check"
                                data-id="<?= $s['idservicio'] ?>"
                                data-precio="<?= $s['precio_base'] ?>">

                            <label class="text-sm">
                                <?= htmlspecialchars($s['nombre_servicio']) ?>
                            </label>

                            <input type="number"
                                class="m2 hidden border rounded px-2 py-1 text-center"
                                placeholder="m²">
                        </div>
                    <?php endforeach; ?>
                    </div>
                <div id="mantenimientoOpciones" class="space-y-3 flex-1">
                    <?php foreach ($categorias['Mantenimiento'] as $s): ?>
                        <div class="flex items-center space-x-3 servicio-item">

                            <input type="checkbox"
                                class="servicio-check"
                                data-id="<?= $s['idservicio'] ?>"
                                data-precio="<?= $s['precio_base'] ?>">

                            <label class="text-sm">
                                <?= htmlspecialchars($s['nombre_servicio']) ?>
                            </label>

                            <input type="number"
                                class="m2 hidden border rounded px-2 py-1 text-center"
                                placeholder="m²">
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
              </section>

              <div class="flex justify-center">
                <button type="submit" class="w-full max-w-lg bg-teal-600 text-white px-6 py-3 rounded-md hover:bg-teal-700 transition text-lg font-semibold flex items-center justify-center h-14">
                  Calcular Cotización
                </button>
              </div>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <!-- ESTILOS REUTILIZABLES -->
  <style>
    /* Todos los inputs numéricos dentro de las tarjetas tendrán 3rem de ancho */
    #obraNegraOpciones input[type="number"],
    #obraGrisOpciones input[type="number"],
    #obraBlancaOpciones input[type="number"],
    #mantenimientoOpciones input[type="number"] {
      width: 4rem;
      text-align: center;
      font-size:small;
    }
  </style>

  <script>
    // Variable global en JavaScript con el valor de PHP
    const BASE_URL = "<?= BASE_URL ?>";
  </script>

<script src="<?= BASE_URL ?>Scripts/simulador.js"></script>
</body>
</html>
