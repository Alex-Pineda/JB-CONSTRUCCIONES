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
     1) SIDEBAR (cargar solo si existe contenedor)
     ============================ */
  (function loadSidebar() {
    const sidebarContainer = document.getElementById('sidebar-container');
    if (!sidebarContainer) return; // si no existe en esta página, saltar

    // Determinar la ruta correcta al sidebar según la ubicación actual
    let sidebarPath = "Encabezado_Pie/sidebar.html";
    if (window.location.pathname.includes("/Paginas/") || window.location.pathname.includes("/Admin/")) {
      sidebarPath = "../Encabezado_Pie/sidebar.html";
    }

    fetch(sidebarPath)
      .then(response => response.text())
      .then(html => {
        // Ajustar rutas de los enlaces según la ubicación actual
        let adjustedHtml = html;
        if (
          window.location.pathname.endsWith('/index.html') ||
          window.location.pathname === '/' ||
          window.location.pathname === '/index.html'
        ) {
          // Si estamos en la raíz, no modificar rutas
        } else {
          // Si estamos en /Paginas o /Admin, ajustar las rutas relativas
          adjustedHtml = adjustedHtml.replace(/href="(Paginas\/)/g, 'href="../Paginas/');
          adjustedHtml = adjustedHtml.replace(/href="(Admin\/)/g, 'href="../Admin/');
          adjustedHtml = adjustedHtml.replace(/href="index.html"/g, 'href="../index.html"');
        }
        sidebarContainer.innerHTML = adjustedHtml;

        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menu-toggle');

        // overlay
        let overlay = document.getElementById('overlay');
        if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'overlay';
          overlay.className = 'fixed inset-0 bg-black opacity-0 z-30 hidden transition-opacity duration-300';
          document.body.appendChild(overlay);
        }

        const links = sidebar ? sidebar.querySelectorAll('a') : [];
        let autoCloseTimer;

        function openSidebar() {
          if (!sidebar) return;
          if (window.innerWidth < 1024) {
            sidebar.classList.remove("-translate-x-full", "hidden");
            sidebar.classList.add("translate-x-0", "z-40");
            overlay.classList.remove("hidden");
            setTimeout(() => overlay.classList.add("opacity-50"), 10);
            resetAutoCloseTimer();
          }
        }

        function closeSidebar() {
          if (!sidebar) return;
          
          if (window.innerWidth < 1024) {
            sidebar.classList.add("-translate-x-full");
            sidebar.classList.remove("translate-x-0");
            overlay.classList.remove("opacity-50");
            clearTimeout(autoCloseTimer);
            setTimeout(() => overlay.classList.add("hidden"), 300);
          }

          // ✅ Restaurar color del botón incluso si no se hace animación
          if (menuToggle) {
            menuToggle.classList.remove("bg-white", "text-gray-800");
            menuToggle.classList.add("bg-gray-800", "text-white");
          }
          }

        function resetAutoCloseTimer() {
          clearTimeout(autoCloseTimer);
          autoCloseTimer = setTimeout(closeSidebar, 5000);
        }

        if (menuToggle) {
          menuToggle.addEventListener('click', () => {
            if (sidebar && sidebar.classList.contains("-translate-x-full")) {
              openSidebar();
            } else {
              closeSidebar();
            }
          });
        }

        overlay.addEventListener("click", closeSidebar);
        if (sidebar) {
          sidebar.addEventListener("mousemove", resetAutoCloseTimer);
          sidebar.addEventListener("click", resetAutoCloseTimer);
        }

        // Resaltar enlace activo
        const currentPath = window.location.pathname.split('/').pop();
        links.forEach(link => {
          const href = (link.getAttribute('href') || '').split('/').pop();
          if (href === currentPath) {
            link.classList.add('bg-red-800', 'font-semibold', 'rounded-lg');
          }
          link.addEventListener('click', () => {
            if (window.innerWidth < 1024) closeSidebar();
          });
        });
      })
      .catch(error => console.error("No se pudo cargar el sidebar:", error));
  })();

  /* ============================
     2) DATOS DE SERVICIOS (arrays)
     ============================ */
  // ... (sin cambios, tus arrays)

  const obraNegraItems = [
    { title: "Preparación y limpieza del terreno", img: "assets/img/obra_negra/preparacion_limpieza.jpg", description: "Despejamos y nivelamos el terreno para iniciar la construcción.", link: "https://www.youtube.com/watch?v=Mdm1iivGiTs" },
    { title: "Verificación topográfica y marcación de ejes", img: "assets/img/obra_negra/verificacion_topografica_marcacion.jpg", description: "Comprobamos medidas y niveles, y trazamos los ejes para la cimentación.", link: "https://www.youtube.com/watch?v=VIDEO_ID2" },
    { title: "Excavación y manejo de tierras", img: "assets/img/obra_negra/excavacion.jpg", description: "Realizamos excavaciones y gestionamos el material sobrante.", link: "https://www.youtube.com/watch?v=VIDEO_ID3" },
    { title: "Relleno y compactación", img: "assets/img/obra_negra/relleno_compactacion.jpg", description: "Compactamos el terreno para garantizar la estabilidad de la cimentación.", link: "https://www.youtube.com/watch?v=VIDEO_ID4" },
    { title: "Cimentación", img: "assets/img/obra_negra/cimentacion.jpg", description: "Construimos zapatas, pilotes o losa de cimentación según el diseño estructural.", link: "https://www.youtube.com/watch?v=VIDEO_ID5" },
    { title: "Elaboración y armado de acero", img: "assets/img/obra_negra/estructura_metalica.jpg", description: "Cortamos, doblamos y ensamblamos el acero de refuerzo para la estructura.", link: "https://www.youtube.com/watch?v=VIDEO_ID6" },
    { title: "Encofrado y vaciado de concreto", img: "assets/img/obra_negra/encofrados_vaciados.jpg", description: "Colocamos encofrados y vaciamos concreto para elementos estructurales.", link: "https://www.youtube.com/watch?v=VIDEO_ID7" },
    { title: "Armado de losa", img: "assets/img/obra_negra/losas.jpg", description: "Preparamos y fundimos las losas de entrepiso o cubierta.", link: "https://www.youtube.com/watch?v=VIDEO_ID8" },
    { title: "Curado y control de calidad", img: "assets/img/obra_negra/Curado.jpg", description: "Aplicamos curado y realizamos ensayos para garantizar la resistencia del concreto.", link: "https://www.youtube.com/watch?v=VIDEO_ID9" },
    { title: "Replanteo de mampostería", img: "assets/img/obra_negra/replanteo_mamposteria.jpg", description: "Marcamos muros y aberturas antes de levantar mampostería.", link: "https://www.youtube.com/watch?v=VIDEO_ID10" },
    { title: "Mampostería primer - segundo nivel", img: "assets/img/obra_negra/mamposteria.jpeg", description: "Levantamos muros y paredes en primer, segundo nivel.", link: "https://www.youtube.com/watch?v=VIDEO_ID11" },
    { title: "Instalaciones hidrosanitarias", img: "assets/img/obra_negra/instalaciones_hidrosanitarias.jpg", description: "Instalamos tuberías de agua potable, aguas negras y pluviales.", link: "https://www.youtube.com/watch?v=VIDEO_ID12" },
    { title: "Instalación eléctrica", img: "assets/img/obra_negra/instalacion_electrica.jpg", description: "Realizamos el tendido de conduits y puntos eléctricos.", link: "https://www.youtube.com/watch?v=VIDEO_ID13" },
    { title: "Impermeabilización", img: "assets/img/obra_negra/impermeabilizacion.jpg", description: "Protegemos cubiertas y muros contra filtraciones de agua.", link: "https://www.youtube.com/watch?v=VIDEO_ID14" },
    { title: "Revoque", img: "assets/img/obra_negra/revoque.jpg", description: "Aplicamos revoque en muros interiores y exteriores.", link: "https://www.youtube.com/watch?v=VIDEO_ID15" },
    { title: "Morteros", img: "assets/img/obra_negra/morteros.jpg", description: "Preparamos y aplicamos morteros para nivelación y asiento.", link: "https://www.youtube.com/watch?v=VIDEO_ID16" },
    { title: "Enchapes interiores", img: "assets/img/obra_negra/enchapes.jpg", description: "Colocamos cerámica o porcelanato en pisos y paredes.", link: "https://www.youtube.com/watch?v=VIDEO_ID17" },
    { title: "Instalación de baños", img: "assets/img/obra_negra/instalacion_banos.jpg", description: "Montamos inodoros, lavamanos, duchas y grifería.", link: "https://www.youtube.com/watch?v=VIDEO_ID18" },
    { title: "Enchape fachada", img: "assets/img/obra_negra/fachada.jpeg", description: "Revestimos la fachada con piedra, cerámica o materiales decorativos.", link: "https://www.youtube.com/watch?v=VIDEO_ID19" },
    { title: "Lechada y fragüe", img: "assets/img/obra_negra/lechada.jpg", description: "Sellamos juntas y realizamos la limpieza final de los enchapes.", link: "https://www.youtube.com/watch?v=VIDEO_ID20" },
    { title: "Pruebas y limpieza final", img: "assets/img/obra_negra/limpieza.jpg", description: "Realizamos pruebas de funcionamiento y limpieza general de obra.", link: "https://www.youtube.com/watch?v=VIDEO_ID21" },
    { title: "Control de calidad y recepción", img: "assets/img/obra_negra/control_calidad.jpg", description: "Verificamos que la obra cumpla con las especificaciones antes de su entrega.", link: "https://www.youtube.com/watch?v=VIDEO_ID22" }
  ];

  const obraBlancaItems = [
    { title: "Revoque fino y estuco", img: "assets/img/obra_blanca/estuco.jpg", description: "Aplicamos revoque fino y estuco para alisar muros y dejarlos listos para pintura.", link: "https://www.youtube.com/watch?v=Mdm1iivGiTs" },
    { title: "Pintura interior", img: "assets/img/obra_blanca/pintura.jpg", description: "Pintamos muros y cielos rasos con acabados decorativos y de protección." },
    { title: "Pintura exterior", img: "assets/img/obra_blanca/pintura_exterior.jpeg", description: "Aplicamos pintura y selladores especiales para proteger fachadas." },
    { title: "Instalación de pisos interiores", img: "assets/img/obra_blanca/piso_interior.jpg", description: "Colocamos pisos en cerámica, porcelanato, madera o laminados." },
    { title: "Instalación de cielorrasos", img: "assets/img/obra_blanca/cieloraso.jpg", description: "Montamos cielorrasos en drywall, PVC o materiales acústicos." },
    { title: "Enchapes y revestimientos decorativos", img: "assets/img/obra_blanca/revestimientos.jpg", description: "Instalamos enchapes y revestimientos en muros, cocinas y baños." },
    { title: "Instalación de estructura metálica", img: "assets/img/obra_blanca/instalacion_estructura.jpg", description: "Armamos techos en hierro de diferentes dimensiones, barandas, rejas y elementos metálicos decorativos o de seguridad." },
    { title: "Instalación de ventanas y vidrios", img: "assets/img/obra_blanca/ventanas.jpg", description: "Colocamos ventanas, ventanales y divisiones en vidrio templado o laminado." },
    { title: "Instalación de grifería y accesorios", img: "assets/img/obra_blanca/griferia.jpg", description: "Montamos grifería, toalleros, espejos y demás accesorios en baños y cocinas." },
    { title: "Instalación de iluminación", img: "assets/img/obra_blanca/iluminacion.jpeg", description: "Instalamos lámparas, bombillas LED y sistemas de iluminación decorativa." },
    { title: "Instalación de tomacorrientes e interruptores", img: "assets/img/obra_blanca/tomacorriente.jpg", description: "Colocamos tomacorrientes, interruptores y placas eléctricas." },
    { title: "Instalación de sanitarios y lavamanos", img: "assets/img/obra_blanca/instalacion_sanitarios.jpg", description: "Instalamos piezas sanitarias, lavamanos y lavaplatos." },
    { title: "Colocación de espejos y cristales decorativos", img: "assets/img/obra_blanca/colocacion_espejos.jpg", description: "Instalamos espejos y vidrios decorativos en interiores." },
    { title: "Colocación de rodapiés y molduras", img: "assets/img/obra_blanca/molduras.jpg", description: "Instalamos rodapiés, guardas y molduras decorativas." },
    { title: "Limpieza final de obra", img: "assets/img/obra_blanca/limpieza_obra_blanca.jpg", description: "Realizamos limpieza profunda y detallada antes de la entrega." },
    { title: "Entrega de la obra", img: "assets/img/obra_blanca/entrega.jpg", description: "Hacemos la entrega oficial al cliente con todos los acabados completos." }
  ];

  const mantenimientoItems = [
    { title: "Reparación de Fisuras", img: "imagenes/mantenimiento/reparacion_fisuras.jpg", description: "Sellamos y reforzamos fisuras en muros, losas y elementos estructurales.", link: "https://www.youtube.com/watch?v=Mdm1iivGiTs" },
    { title: "Refuerzo Estructural", img: "imagenes/mantenimiento/refuerzo_estructural.jpg", description: "Fortalecemos vigas, columnas y cimentaciones para prolongar la vida útil." },
    { title: "Pintura Interior y Exterior", img: "imagenes/mantenimiento/pintura.jpg", description: "Aplicamos pintura de alta calidad para renovar y proteger superficies." },
    { title: "Reparación de Enchapes", img: "imagenes/mantenimiento/reparacion_enchapes.jpg", description: "Restauramos y reemplazamos enchapes y cerámicas dañadas." },
    { title: "Mantenimiento Eléctrico", img: "imagenes/mantenimiento/mantenimiento_electrico.jpg", description: "Revisamos y reparamos cableado, tableros y luminarias." },
    { title: "Mantenimiento Hidrosanitario", img: "imagenes/mantenimiento/mantenimiento_hidrosanitario.jpg", description: "Corregimos fugas, drenajes y problemas en redes de agua." },
    { title: "Impermeabilización de Cubiertas", img: "imagenes/mantenimiento/impermeabilizacion_cubiertas.jpg", description: "Evitamos filtraciones mediante impermeabilización profesional." },
    { title: "Limpieza y Restauración de Fachadas", img: "imagenes/mantenimiento/limpieza_fachadas.jpg", description: "Realizamos limpieza profunda y restauración estética de fachadas." },
    { title: "Revisión Preventiva", img: "imagenes/mantenimiento/revision_preventiva.jpg", description: "Inspeccionamos y realizamos mantenimientos periódicos para prevenir daños." },
    { title: "Reparaciones Urgentes", img: "imagenes/mantenimiento/reparaciones_urgentes.jpg", description: "Atendemos daños imprevistos como filtraciones, cortos o desprendimientos." }
  ];

  /* ============================
     3) RENDER DE TARJETAS (index u otras páginas)
     ============================ */
  function createCard({ title, img, description, link }) {
    return `
      <div class="service-card cursor-pointer p-1 border rounded" title="${title}" data-link="${link || ''}">
        <img src="${img || 'imagenes/default.jpg'}" alt="${title}" class="w-full h-36 object-cover rounded">
        <h3 class="mt-2 font-semibold text-sm">${title}</h3>
        <p class="text-xs text-gray-600 mt-1">${description || ''}</p>
      </div>
    `;
  }

  function renderCards(containerId, items) {
    const container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = items.map(createCard).join('');

    // Añadir evento click a cada tarjeta para redirigir a YouTube si tiene link
    container.querySelectorAll('.service-card').forEach(card => {
      const link = card.getAttribute('data-link');
      if (link) {
        card.addEventListener('click', () => {
          window.open(link, '_blank');
        });
      }
    });
  }

  renderCards('obra-negra-section', obraNegraItems);
  renderCards('obra-blanca-section', obraBlancaItems);
  renderCards('mantenimiento-section', mantenimientoItems);

  /* ============================
     4) SIMULADOR (solo si estamos en simulador.html)
     ============================ */
  if (window.location.pathname.includes('simulador.html')) {

    // Mostrar título pasado por querystring (si existe)
    (function mostrarTitulo() {
      const params = new URLSearchParams(window.location.search);
      const title = params.get('title');
      if (title) {
        const tituloElem = document.getElementById('simulador-titulo');
        if (tituloElem) tituloElem.textContent = decodeURIComponent(title);
      }
    })();

    // Arrays que guardan selección con m2
    const seleccion = {
      obraNegra: [],
      obraBlanca: [],
      mantenimiento: []
    };

    // Crear opciones (checkbox + input m2) a partir de un array de items
    function crearOpcionesDesdeItems(categoria, contenedorId, items) {
      const contenedor = document.getElementById(contenedorId);
      if (!contenedor) return;

      contenedor.innerHTML = '';

      items.forEach(item => {
        const idSafe = sanitizeId(item.title);
        const chkId = `${categoria}-${idSafe}`;
        const m2Id = `${chkId}-m2`;

        const wrapper = document.createElement('div');
        wrapper.className = 'flex items-center space-x-3';

       wrapper.innerHTML = `
  <div class="flex items-center space-x-2">
    <input type="checkbox" id="${chkId}" class="form-checkbox h-4 w-4 text-red-600">
    <label for="${chkId}" class="text-gray-700 text-sm">${item.title}</label>
  </div>
  <input type="number" min="0" step="1" id="${m2Id}" placeholder="m²"
      class="ml-auto w-[4.5rem] sm:w-[5rem] md:w-[5.5rem] lg:w-[6rem] xl:w-[6.5rem]
             border border-gray-300 rounded-md px-2 py-1
             text-xs sm:text-sm text-center hidden
             [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
      aria-label="metros cuadrados para ${item.title}">
`;

        // listeners
        const checkbox = wrapper.querySelector(`#${chkId}`);
        const m2input = wrapper.querySelector(`#${m2Id}`);

        checkbox.addEventListener('change', (e) => {
          if (e.target.checked) {
            m2input.classList.remove('hidden');
            // añadir al array si no existe
            if (!seleccion[categoria].find(s => s.servicio === item.title)) {
              seleccion[categoria].push({ servicio: item.title, m2: 0 });
            }
            m2input.focus();
          } else {
            m2input.classList.add('hidden');
            m2input.value = '';
            // quitar del array
            const idx = seleccion[categoria].findIndex(s => s.servicio === item.title);
            if (idx !== -1) seleccion[categoria].splice(idx, 1);
          }
        });

        m2input.addEventListener('input', (e) => {
          const val = parseFloat(e.target.value) || 0;
          const obj = seleccion[categoria].find(s => s.servicio === item.title);
          if (obj) obj.m2 = val;
        });

        contenedor.appendChild(wrapper);
      });
    }

    // Ejecutar para las 3 categorías usando tus arrays principales
    crearOpcionesDesdeItems('obraNegra', 'obraNegraOpciones', obraNegraItems);
    crearOpcionesDesdeItems('obraBlanca', 'obraBlancaOpciones', obraBlancaItems);
    crearOpcionesDesdeItems('mantenimiento', 'mantenimientoOpciones', mantenimientoItems);

    /* ============================
       4.b) Manejar submit del formulario (calcular)
       ============================ */
    const form = document.getElementById('formCotizacion');
    function obtenerPrecioSimulado(servicio, categoria) {
      // <- REEMPLAZA/CONECTA con tu DB o API
      // Ejemplo: precios por categoría base (valor por m²)
      const base = {
        obraNegra: 120000,
        obraBlanca: 45000,
        mantenimiento: 30000
      };
      // Podrías mapear nombres concretos a precios más específicos aquí
      return base[categoria] || 0;
    }

    function formatearCOP(valor) {
      try {
        return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(valor);
      } catch (err) {
        return `$ ${Math.round(valor).toLocaleString()}`;
      }
    }
     function mostrarResultado(total, detalle) {
      // ─── Capturar datos del formulario ───────────────────────────────
    const datosUsuario = {
      nombreCompleto: `${document.getElementById("nombre").value} ${document.getElementById("apellido").value || 'Cliente'}`.trim(),
      tipoDocumento: document.getElementById("tipoDocumento").value || 'No especificado',
      numeroDocumento: document.getElementById("numeroDocumento").value || 'No especificado',
      correo: document.getElementById("correo").value || 'No especificado',
      contacto: document.getElementById("contacto").value || 'No especificado',
      ubicacion: document.getElementById("ubicacion").value || 'No especificada',
      direccion: document.getElementById("direccion").value || 'No especificada',
      contactoPersonalizado: document.getElementById("contactoPersonalizado").checked ? "Sí" : "No",
      fechaVisita: document.getElementById("contactoPersonalizado").checked ? (document.getElementById("fechaVisita").value || 'No especificada') : 'No solicitada',
      descripcion: document.getElementById("descripcion").value || 'Sin descripción',
    };

    // ─── Crear contenedor si no existe ──────────────────────────────
    let box = document.getElementById('resultado-cotizacion');
    if (!box) {
      box = document.createElement('div');
      box.id = 'resultado-cotizacion';
      box.className = 'max-w-4xl mx-auto mt-6';
      form.after(box);
    }
    // ─── Normalizar nombres de categoría ───────────────────────────────
    detalle = detalle.map(d => {
      let categoriaLegible = d.categoria;

      switch (d.categoria) {
        case 'obraNegra':
          categoriaLegible = 'Obra Negra';
          break;
        case 'obraBlanca':
          categoriaLegible = 'Obra Blanca';
          break;
        case 'mantenimiento':
          categoriaLegible = 'Mantenimiento';
          break;
      }

      return { ...d, categoria: categoriaLegible };
    });

// ─── Construir HTML de la factura ───────────────────────────────
box.innerHTML = `
  <div id="factura-container" class="relative w-full mx-auto p-4 sm:p-6 bg-white shadow-lg rounded-lg overflow-hidden">

    <!-- BOTÓN CERRAR (insertado) -->
    <button id="cerrarFactura"
    class="absolute top-2 left-1/2 transform -translate-x-1/2 bg-red-600 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-md border border-red-700">
      ✕
    </button>

    <!-- ENCABEZADO --><br>
    <div class="flex justify-between items-center border-b-2 border-teal-500 pb-6 mb-8">
      <div class="flex items-center gap-6">
        <img src="../assets/img/JB-CONSTRUCTORES.png"
          alt="Logo JB-CONSTRUCTORES"
          class="h-[60px] w-[60px] object-cover rounded-full border-2 border-teal-500">
        <div>
          <h1 class="text-1xl font-extrabold text-teal-700 leading-tight">JB-CONSTRUCTORES</h1>
          <p class="text-lg text-gray-500 mt-1">Factura de Cotización</p>
        </div>
      </div>
      <div class="text-right space-y-1">
        <p class="text-base text-gray-600">Fecha: ${new Date().toLocaleDateString()}</p>
        <p class="text-base text-gray-600">Cotización #${Math.floor(Math.random() * 100000)}</p>
      </div>
    </div>

    <!-- DATOS DEL CLIENTE -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-teal-600 mb-2">Datos del Cliente</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 print:grid-cols-2 gap-4 text-gray-700 text-sm">
        <p><strong>Nombre:</strong> ${datosUsuario.nombreCompleto}</p>
        <p><strong>Número Documento:</strong> ${datosUsuario.numeroDocumento}</p>
        <p><strong>Correo:</strong> ${datosUsuario.correo}</p>
        <p><strong>Contacto:</strong> ${datosUsuario.contacto}</p>
        <p><strong>Ubicación:</strong> ${datosUsuario.ubicacion}</p>
        <p><strong>Dirección:</strong> ${datosUsuario.direccion}</p>
    </div>

    <!-- DETALLE DE SERVICIOS -->
    <div class="mb-8"><br>
      <h2 class="text-xl font-semibold text-teal-600 mb-2">Detalle de Servicios</h2>

      <!-- Contenedor responsivo con scroll si es necesario -->
      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
          <thead>
            <tr class="bg-teal-100 text-teal-800">
              <th class="border p-2 text-left">Servicio</th>
              <th class="border p-2 text-left">Categoría</th>
              <th class="border p-2 text-right">m²</th>
              <th class="border p-2 text-right">Precio/m²</th>
              <th class="border p-2 text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            ${detalle.map(d => `
              <tr>
                <td class="border p-2">${d.servicio}</td>
                <td class="border p-2">${d.categoria}</td>
                <td class="border p-2 text-right">${d.m2}</td>
                <td class="border p-2 text-right">${formatearCOP(d.precioUnitario)}</td>
                <td class="border p-2 text-right font-semibold">${formatearCOP(d.subtotal)}</td>
              </tr>
            `).join('')}
          </tbody>
          <tfoot>
            <tr class="bg-gray-100">
              <td colspan="4" class="border p-2 text-right font-bold">Total Estimado</td>
              <td class="border p-2 text-right text-2xl font-bold text-red-600">${formatearCOP(total)}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>


    <!-- NOTA -->
    <p class="text-gray-600 text-sm italic">
      * Los valores son estimados. Los precios finales pueden variar tras la visita técnica.
    </p>

    <!-- BOTONES DE EXPORTACIÓN -->
    <div class="mt-6 flex flex-wrap gap-4 justify-center">
      <button id="btnExportPDF" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Descargar Cotización</button>
    </div>
  </div>
`;

// — Inserción del listener mínimo para el botón cerrar (restaura overflowX)
const btnCerrar = document.getElementById('cerrarFactura');
if (btnCerrar) {
  btnCerrar.addEventListener('click', () => {
    const factura = document.getElementById('factura-container');
    if (factura) factura.remove();
    document.documentElement.style.overflowX = '';
    document.body.style.overflowX = '';
  });
}
      // ─── Ajustes de visualización en navegador ──────────
      document.documentElement.style.overflowX = 'hidden'; 
      document.body.style.overflowX = 'hidden';

      // ─── Función para escalar solo el contenedor ──────────
      function ajustarEscala() {
        const factura = document.querySelector('#factura-container');
        if (!factura) return;

      }

      // Ejecutar al cargar y al redimensionar
      ajustarEscala();
      window.addEventListener('resize', ajustarEscala);
      window.addEventListener('orientationchange', ajustarEscala);

      // ─── Exportar PDF ──────────
      document.getElementById('btnExportPDF').addEventListener('click', () => {
        const factura = document.querySelector('#factura-container');
        if (!factura) return;

        // 1) localizar el contenedor de "Datos del Cliente"
        let clienteGrid = factura.querySelector('#datos-cliente'); 
        if (!clienteGrid) {
          const h2s = factura.querySelectorAll('h2');
          for (const h of h2s) {
            if (h.textContent.trim().toLowerCase().includes('datos del cliente')) {
              clienteGrid = h.parentElement.querySelector('.grid') || h.nextElementSibling;
              break;
            }
          }
        }

        // Guardar estado original
        const oldClienteStyle = clienteGrid ? clienteGrid.getAttribute('style') || '' : null;
        const oldClienteClass = clienteGrid ? clienteGrid.className : null;

        // Forzar 2 columnas en exportación
        if (clienteGrid) {
          try { clienteGrid.classList.remove('grid-cols-1'); } catch(e){}
          clienteGrid.style.display = 'grid';
          clienteGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
          clienteGrid.style.gap = '0.75rem';
          clienteGrid.style.gridAutoRows = 'min-content';
          clienteGrid.querySelectorAll('p').forEach(p => p.style.margin = '0');
        }

        // Quitar sombra para evitar manchas
        const card = factura.querySelector('.shadow-2xl');
        const hadShadow = !!card;
        if (card) card.classList.remove('shadow-2xl');

        // Quitar botones al exportar
        const exportButtons = factura.querySelector('#botones-export') || factura.querySelector('.mt-6');
        let placeholder = null, parent = null;
        if (exportButtons) {
          parent = exportButtons.parentNode;
          placeholder = document.createComment('export-placeholder');
          parent.replaceChild(placeholder, exportButtons);
        }

        // Generar PDF
        html2pdf().from(factura).set({
          margin: [10, 10, 10, 10],
          filename: `Cotizacion JB-CONTRUCCIONES_${new Date().toISOString().slice(0,10)}.pdf`,
          image: { type: 'jpeg', quality: 0.98 },
          html2canvas: { scale: 2, useCORS: true, letterRendering: true },
          jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        }).save()
          .catch(err => console.error('Error generando PDF:', err))
          .finally(() => {
            // Restaurar botones
            if (exportButtons && parent && placeholder) parent.replaceChild(exportButtons, placeholder);

            // Restaurar grid
            if (clienteGrid) {
              clienteGrid.setAttribute('style', oldClienteStyle);
              clienteGrid.className = oldClienteClass;
              clienteGrid.querySelectorAll('p').forEach(p => p.style.margin = '');
            }

            // Restaurar sombra
            if (card && hadShadow) card.classList.add('shadow-2xl');

            // Restaurar transform de pantalla
            factura.style.transform = oldTransform;
          });
      });

      // ─── Scroll al resultado ────────────────────────────────────────
      box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();

        const detalle = [];
        let total = 0;
        ['obraNegra', 'obraBlanca', 'mantenimiento'].forEach(categoria => {
          seleccion[categoria].forEach(item => {
            const precioUnitario = obtenerPrecioSimulado(item.servicio, categoria);
            const subtotal = (item.m2 || 0) * precioUnitario;
            detalle.push({ categoria, servicio: item.servicio, m2: item.m2 || 0, precioUnitario, subtotal });
            total += subtotal;
          });
        });

        if (detalle.length === 0) {
          alert('Por favor seleccione al menos un servicio y registre los m² correspondientes.');
          return;
        }

        mostrarResultado(total, detalle);
      });
    }
  }
  const contactoCheckbox = document.getElementById('contactoPersonalizado');
const campoFechaVisita = document.getElementById('campoFechaVisita');

if (contactoCheckbox && campoFechaVisita) {
  contactoCheckbox.addEventListener('change', () => {
    if (contactoCheckbox.checked) {
      campoFechaVisita.classList.remove('hidden');
    } else {
      campoFechaVisita.classList.add('hidden');
      // Opcional: limpiar fecha al ocultar
      document.getElementById('fechaVisita').value = '';
    }
  });
}

});