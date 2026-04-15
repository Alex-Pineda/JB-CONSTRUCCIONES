document.addEventListener("DOMContentLoaded", () => {


    /* =========================
       1. BUSCAR COTIZACIÓN
       ========================= */
    window.buscarCotizacion = async function () {
        const inputBuscar = document.getElementById("buscarCotizacion");
        const valor = inputBuscar ? inputBuscar.value.trim() : '';

        if (!valor) {
            alert("Ingrese un número de cotización o documento");
            return;
        }

        try {
            // Usamos la variable global BASE_URL inyectada desde PHP
            const res = await fetch(`${BASE_URL}app/ajax/buscarCotizacion.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ busqueda: valor })
            });

            // Verificamos si la respuesta es OK (200)
            if (!res.ok) throw new Error("Error en la respuesta del servidor");

            const data = await res.json();

            if (!data.success) {
                alert(data.error || "Cotización no encontrada");
                return;
            }

            const c = data.data;

            // Mapeo de campos (Asegúrate de que los IDs coincidan en tu HTML)
            const campos = {
                "cotizacion_id": c.idcotizacion,
                "nombre": c.nombres,
                "apellido": c.apellidos,
                "correo": c.correo,
                "contacto": c.contacto,
                "ubicacion": c.ubicacion,
                "direccion": c.direccion,
                "descripcion": c.descripcion
            };

            for (let id in campos) {
                const el = document.getElementById(id);
                if (el) el.value = campos[id] || '';
            }

            alert("Cotización cargada ✔");

        } catch (err) {
            console.error("Error al buscar:", err);
            alert("Error: El servidor no devolvió un formato válido (JSON)");
        }
    };

    /* =========================
       2. GUARDAR PROYECTO
       ========================= */
    const formProyecto = document.getElementById("formProyecto");
    if (formProyecto) {
        formProyecto.addEventListener("submit", async (e) => {
            e.preventDefault();

            const datos = {
                cotizacion_id: document.getElementById("cotizacion_id").value || null,
                nombre_proyecto: document.getElementById("nombre_proyecto").value,
                descripcion: document.getElementById("descripcion").value,
                direccion: document.getElementById("direccion").value,
                ubicacion: document.getElementById("ubicacion").value,
                fecha_inicio: document.getElementById("fecha_inicio").value,
                fecha_fin: document.getElementById("fecha_fin").value
            };

            try {
                const res = await fetch(`${BASE_URL}app/ajax/guardarProyecto.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(datos)
                });

                const r = await res.json();

                if (r.success) {
                    alert("Proyecto creado con éxito ✔");
                    // Redirección dinámica absoluta
                    window.location.href = `${BASE_URL}app/views/proyectos/gestionproyectos.php`;
                } else {
                    alert(r.error || "Error al guardar el proyecto");
                }

            } catch (err) {
                console.error("Error al guardar:", err);
                alert("Error de conexión o error interno del servidor");
            }
        });
    }
});