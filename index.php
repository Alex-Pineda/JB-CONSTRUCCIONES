<?php

require_once __DIR__ . '/app/controllers/ServicioController.php';
$controller = new ServicioController();
$servicios = $controller->listarPorCategoria();


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Construcción y Mantenimiento</title>

    <link rel="icon" href="/JB-CONSTRUCCIONES/assets/img/favicon.ico" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap"
      rel="stylesheet"/>

    <link rel="stylesheet" href="/JB-CONSTRUCCIONES/assets/css/style.css"/>

    <style>
      body { font-family: "Roboto", sans-serif; }

      .sidebar {
        transition: all 0.3s;
        height: fit-content;
      }

      .tabs {
        display: flex;
        gap: 0.5rem;
        border-bottom: 3px solid #161a7e;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
        border-top:3px solid #161a7e;
      }

      .tabs button {
        font-weight: 500;
        color: #4a4a4a;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        transition: border-color 0.3s ease, color 0.3s ease;
      }

      .tabs button:hover { color: #761f26; }

      .tabs button.active {
        border-bottom-color: #761f26;
        color: #761f26;
        font-weight: 700;
      }
    </style>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar REAL en PHP -->
    <?php require_once __DIR__ . '/app/views/layout/sidebar.php'; ?>

    <!-- Main content -->
    <div class="flex-1 flex flex-col h-full">

      <!-- Header -->
<header class="bg-transparent shadow-none">
  <div class="px-2 pt-4 pb-0">

    <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between mb-2 text-center sm:text-left">

      <div>

        <?php if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])): ?>

            <h1 class="text-2xl font-bold text-gray-800">
                Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre_usuario']); ?>
            </h1>

        <?php else: ?>

            <h1 class="text-2xl font-bold text-gray-800">
                JB-CONSTRUCCIONES
            </h1>

            <p class="text-gray-600 text-sm sm:mr-32">
                Soluciones de obra civil con calidad, seguridad y cumplimiento.
                Elige el servicio que necesitas.
            </p>

        <?php endif; ?>

      </div>

      <div class="flex flex-row space-x-2 mt-3 sm:mt-0 w-full sm:w-auto justify-center sm:justify-end"
           style="white-space: nowrap; min-width: 100px; margin-top: 0.3rem;">

        <?php if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])): ?>

            <!-- Solo visible cuando está logueado -->
            <a href="/JB-CONSTRUCCIONES/logout.php"
               class="px-2 py-0 sm:px-3 sm:py-1 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition duration-200">
                Cerrar sesión
            </a>

        <?php else: ?>

            <a href="https://wa.me/573007413114?text=Hola%2C%20quiero%20más%20información%20sobre%20sus%20servicios"
               class="flex items-center"><img src="/JB-CONSTRUCCIONES/assets/img/whatsapp-fill.svg"alt="WhatsApp"
               class="w-7 h-7 mt-[-0.7rem] transition-transform duration-200 hover:scale-125 hover:brightness-130"/>
            </a>

            <!-- Solo visible cuando NO está logueado -->
            <a href="/JB-CONSTRUCCIONES/app/views/auth/login.php"
               class="mt-0.5 px-2 py-1 sm:px-3 sm:py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition duration-200">
                Iniciar sesión
            </a>

            <a href="/JB-CONSTRUCCIONES/app/views/auth/registro.php"
               class="mt-0.5 px-2 py-1 sm:px-3 sm:py-1 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition duration-200">
                Registrarse
            </a>

        <?php endif; ?>
        <!-- SIEMPRE -->

      <button id="menu-toggle" class=" md:hidden p-2 py-1 text-white bg-gray-800">
          ☰
      </button>

      </div>

    </div>

  </div>

<?php if (isset($_GET['session']) && $_GET['session'] === 'expirada'): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        Tu sesión expiró por inactividad.
    </div>
<?php endif; ?>

</header>

      <!-- Navegación pestañas -->
      <nav class="tabs mt-[0.1rem] mb-[0.1rem] text-base md:text-lg flex flex-row sm:gap-1 gap-[0.5px] justify-evenly items-center flex-wrap">

        <button class="active" data-tab="obra-negra">Obra Negra</button>
        <button data-tab="obra-blanca">Obra Blanca</button>
        <button data-tab="obra-gris">Obra Gris</button>
        <button data-tab="mantenimiento">Mantenimiento</button>

      </nav>

      <!-- Contenido principal -->
      <main
        class="flex-1 w-full flex flex-col items-center justify-start overflow-y-auto pl-[0.4rem] pr-[0.4rem] pb-6 rounded-2xl shadow-lg"
        style="background-color: #e0f2f1; border-radius: 1.5rem;">

        <section id="obra-negra" class="tab-content w-full">
          <div id="obra-negra-section"
               class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 3xl:grid-cols-7 gap-4 w-full mt-4">
          </div>
        </section>

        <section id="obra-blanca" class="tab-content hidden w-full">
          <div id="obra-blanca-section"
               class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 3xl:grid-cols-7 gap-4 w-full mt-4">
          </div>
        </section>

        <section id="obra-gris" class="tab-content hidden w-full">
          <div id="obra-gris-section"
               class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 3xl:grid-cols-7 gap-4 w-full mt-4">
          </div>
        </section>

        <section id="mantenimiento" class="tab-content hidden w-full">
          <div id="mantenimiento-section"
               class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 3xl:grid-cols-7 gap-4 w-full mt-4">
          </div>
        </section>
      </main>
    </div>
</div>

<script>
const tabs = document.querySelectorAll("nav.tabs button");
const contents = document.querySelectorAll(".tab-content");

tabs.forEach((tab) => {
  tab.addEventListener("click", () => {
    tabs.forEach((t) => t.classList.remove("active"));
    contents.forEach((c) => c.classList.add("hidden"));

    tab.classList.add("active");
    document.getElementById(tab.dataset.tab).classList.remove("hidden");
  });
});
</script>

<script>
  const serviciosDB = <?= json_encode($servicios); ?>;
</script>

<!-- JS externo que genera tarjetas -->
<script src="/JB-CONSTRUCCIONES/Scripts/main.js"></script>

</body>
</html>