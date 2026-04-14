document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('formCotizacion');

  /* =========================
     1. Mostrar campo fecha
     ========================= */
  const contactoCheckbox = document.getElementById('contactoPersonalizado');
  const campoFechaVisita = document.getElementById('campoFechaVisita');

  if (contactoCheckbox && campoFechaVisita) {
    contactoCheckbox.addEventListener('change', () => {
      if (contactoCheckbox.checked) {
        campoFechaVisita.classList.remove('hidden');
      } else {
        campoFechaVisita.classList.add('hidden');
        document.getElementById('fechaVisita').value = '';
      }
    });
  }

  /* =========================
     2. Mostrar input m²
     ========================= */
  const checks = document.querySelectorAll('.servicio-check');

  checks.forEach(check => {
    check.addEventListener('change', function () {

      const container = this.closest('.servicio-item');
      const inputM2 = container.querySelector('.m2');

      if (this.checked) {
        inputM2.classList.remove('hidden');
        inputM2.focus();
      } else {
        inputM2.classList.add('hidden');
        inputM2.value = '';
      }
    });
  });

  /* =========================
     3. Obtener categoría
     ========================= */
  function obtenerCategoria(elemento) {
    const columna = elemento.closest('[id]');
    const id = columna.id;

    if (id.includes('obraNegra')) return 'Obra Negra';
    if (id.includes('obraGris')) return 'Obra Gris';
    if (id.includes('obraBlanca')) return 'Obra Blanca';
    if (id.includes('mantenimiento')) return 'Mantenimiento';

    return 'General';
  }

  /* =========================
     4. Formatear dinero
     ========================= */
  function formatearCOP(valor) {
    return new Intl.NumberFormat('es-CO', {
      style: 'currency',
      currency: 'COP',
      maximumFractionDigits: 0
    }).format(valor);
  }

  /* =========================
     5. Mostrar resultado
     ========================= */
  function mostrarResultado(total, detalle) {

    const datosUsuario = {
    nombreCompleto: `${document.getElementById("nombre")?.value || ''} ${document.getElementById("apellido")?.value || ''}`.trim() || 'No especificado',
    numeroDocumento: document.getElementById("numeroDocumento")?.value || 'No especificado',
    correo: document.getElementById("correo")?.value || 'No especificado',
    contacto: document.getElementById("contacto")?.value || 'No especificado',
    ubicacion: document.getElementById("ubicacion")?.value || 'No especificada',
    direccion: document.getElementById("direccion")?.value || 'No especificada'
    };

    let box = document.getElementById('resultado-cotizacion');

    if (!box) {
      box = document.createElement('div');
      box.id = 'resultado-cotizacion';
      box.className = 'max-w-4xl mx-auto mt-6';
      form.after(box);
    }

    box.innerHTML = `
    <div id="factura-container" class="relative w-full mx-auto p-2 sm:p-6 bg-white shadow-lg rounded-lg">

    <!-- ENCABEZADO -->
    <div class="flex justify-between items-center border-b-2 border-teal-500 pb-6 mb-8">
        <div class="flex flex-col items-center gap-2">
        <img src="../../assets/img/JB-CONSTRUCTORES.png"
            class="h-16 w-16 object-cover rounded-full border-2 border-teal-500">
        <h1 class="text-lg font-extrabold text-teal-700">JB-CONSTRUCTORES</h1>
        </div>

        <div class="text-right space-y-1">
        <p class="text-gray-600">${new Date().toLocaleDateString()}</p>
        <p class="text-gray-600">${new Date().toLocaleTimeString()}</p>
        <p class="text-gray-600">Cotización #${Math.floor(Math.random() * 10000)}</p>
        </div>
    </div>

    <!-- DATOS CLIENTE -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-teal-600 mb-2">Datos del Cliente</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <p><strong>Nombre:</strong> ${datosUsuario.nombreCompleto}</p>
        <p><strong>Documento:</strong> ${datosUsuario.numeroDocumento}</p>
        <p><strong>Correo:</strong> ${datosUsuario.correo}</p>
        <p><strong>Contacto:</strong> ${datosUsuario.contacto}</p>
        <p><strong>Ubicación:</strong> ${datosUsuario.ubicacion}</p>
        <p><strong>Dirección:</strong> ${datosUsuario.direccion}</p>
        </div>
    </div>

    <!-- DETALLE -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-teal-600 mb-2">Detalle de Servicios</h2>

        <div class="overflow-x-auto">
        <table class="w-full border text-sm">
            <thead>
            <tr class="bg-teal-100">
                <th class="border p-2">Servicio</th>
                <th class="border p-2">Categoría</th>
                <th class="border p-2 text-right">m²</th>
                <th class="border p-2 text-right">Precio</th>
                <th class="border p-2 text-right">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            ${detalle.map(d => `
                <tr>
                <td class="border p-2">${d.servicio_nombre}</td>
                <td class="border p-2">${d.categoria}</td>
                <td class="border p-2 text-right">${d.metros}</td>
                <td class="border p-2 text-right">${formatearCOP(d.precio_unitario)}</td>
                <td class="border p-2 text-right font-semibold">${formatearCOP(d.subtotal)}</td>
                </tr>
            `).join('')}
            </tbody>
            <tfoot>
            <tr class="bg-gray-100">
                <td colspan="4" class="text-right font-bold p-2">Total</td>
                <td class="text-right text-xl font-bold text-red-600 p-2">
                ${formatearCOP(total)}
                </td>
            </tr>
            </tfoot>
        </table>
        </div>
    </div>

    <!-- NOTA -->
    <p class="text-sm italic text-gray-600">
        * Valores estimados sujetos a validación técnica.
    </p>

    <!-- BOTONES -->
    <div class="mt-6 flex justify-center gap-4 flex-wrap">

        <button id="cerrarFactura"
        class="w-40 h-10 bg-red-600 text-white rounded-lg hover:bg-red-700">
        Cerrar
        </button>

        <button id="btnEnviarCorreo"
        class="w-40 h-10 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Enviar Correo
        </button>

    </div>

    </div>
    `;

    const btnCerrar = document.getElementById('cerrarFactura');

    if (btnCerrar) {
    btnCerrar.addEventListener('click', () => {
        const factura = document.getElementById('factura-container');
        if (factura) factura.remove();
    });
    }


const btnCorreo = document.getElementById('btnEnviarCorreo');

if (btnCorreo) {
  btnCorreo.addEventListener('click', async () => {

    const correo = document.getElementById("correo").value;

    if (!correo) {
      alert("Ingrese un correo válido");
      return;
    }

    try {
      const res = await fetch('/JB-CONSTRUCCIONES/app/controllers/EnviarCorreoController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({correo, total, detalle,cliente: {
            nombreCompleto: document.getElementById("nombre").value + " " + document.getElementById("apellido").value,
            numeroDocumento: document.getElementById("numeroDocumento").value,
            correo: document.getElementById("correo").value,
            contacto: document.getElementById("contacto").value,
            ubicacion: document.getElementById("ubicacion").value,
            direccion: document.getElementById("direccion").value
          }
        })
      });

      //  LEER RESPUESTA CORRECTAMENTE
      const text = await res.text();
      console.log("RAW RESPONSE CORREO:", text);

      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        console.error("El servidor no devolvió JSON válido:", text);
        alert("Error inesperado del servidor");
        return;
      }

      // VALIDACIÓN CORRECTA
      if (data.ok) {
        alert('Correo enviado correctamente');
      } else {
        alert('Error: ' + (data.error || 'Error desconocido'));
      }

    } catch (err) {
      console.error("ERROR FETCH:", err);
      alert('Error de conexión (fetch falló)');
    }
  });
}

}


function obtenerDetalleServicios() {
  const servicios = document.querySelectorAll('.servicio-item');

  const detalle = [];

  servicios.forEach(item => {
    const check = item.querySelector('.servicio-check');
    const inputM2 = item.querySelector('.m2');
    const label = item.querySelector('label');

    if (check.checked && inputM2.value) {
      const metros = parseFloat(inputM2.value);
      const precio = parseFloat(check.dataset.precio);

      detalle.push({
        servicio_id: check.dataset.id,
        servicio_nombre: label.textContent.trim(), //  NOMBRE
        categoria: obtenerCategoria(item), //  CATEGORÍA
        metros: metros,
        precio_unitario: precio,
        subtotal: metros * precio
      });
    }
  });

  return detalle;
}


  /* =========================
     6. Guardar en BD
     ========================= */
async function guardarCotizacion(detalle, total) {

  const nombres = document.getElementById("nombre")?.value || '';
  const apellidos = document.getElementById("apellido")?.value || '';

  const datos = {
    nombres: nombres || 'No especificado',
    apellidos: apellidos || 'No especificado',
    tipo_documento: document.getElementById("tipoDocumento")?.value || 'No especificado',
    numero_documento: document.getElementById("numeroDocumento")?.value || 'No especificado',
    correo: document.getElementById("correo")?.value || 'No especificado',
    contacto: document.getElementById("contacto")?.value || 'No especificado',
    ubicacion: document.getElementById("ubicacion")?.value || 'No especificada',
    direccion: document.getElementById("direccion")?.value || 'No especificada',
    descripcion: document.getElementById("descripcion")?.value || 'Sin descripción',
    ser_contactado: 1,
    fecha_visita: document.getElementById("fechaVisita")?.value || null,
    total_estimado: total,
    detalle: detalle
  };

  try {
    const res = await fetch('/JB-CONSTRUCCIONES/app/controllers/CotizacionController.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(datos)
    });

    const text = await res.text();
    console.log("RAW RESPONSE:", text);

    let response;
    try {
      response = JSON.parse(text);
    } catch (e) {
      console.error("JSON inválido:", text);
      return;
    }

    //  VALIDACIÓN REAL
    if (response.success) {

      console.log("Cotización guardada:", response.id_cotizacion);

      //  AQUÍ SE SOLUCIONA TU PROBLEMA
      mostrarResultado(total, detalle);

    } else {
      console.error("Error backend:", response);
      alert("Error al guardar cotización");
    }

  } catch (err) {
    console.error('Error guardando:', err);
  }
}

  /* =========================
     7. SUBMIT (CLAVE)
     ========================= */
  form.addEventListener('submit', function(e) {
  e.preventDefault();

  const detalle = obtenerDetalleServicios();

  if (detalle.length === 0) {
    alert("Selecciona al menos un servicio");
    return;
  }

  const total = detalle.reduce((acc, d) => acc + d.subtotal, 0);

  guardarCotizacion(detalle, total);
});
});