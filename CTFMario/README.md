# 🕵️‍♂️ CTF Hint System — Sistema de Pistas y Entrega de Bandera

Este proyecto permite gestionar **retos CTF (Capture The Flag)** educativos desde un servidor PHP de forma sencilla, sin necesidad de base de datos.  
Incluye un sistema de pistas configurables, entrega de bandera final, registro automático en JSON y un panel de administración seguro.

---

## 🚀 Características principales

- ✅ Configurable mediante un único archivo `hints.json`
- 📎 Soporta documentación adjunta (PDFs, imágenes u otros recursos)
- 🧩 Pistas con texto o archivos descargables
- 📨 Registro automático de todas las solicitudes y banderas en `logs.json`
- 🔐 Panel **Admin** protegido por autenticación básica
- 💾 Sin base de datos (funciona solo con ficheros JSON)
- 🎨 Interfaz moderna con **Bootstrap 5**
- 💻 Compatible con **PHP 7.4+ y 8.x**

---

## 📁 Estructura del proyecto

```
ctf_hint_final/
├── admin.php           # Panel de administración protegido
├── config.php          # Configuración general del sistema
├── hints.json          # Archivo principal de configuración del reto y pistas
├── index.php           # Página de inicio del reto (vista del alumno)
├── process.php         # Lógica de peticiones y almacenamiento de logs
├── logs.json           # Registro automático (se crea al primer uso)
└── uploads/            # Carpeta de archivos y recursos del reto
```

---

## ⚙️ Instalación

1. Sube todos los archivos al directorio de tu servidor web, por ejemplo:
   ```bash
   /var/www/html/ctf_hint_final/
   ```

2. Crea la carpeta `/uploads` (si no existe) y sube allí los documentos PDF u otros recursos del reto.

3. Asegúrate de que PHP tenga permiso de escritura para crear y modificar `logs.json`:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/ctf_hint_final
   sudo chmod -R 775 /var/www/html/ctf_hint_final
   ```

4. Edita `config.php` para definir usuario/contraseña del panel admin y rutas personalizadas.

5. Edita `hints.json` para definir el reto, sus objetivos, recursos y pistas.

---

## 🔐 Acceso al panel admin

1. Abre tu navegador y visita:
   ```
   http://tusitio.com/ctf_hint_final/admin.php
   ```

2. Introduce las credenciales configuradas en `config.php`.

El panel mostrará todas las solicitudes de pistas y entregas de bandera registradas.

---

## 📜 Ejemplo básico de `hints.json`

```json
{
  "challenge": {
    "enabled": true,
    "title": "Reto: El Archivo Oculto",
    "description": "Analiza los archivos y descubre la información sensible oculta.",
    "objectives": [
      "Aprender a analizar metadatos.",
      "Usar herramientas forenses básicas.",
      "Entregar la FLAG final correctamente."
    ],
    "resources": [
      {
        "name": "Dossier del caso.pdf",
        "description": "Documento con el contexto del incidente.",
        "file": "dossier-caso.pdf"
      }
    ]
  },
  "hints": [
    {
      "id": 1,
      "type": "text",
      "title": "Pista 1",
      "content": "Revisa los metadatos del archivo 'misterio.jpg'."
    },
    {
      "id": 2,
      "type": "file",
      "title": "Pista 2",
      "content": "documento-clave.pdf",
      "description": "Descarga y analiza este documento con herramientas forenses."
    }
  ]
}
```

---

## 🧠 Créditos y licencia

Desarrollado por **Carlos Basulto Pardo** para entornos educativos de Formación Profesional en Ciberseguridad.  
Uso libre con fines docentes, siempre citando la fuente.  
Licencia: **MIT**.

---

## 💬 Contacto

Si deseas contribuir o reutilizar este sistema en tus retos CTF, puedes clonar el repositorio o crear un fork desde GitHub.

**Autor:** [Carlos Basulto Pardo](https://github.com/CarlosBasulto)  
**Email:** contacto@artefactoforense.com
