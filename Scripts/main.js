document.addEventListener('DOMContentLoaded', () => {

  /* ============================
     0) UTIL: sanitizar IDs
     ============================ */
  function sanitizeId(text) {
    if (!text) return '';
    return text
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // quitar tildes
      .replace(/\s+/g, '-')
      .replace(/[^\w\-]/g, '')
      .toLowerCase();
  }

/* ============================
   1) SIDEBAR (FIX COMPLETO)
============================ */
(function loadSidebar() {

  const sidebar = document.getElementById('sidebar');
  const menuToggle = document.getElementById('menu-toggle');

  if (!sidebar || !menuToggle) return;

  // Crear overlay si no existe
  let overlay = document.getElementById('overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.id = 'overlay';
    overlay.className = 'fixed inset-0 bg-black opacity-0 hidden z-30 transition-opacity duration-300';
    document.body.appendChild(overlay);
  }

  let autoCloseTimer;

  function openSidebar() {
    if (window.innerWidth >= 768) return;

    sidebar.classList.remove('-translate-x-full');
    sidebar.classList.add('translate-x-0');

    overlay.classList.remove('hidden');
    setTimeout(() => overlay.classList.add('opacity-50'), 10);

    resetTimer();
  }

  function closeSidebar() {
    if (window.innerWidth >= 768) return;

    sidebar.classList.add('-translate-x-full');
    sidebar.classList.remove('translate-x-0');

    overlay.classList.remove('opacity-50');
    setTimeout(() => overlay.classList.add('hidden'), 300);

    clearTimeout(autoCloseTimer);
  }

  function resetTimer() {
    clearTimeout(autoCloseTimer);
    autoCloseTimer = setTimeout(closeSidebar, 5000);
  }

  // EVENTO CLICK
  menuToggle.addEventListener('click', () => {
    if (sidebar.classList.contains('-translate-x-full')) {
      openSidebar();
    } else {
      closeSidebar();
    }
  });

  // Overlay
  overlay.addEventListener('click', closeSidebar);

  // Actividad en sidebar
  sidebar.addEventListener('mousemove', resetTimer);
  sidebar.addEventListener('click', resetTimer);

})();

  /* ============================
     3) RENDER DE TARJETAS (index u otras páginas)
     ============================ */

function createCard(servicio) {
  return `
    <div class="service-card cursor-pointer p-1 border rounded">
      <img src="${servicio.imagen}" 
           alt="${servicio.nombre_servicio}" 
           class="w-full h-36 object-cover rounded">

      <h3 class="mt-2 font-semibold text-sm">
        ${servicio.nombre_servicio}
      </h3>

      <p class="text-xs text-gray-600 mt-1">
        ${servicio.descripcion || ''}
      </p>
    </div>
  `;
}

function renderCards(containerId, items) {
  const container = document.getElementById(containerId);
  if (!container) return;

  container.innerHTML = items.map(createCard).join('');
}

 async function cargarServicios() {
  try {
    const res = await fetch('/JB-CONSTRUCCIONES/app/controllers/ServicioApiController.php');

    const data = await res.json(); //

    if (!data.ok) {
      console.error('Error:', data.error);
      return;
    }

    const servicios = data.data;

    const obraNegra = [];
    const obraBlanca = [];
    const obraGris = [];
    const mantenimiento = [];

    servicios.forEach(s => {

      const item = {
        nombre_servicio: s.nombre_servicio,
        descripcion: s.descripcion,
        imagen: s.imagen 
          ? '/JB-CONSTRUCCIONES/' + s.imagen 
          : '/JB-CONSTRUCCIONES/assets/img/default.jpg'
      };

      const categoria = (s.categoria || '').toLowerCase().trim();

      if (categoria.includes('obra negra')) {
        obraNegra.push(item);
      } else if (categoria.includes('obra blanca')) {
        obraBlanca.push(item);
      } else if (categoria.includes('obra gris')) {
        obraGris.push(item);
      } else {
        mantenimiento.push(item);
      }
    });

    console.log("DATOS:", { obraNegra, obraBlanca, obraGris, mantenimiento });

    renderCards('obra-negra-section', obraNegra);
    renderCards('obra-blanca-section', obraBlanca);
    renderCards('obra-gris-section', obraGris);
    renderCards('mantenimiento-section', mantenimiento);

  } catch (error) {
    console.error('Error cargando servicios:', error);
  }
}

cargarServicios();

});