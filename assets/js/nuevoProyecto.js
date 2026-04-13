document.addEventListener("DOMContentLoaded", () => {

    const BASE_URL = "http://localhost/JB-CONSTRUCCIONES";

    /* =========================
       1. BUSCAR COTIZACIÓN
       ========================= */
    window.buscarCotizacion = async function () {

        const valor = document.getElementById("buscarCotizacion").value.trim();

        if (!valor) {
            alert("Ingrese un valor");
            return;
        }

        try {
            const res = await fetch(`${BASE_URL}/app/ajax/buscarCotizacion.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ busqueda: valor })
            });

            const text = await res.text();
            console.log("RESPUESTA:", text);

            let data;
            try {
                data = JSON.parse(text);
            } catch {
                alert("Error backend (no JSON)");
                return;
            }

            if (!data.success) {
                alert(data.error || "No encontrada");
                return;
            }

            const c = data.data;

            document.getElementById("cotizacion_id").value = c.idcotizacion || '';
            document.getElementById("nombre").value = c.nombres || '';
            document.getElementById("apellido").value = c.apellidos || '';
            document.getElementById("correo").value = c.correo || '';
            document.getElementById("contacto").value = c.contacto || '';
            document.getElementById("ubicacion").value = c.ubicacion || '';
            document.getElementById("direccion").value = c.direccion || '';
            document.getElementById("descripcion").value = c.descripcion || '';

            alert("Cotización cargada ✔");

        } catch (err) {
            console.error(err);
            alert("Error conexión");
        }
    };

    /* =========================
       2. GUARDAR PROYECTO
       ========================= */
    document.getElementById("formProyecto").addEventListener("submit", async (e) => {

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

            const res = await fetch(`${BASE_URL}/app/ajax/guardarProyecto.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            });

            const text = await res.text();
            console.log("GUARDAR:", text);

            let r;
            try {
                r = JSON.parse(text);
            } catch {
                alert("Error backend (HTML)");
                return;
            }

            if (r.success) {
                alert("Proyecto creado ✔");
                window.location.href = "gestionproyectos.php";
            } else {
                alert(r.error || "Error al guardar");
            }

        } catch (err) {
            console.error(err);
            alert("Error conexión");
        }

    });

});