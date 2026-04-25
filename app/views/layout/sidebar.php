<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../controllers/proyectocontroller.php';
$mostrarProyectos = false;

if (isset($_SESSION['usuario'])) {

    $usuario = $_SESSION['usuario'];
    $rol = $usuario['idrol'] ?? null;
    $usuario_id = $usuario['idusuario'];

    if ($rol == 2) {
        // cliente → validar si tiene proyectos
        $controller = new ProyectoController();
        $proyectosCliente = $controller->listarPorUsuario($usuario_id);

        if (!empty($proyectosCliente)) {
            $mostrarProyectos = true;
        }
    } else {
        // admin siempre ve
        $mostrarProyectos = true;
    }
}

?>


<aside id="sidebar"
    aria-label="Menú lateral"
    class="fixed inset-0 z-40 w-52 h-screen px-4 pt-4 pb-6
           transform -translate-x-full transition-transform
           md:static md:translate-x-0 md:block
           bg-[rgba(23,40,33,0.944)] rounded-r-2xl">

    <!-- Logo -->
    <div class="flex items-center justify-center mb-6">
        <img src="<?= BASE_URL ?>assets/img/JB-CONSTRUCTORES.png"
             alt="Logo JB-CONSTRUCTORES"
             class="w-26 h-26 rounded-full border-4 border-teal-500 shadow-md object-cover">
    </div>

    <!-- Principal -->
    <div class="space-y-1 mb-8">
        <div class="text-red-400 text-xs uppercase font-semibold px-2 mb-2">Principal</div>
        <a href="<?= BASE_URL ?>index.php"
           class="block px-4 py-2 hover:bg-red-800 rounded-lg text-green-300">
           Inicio
        </a>
    </div>



    <!-- Servicios -->
    <div class="space-y-1 mb-8">
        <div class="text-red-400 text-xs uppercase font-semibold px-2 mb-2">Servicios</div>


        <?php if ($mostrarProyectos): ?>
        <a href="<?= BASE_URL ?>app/views/gestionproyectos.php"
        class="block px-4 py-2 hover:bg-red-800 rounded-lg text-green-300">
        Proyectos
        </a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>app/views/simulador.php"
           class="block px-4 py-2 hover:bg-red-800 rounded-lg text-green-300">
           Cotización
        </a>
   
        <!-- Enlaces ocultos - Se activaran cuando se migren a php 


        <a href="/JB-CONSTRUCCIONES/app/views/portafolio.html"
           class="block px-4 py-2 hover:bg-red-800 rounded-lg text-green-300">
           Portafolio
        </a>

        <a href="/JB-CONSTRUCCIONES/app/views/blogs.html"
           class="block px-4 py-2 hover:bg-red-800 rounded-lg text-green-300">
           Blogs
        </a>

        <a href="/JB-CONSTRUCCIONES/app/views/resenas.html"
           class="block px-4 py-2 hover:bg-red-800 rounded-lg text-green-300">
           Reseñas
        </a>

      -->

    </div>

</aside>

<script>
    // Variable global en JavaScript con el valor de PHP
    const BASE_URL = "<?= BASE_URL ?>";
</script>