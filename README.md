# JB-CONSTRUCCIONES

Plataforma web para la gestión integral de cotizaciones y proyectos 
de construcción, orientada a la automatización de procesos 
comerciales y operativos mediante arquitectura MVC.

---

## Vista General

Sistema diseñado para cubrir el flujo completo:

* Captación de cliente
* Generación de cotización
* Persistencia de información
* Conversión a proyecto
* Gestión operativa

---

## Problema que Resuelve

En muchos entornos del sector construcción:

* Las cotizaciones se realizan manualmente
* No existe trazabilidad entre cliente y proyecto
* La información se dispersa en múltiples medios
* No hay control estructurado del avance

JB-CONSTRUCCIONES centraliza y automatiza este proceso.

---

## Solución Propuesta

El sistema permite:

* Generar cotizaciones dinámicas basadas en servicios y área
* Enviar cotizaciones automáticamente por correo
* Almacenar información estructurada en base de datos
* Transformar cotizaciones en proyectos reales
* Gestionar proyectos con control de estado
* Comunicación instantanea por Whatssapp
* Georreferencia de proyectos por ggogle maps

---

## Módulos del Sistema

### Simulador de Cotización

* Selección de servicios por categoría
* Cálculo automático por m²
* Generación de cotización
* Envío por correo electrónico

---

### Resultado de Cotización

* Visualización clara del detalle
* Datos del cliente
* Total estimado
* Persistencia en base de datos

---

### Gestión de Proyectos

* Creación manual o desde cotización
* Estados del proyecto:

  * Pendiente
  * En ejecución
  * Pausado
  * Finalizado
  * Cancelado
* Visualización tipo dashboard

---

## Arquitectura del Sistema

```plaintext
app/
├── models/
├── controllers/
├── views/
├── ajax/
├── assets/
```

Principios aplicados:

* Separación de responsabilidades
* Escalabilidad
* Mantenibilidad
* Reutilización de componentes

---

## Tecnologías Utilizadas

### Backend

* PHP (compatible con entorno v 8.2)
* MySQL
* PDO

### Frontend

* HTML5
* CSS3
* Tailwind CSS
* JavaScript (ES6+)
* Fetch API

### Integraciones

* PHPMailer (envío de correos)
* WhatsApp Web (contacto directo con clientes)
* Google Maps (visualización de ubicación)
* YouTube (en proceso de integración)

---

## Modelo de Datos

### Tablas principales

* usuario
* usuario_has_rol
* rol
* cotizacion
* cotizacion_servicio
* proyecto
* servicio
* categoria

### Relaciones clave

* Una cotización puede incluir múltiples servicios
* Un proyecto puede originarse desde una cotización
* Un usuario puede tener múltiples roles
* Los servicios están organizados por categorías

---

## Flujo del Sistema

```plaintext
Usuario genérico
    → Simulador
    → Cotización
    → Guardado en base de datos
    → Envío de correo

Administrador
    → Todo lo anterior
    → Búsqueda de cotización
    → Creación de proyecto
    → Registro en tabla proyecto
    → Visualización en gestión de proyectos
```

---

## Despliegue

Plataforma utilizada:

Railway

### Configuración

1. Crear proyecto en Railway
2. Conectar repositorio
3. Configurar variables de entorno:

   * DB_HOST
   * DB_NAME
   * DB_USER
   * DB_PASS
4. Configurar base de datos MySQL
5. Ajustar rutas del sistema

---

## Seguridad

* Validación de sesión en rutas protegidas
* Control de acceso por rol
* Uso de consultas preparadas (PDO)
* Manejo seguro de datos

---

## Estado del Proyecto

En desarrollo activo

Líneas de evolución:

* Integración completa con YouTube
* Panel de métricas
* Mejoras de experiencia de usuario
* Optimización responsive

---

## Autor

Johnny Alexander Pineda Bustamante
Tegnologo en analisis y desarrollado de Software

---

## Licencia

Uso académico y demostrativo
