# 🚀 Inicio Rápido - Producto 4

## Pasos para poner en marcha el proyecto en 5 minutos

### 1️⃣ Verificar Requisitos

✅ Docker Desktop instalado y corriendo  
✅ Navegador web  
✅ Editor de código (VS Code recomendado)

### 2️⃣ Iniciar el Entorno

Abrir PowerShell en la carpeta del proyecto:

```powershell
cd "C:\Users\sergio.gomezg\Desktop\UOC DAW\Back End PHP\Producto 4\docker"
docker-compose up -d
```

⏱️ Esperar 30 segundos...

### 3️⃣ Instalar WordPress

1. Abrir navegador: **http://localhost:8000**
2. Seleccionar idioma: **Español**
3. Completar formulario de instalación:
   - Título: `Transfers Excellence`
   - Usuario: `admin`
   - Contraseña: (la que prefieras)
   - Email: tu email
4. Clic en **Instalar WordPress**

### 4️⃣ Instalar Plugins

En el panel de WordPress:

1. Ir a **Plugins > Añadir nuevo**
2. Buscar e instalar: **Create Block Theme**
3. Buscar e instalar: **Genesis Custom Blocks**
4. Activar ambos plugins
5. Ir a **Plugins > Plugins instalados**
6. Activar: **Bloque Transfers**

### 5️⃣ Activar Tema

1. Ir a **Apariencia > Temas**
2. Activar: **Mi Tema Transfers**

### 6️⃣ Crear Páginas

**Página 1: Inicio**
- Título: `Inicio`
- Contenido: Bienvenida a la empresa
- **Publicar**

**Página 2: Nuestros servicios**
- Título: `Nuestros servicios`
- Añadir bloque: **Estadísticas de Transfers**
- Configurar URL del JSON (ver abajo)
- **Publicar**

**Página 3: Nuestra flota**
- Título: `Nuestra flota`
- Plantilla: **Página Flota**
- Contenido: Información de vehículos
- **Publicar**

**Página 4: Blog**
- Título: `Blog`
- Sin contenido
- **Publicar**

### 7️⃣ Configurar Inicio y Blog

1. Ir a **Ajustes > Lectura**
2. Seleccionar: **Una página estática**
3. Página de inicio: `Inicio`
4. Página de entradas: `Blog`
5. **Guardar cambios**

### 8️⃣ Crear 3 Noticias

**Noticia 1:**
- Ir a **Entradas > Añadir nueva**
- Título: `Nuevo servicio de transfer al aeropuerto`
- Contenido: Descripción del servicio
- **Publicar**

**Noticia 2:**
- Título: `Ampliamos nuestra flota con vehículos eléctricos`
- Contenido: Información sobre nuevos vehículos
- **Publicar**

**Noticia 3:**
- Título: `Ofertas especiales para grupos`
- Contenido: Detalles de las ofertas
- **Publicar**

### 9️⃣ Configurar el Menú

1. Ir a **Apariencia > Menús**
2. Crear menú: `Menu Principal`
3. Añadir páginas: Inicio, Nuestros servicios, Nuestra flota, Blog
4. Guardar menú

### 🎉 ¡Listo!

Visitar: **http://localhost:8000**

---

## 🔧 Configurar el Bloque de Estadísticas

### Opción A: Usar JSON de Prueba (Local)

1. Crear archivo `test.json` en alguna carpeta
2. Copiar contenido de `docs/ejemplo-transfers.json`
3. Servir con HTTP:

```powershell
# Con Python
python -m http.server 8001

# Con PHP
php -S localhost:8001
```

4. En el bloque, usar URL: `http://localhost:8001/test.json`

### Opción B: Usar JSON del Producto 3

Si ya tienes el Producto 3 corriendo:

URL: `http://localhost:PUERTO/api/transfers.json`

### Opción C: Usar JSON Online (Testing)

Crear un archivo JSON en:
- [JSONBin.io](https://jsonbin.io)
- [Pastebin](https://pastebin.com)
- Tu propio servidor

---

## 📊 Verificación

Comprobar que todo funciona:

- ✅ http://localhost:8000 → Página de inicio se ve bien
- ✅ http://localhost:8000/nuestros-servicios → Bloque muestra estadísticas
- ✅ http://localhost:8000/nuestra-flota → Plantilla personalizada
- ✅ http://localhost:8000/blog → 3 noticias visibles
- ✅ Menú de navegación funciona
- ✅ Responsive (probar en DevTools)

---

## 🆘 Solución Rápida de Problemas

### WordPress no carga
```powershell
docker-compose restart wordpress
```

### Error de base de datos
```powershell
# Esperar 30 segundos más, o:
docker-compose restart db
```

### Tema no aparece
```powershell
docker-compose restart wordpress
# Luego refrescar navegador con Ctrl+Shift+R
```

### Bloque no muestra datos
1. Verificar URL del JSON en navegador
2. Limpiar caché del navegador (Ctrl+Shift+R)
3. Verificar en DevTools > Console si hay errores

---

## 📚 Documentación Completa

Para información detallada, consultar:

- **README.md** - Visión general del proyecto
- **docs/INSTALACION.md** - Guía paso a paso completa
- **docs/CONFIGURACION.md** - Configuración avanzada
- **docs/PASOS_REALIZADOS.md** - Proceso de desarrollo
- **docs/COMANDOS_UTILES.md** - Comandos Docker y WordPress
- **docs/GUION_VIDEO.md** - Guión para vídeo explicativo

---

## 🎥 Siguiente Paso: Vídeo Explicativo

Consultar **docs/GUION_VIDEO.md** para instrucciones detalladas sobre cómo grabar el vídeo demostrativo.

---

## 📞 URLs de Acceso

| Servicio | URL | Credenciales |
|----------|-----|--------------|
| **WordPress** | http://localhost:8000 | admin / (tu contraseña) |
| **WP Admin** | http://localhost:8000/wp-admin | admin / (tu contraseña) |
| **phpMyAdmin** | http://localhost:8080 | root / password_seguro |

---

## ⏰ Tiempo Total Estimado

- ⏱️ Instalación Docker: 1 minuto
- ⏱️ Instalación WordPress: 2 minutos
- ⏱️ Configuración: 5-10 minutos
- **Total: ~15 minutos**

---

## ✅ Checklist Final

- [ ] Docker corriendo
- [ ] WordPress instalado
- [ ] Plugins instalados y activos
- [ ] Tema activado
- [ ] 4 páginas creadas
- [ ] 3 noticias publicadas
- [ ] Menú configurado
- [ ] Bloque de estadísticas funcionando
- [ ] Sitio responsive

---

**💡 Consejo:** Si algo falla, siempre puedes reiniciar desde cero con:

```powershell
docker-compose down -v
docker-compose up -d
```

Esto eliminará todo y empezarás con una instalación limpia de WordPress.

---

**¡Éxito con tu proyecto!** 🚀
