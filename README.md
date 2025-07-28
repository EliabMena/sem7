
# Sistema de Trivias (PHP + Bootstrap)

Proyecto completo para gestión de trivias, usuarios y administración, usando PHP, Bootstrap y MySQL.

## Estructura del Proyecto

- `public/` — Archivos públicos, vistas, dashboards, formularios, CRUD, reportes y punto de entrada (`index.php`).
- `assets/` — Imágenes, CSS y JS personalizados.
- `src/` — Lógica de la aplicación, clases, API, sanitización, conexión a base de datos.
- `db/` — Scripts y utilidades de base de datos (`quiz_schema.sql`).

## Cómo empezar
1. Importa el script SQL desde `db/quiz_schema.sql` en tu gestor de base de datos MySQL.
2. Configura la conexión en `src/db.php` según tus credenciales.
3. Ejecuta el sistema desde `public/index.php` en tu servidor local (WAMP, XAMPP, etc.).
4. Personaliza el frontend en `public/` y los estilos en `assets/`.

## Notas y recomendaciones
- El sistema incluye roles: `admin`, `operative` y `player`.
- El panel de administración permite CRUD de usuarios, temas, preguntas y exportar reportes a Excel.
- Usa la clase `Sanitizer` y la interfaz `SanitizerInterface` para validar y sanitizar datos.
- El avance y puntos de los jugadores se visualizan en el dashboard.
- Los premios se asignan automáticamente según el avance y puntos.
- El sistema soporta preguntas de opción múltiple y verdadero/falso.
- Los avatares pueden ser gestionados por el usuario.
- El acceso a niveles avanzados debe estar restringido hasta aprobar el anterior (ver lógica en backend/frontend).
- Falta implementar la funcionalidad de QR para sets de preguntas (pendiente).

## Cosas por mejorar o agregar
- Implementar generación y escaneo de QR para sets de preguntas.
- Mejorar validaciones y mensajes de error en formularios.
- Agregar tests automatizados para la API.
- Documentar endpoints y lógica de negocio en `src/`.

---
**Desarrollado por EliabMena y colaboradores.**
