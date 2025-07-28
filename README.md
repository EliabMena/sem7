
# Sistema de Trivias (PHP + Bootstrap)

Proyecto completo para gestión de trivias, usuarios y administración, usando PHP, Bootstrap y MySQL.

## Estructura del Proyecto

- `public/` — Archivos públicos, vistas, dashboards, formularios, CRUD, reportes y punto de entrada (`index.php`).
- `assets/` — Imágenes, CSS y JS personalizados.
- `src/` — Lógica de la aplicación, clases, API, sanitización, conexión a base de datos.
- `db/` — Scripts y utilidades de base de datos (`quiz_schema.sql`).


## Pasos para ejecutar el sistema en un entorno local (WAMP)

1. **Instala WAMP Server:** Descarga e instala WAMP desde https://www.wampserver.com/
2. **Instala Git:** Descarga e instala Git desde https://git-scm.com/
3. **Clona el repositorio del proyecto dentro de la carpeta `www` de WAMP:**
   ```bash
   cd C:\wamp64\www
   git clone https://github.com/EliabMena/sem7.git
   ```
4. **Inicia WAMP** y asegúrate que Apache y MySQL estén activos (icono verde).
5. **Crea la base de datos:**
   - Accede a PhpMyAdmin en http://localhost/phpmyadmin
   - Crea la base de datos llamada `dbQuiz`
   - Importa el archivo SQL `db/quiz_schema.sql` y luego (opcionalmente) `db/db_data.sql` desde el proyecto clonado.
6. **Configura la conexión a la base de datos en:**
   - Edita el archivo `src/db.php` y ajusta los siguientes valores según tu entorno:
     ```php
     $host = 'localhost';
     $dbname = 'dbQuiz';
     $user = 'root';
     $pass = '';
     ```
7. **Accede al sistema en el navegador:**
   - http://localhost/sem7/public/
8. **Credenciales de ejemplo:**
   - Administrador: admin@example.com / 123456
   - Jugador: player@example.com / 123456

> **Nota:** Puedes crear más usuarios desde el panel de administración.

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
