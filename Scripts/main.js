document.addEventListener('DOMContentLoaded', () => {
  // Detectar la ruta correcta del sidebar según ubicación
  let sidebarPath = "Encabezado_Pie/sidebar.html";
  if (window.location.pathname.includes("/Paginas/")) {
    sidebarPath = "../" + sidebarPath;
  }

  // Cargar sidebar
  fetch(sidebarPath)
    .then(response => response.text())
    .then(html => {
      document.getElementById("sidebar-container").innerHTML = html;

      // Activar toggle para menú móvil
      const mobileMenuButton = document.querySelector('.md\\:hidden');
      const sidebar = document.querySelector('.sidebar');
      if (mobileMenuButton && sidebar) {
        mobileMenuButton.addEventListener('click', () => sidebar.classList.toggle('hidden'));
      }

      // Marcar enlace activo según la URL actual
      const currentPath = window.location.pathname.split('/').pop();
      const links = sidebar.querySelectorAll('a');
      links.forEach(link => {
        const href = link.getAttribute('href').split('/').pop();
        if (href === currentPath) {
          link.classList.add('bg-red-800', 'font-semibold');
        }
      });
    });

  // Datos para las tarjetas de Obra Negra
  const obraNegraItems = [

  {
    title: "Preparación y limpieza del terreno",
    img: "assets/img/obra_negra/preparacion_limpieza.jpg",
    description: "Despejamos y nivelamos el terreno para iniciar la construcción."
  },
  {
    title: "Verificación topográfica y marcación de ejes",
    img: "assets/img/obra_negra/verificacion_topografica_marcacion.jpg",
    description: "Comprobamos medidas y niveles, y trazamos los ejes para la cimentación."
  },
  {
    title: "Excavación y manejo de tierras",
    img: "assets/img/obra_negra/excavacion.jpg",
    description: "Realizamos excavaciones y gestionamos el material sobrante."
  },
  {
    title: "Relleno y compactación",
    img: "assets/img/obra_negra/relleno_compactacion.jpg",
    description: "Compactamos el terreno para garantizar la estabilidad de la cimentación."
  },
  {
    title: "Cimentación",
    img: "assets/img/obra_negra/cimentacion.jpg",
    description: "Construimos zapatas, pilotes o losa de cimentación según el diseño estructural."
  },
  {
    title: "Elaboración y armado de acero",
    img: "assets/img/obra_negra/estructura_metalica.jpg",
    description: "Cortamos, doblamos y ensamblamos el acero de refuerzo para la estructura."
  },
  {
    title: "Encofrado y vaciado de concreto",
    img: "assets/img/obra_negra/encofrados_vaciados.jpg",
    description: "Colocamos encofrados y vaciamos concreto para elementos estructurales."
  },
  {
    title: "Armado de losa",
    img: "assets/img/obra_negra/losas.jpg",
    description: "Preparamos y fundimos las losas de entrepiso o cubierta."
  },
  {
    title: "Curado y control de calidad",
    img: "assets/img/obra_negra/Curado.jpg",
    description: "Aplicamos curado y realizamos ensayos para garantizar la resistencia del concreto."
  },
  {
    title: "Replanteo de mampostería",
    img: "assets/img/obra_negra/replanteo_mamposteria.jpg",
    description: "Marcamos muros y aberturas antes de levantar mampostería."
  },
  {
    title: "Mampostería primer - segundo nivel",
    img: "assets/img/obra_negra/mamposteria.jpeg",
    description: "Levantamos muros y paredes en primer, segundo nivel."
  },
  {
    title: "Instalaciones hidrosanitarias",
    img: "assets/img/obra_negra/instalaciones_hidrosanitarias.jpg",
    description: "Instalamos tuberías de agua potable, aguas negras y pluviales."
  },
  {
    title: "Instalación eléctrica",
    img: "assets/img/obra_negra/instalacion_electrica.jpg",
    description: "Realizamos el tendido de conduits y puntos eléctricos."
  },
  {
    title: "Impermeabilización",
    img: "assets/img/obra_negra/impermeabilizacion.jpg",
    description: "Protegemos cubiertas y muros contra filtraciones de agua."
  },
  {
    title: "Revoque",
    img: "assets/img/obra_negra/revoque.jpg",
    description: "Aplicamos revoque en muros interiores y exteriores."
  },
  {
    title: "Morteros",
    img: "assets/img/obra_negra/morteros.jpg",
    description: "Preparamos y aplicamos morteros para nivelación y asiento."
  },
  {
    title: "Enchapes interiores",
    img: "assets/img/obra_negra/enchapes.jpg",
    description: "Colocamos cerámica o porcelanato en pisos y paredes."
  },
  {
    title: "Instalación de baños",
    img: "assets/img/obra_negra/instalacion_banos.jpg",
    description: "Montamos inodoros, lavamanos, duchas y grifería."
  },
  {
    title: "Enchape fachada",
    img: "assets/img/obra_negra/fachada.jpeg",
    description: "Revestimos la fachada con piedra, cerámica o materiales decorativos."
  },
  {
    title: "Lechada y fragüe",
    img: "assets/img/obra_negra/lechada.jpg",
    description: "Sellamos juntas y realizamos la limpieza final de los enchapes."
  },
  {
    title: "Pruebas y limpieza final",
    img: "assets/img/obra_negra/limpieza.jpg",
    description: "Realizamos pruebas de funcionamiento y limpieza general de obra."
  },
  {
    title: "Control de calidad y recepción",
    img: "assets/img/obra_negra/control_calidad.jpg",
    description: "Verificamos que la obra cumpla con las especificaciones antes de su entrega."
  }
];

  // Datos para las tarjetas de Obra Blanca
  const obraBlancaItems = [
  {
    title: "Revoque fino y estuco",
    img: "assets/img/obra_blanca/estuco.jpg",
    description: "Aplicamos revoque fino y estuco para alisar muros y dejarlos listos para pintura."
  },
  {
    title: "Pintura interior",
    img: "assets/img/obra_blanca/pintura.jpg",
    description: "Pintamos muros y cielos rasos con acabados decorativos y de protección."
  },
  {
    title: "Pintura exterior",
    img: "assets/img/obra_blanca/pintura_exterior.jpeg",
    description: "Aplicamos pintura y selladores especiales para proteger fachadas."
  },
  {
    title: "Instalación de pisos interiores",
    img: "assets/img/obra_blanca/piso_interior.jpg",
    description: "Colocamos pisos en cerámica, porcelanato, madera o laminados."
  },
  {
    title: "Instalación de cielorrasos",
    img: "assets/img/obra_blanca/cieloraso.jpg",
    description: "Montamos cielorrasos en drywall, PVC o materiales acústicos."
  },
  {
    title: "Enchapes y revestimientos decorativos",
    img: "assets/img/obra_blanca/revestimientos.jpg",
    description: "Instalamos enchapes y revestimientos en muros, cocinas y baños."
  },
  {
    title: "Instalación de estructura metálica",
    img: "assets/img/obra_blanca/instalacion_estructura.jpg",
    description: "Armamos techos en hierro de diferentes dimensiones, barandas, rejas y elementos metálicos decorativos o de seguridad."
  },
  {
    title: "Instalación de ventanas y vidrios",
    img: "assets/img/obra_blanca/ventanas.jpg",
    description: "Colocamos ventanas, ventanales y divisiones en vidrio templado o laminado."
  },
  {
    title: "Instalación de grifería y accesorios",
    img: "assets/img/obra_blanca/griferia.jpg",
    description: "Montamos grifería, toalleros, espejos y demás accesorios en baños y cocinas."
  },
  {
    title: "Instalación de iluminación",
    img: "assets/img/obra_blanca/iluminacion.jpeg",
    description: "Instalamos lámparas, bombillas LED y sistemas de iluminación decorativa."
  },
  {
    title: "Instalación de tomacorrientes e interruptores",
    img: "assets/img/obra_blanca/tomacorriente.jpg",
    description: "Colocamos tomacorrientes, interruptores y placas eléctricas."
  },
  {
    title: "Instalación de sanitarios y lavamanos",
    img: "assets/img/obra_blanca/instalacion_sanitarios.jpg",
    description: "Instalamos piezas sanitarias, lavamanos y lavaplatos."
  },
  {
    title: "Colocación de espejos y cristales decorativos",
    img: "assets/img/obra_blanca/colocacion_espejos.jpg",
    description: "Instalamos espejos y vidrios decorativos en interiores."
  },
  {
    title: "Colocación de rodapiés y molduras",
    img: "assets/img/obra_blanca/molduras.jpg",
    description: "Instalamos rodapiés, guardas y molduras decorativas."
  },
  {
    title: "Limpieza final de obra",
    img: "assets/img/obra_blanca/limpieza_obra_blanca.jpg",
    description: "Realizamos limpieza profunda y detallada antes de la entrega."
  },
  {
    title: "Entrega de la obra",
    img: "assets/img/obra_blanca/entrega.jpg",
    description: "Hacemos la entrega oficial al cliente con todos los acabados completos."
  }
];

  // Datos para las tarjetas de Mantenimiento
  const mantenimientoItems = [
  {
    title: "Reparación de Fisuras",
    img: "imagenes/mantenimiento/reparacion_fisuras.jpg",
    description: "Sellamos y reforzamos fisuras en muros, losas y elementos estructurales."
  },
  {
    title: "Refuerzo Estructural",
    img: "imagenes/mantenimiento/refuerzo_estructural.jpg",
    description: "Fortalecemos vigas, columnas y cimentaciones para prolongar la vida útil."
  },
  {
    title: "Pintura Interior y Exterior",
    img: "imagenes/mantenimiento/pintura.jpg",
    description: "Aplicamos pintura de alta calidad para renovar y proteger superficies."
  },
  {
    title: "Reparación de Enchapes",
    img: "imagenes/mantenimiento/reparacion_enchapes.jpg",
    description: "Restauramos y reemplazamos enchapes y cerámicas dañadas."
  },
  {
    title: "Mantenimiento Eléctrico",
    img: "imagenes/mantenimiento/mantenimiento_electrico.jpg",
    description: "Revisamos y reparamos cableado, tableros y luminarias."
  },
  {
    title: "Mantenimiento Hidrosanitario",
    img: "imagenes/mantenimiento/mantenimiento_hidrosanitario.jpg",
    description: "Corregimos fugas, drenajes y problemas en redes de agua."
  },
  {
    title: "Impermeabilización de Cubiertas",
    img: "imagenes/mantenimiento/impermeabilizacion_cubiertas.jpg",
    description: "Evitamos filtraciones mediante impermeabilización profesional."
  },
  {
    title: "Limpieza y Restauración de Fachadas",
    img: "imagenes/mantenimiento/limpieza_fachadas.jpg",
    description: "Realizamos limpieza profunda y restauración estética de fachadas."
  },
  {
    title: "Revisión Preventiva",
    img: "imagenes/mantenimiento/revision_preventiva.jpg",
    description: "Inspeccionamos y realizamos mantenimientos periódicos para prevenir daños."
  },
  {
    title: "Reparaciones Urgentes",
    img: "imagenes/mantenimiento/reparaciones_urgentes.jpg",
    description: "Atendemos daños imprevistos como filtraciones, cortos o desprendimientos."
  }
];

  // Función para crear tarjeta con imagen, título y descripción
  function createCard({ title, link, img, description }) {
    const cursor = link ? "cursor-pointer" : "";
    const onclick = link ? `onclick="window.location.href='${link}'"` : "";
    return `
      <div class="service-card ${cursor}" ${onclick}>
        <img src="${img || 'imagenes/default.jpg'}" alt="${title}">
        <h3>${title}</h3>
        <p>${description || ''}</p>
      </div>
    `;
  }

  // Renderizar tarjetas en el contenedor con grid definido en HTML
  function renderCards(containerId, items) {
    const container = document.getElementById(containerId);
    if (container) {
      container.innerHTML = items.map(createCard).join('');
    }
  }

  // Pintar las tarjetas para cada sección
  renderCards('obra-negra-section', obraNegraItems);
  renderCards('obra-blanca-section', obraBlancaItems);
  renderCards('mantenimiento-section', mantenimientoItems);
});


