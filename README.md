# 📦 Sistema ABM de Productos MercadoLibre

Sistema completo de gestión (Alta, Baja, Modificación) desarrollado en PHP nativo con Bootstrap 5.

**Trabajo Práctico 3** - Desarrollo Web - Universidad Siglo 21  

---

## 📋 Descripción

Aplicación web fullstack que permite gestionar un catálogo de productos con operaciones CRUD completas. Desarrollada con PHP nativo, PDO y Bootstrap 5.

### 💼 Contexto del proyecto

El sistema fue diseñado para **MasSymple**, un emprendimiento familiar que gestiona más de 200 publicaciones activas en MercadoLibre con múltiples variaciones (colores, tamaños, modelos). Necesitaban centralizar la información de productos para facilitar el seguimiento de publicaciones y el control de variaciones de stock.

---

## 📝 Consigna del Trabajo Práctico

**Objetivo:** Desarrollar un sistema ABM (Altas, Bajas, Modificaciones) completo en PHP utilizando PDO para la conexión a base de datos.

**Requerimientos:**

- Elegir una entidad para trabajar (Productos, Personas, Ventas, etc.)
- Crear una base de datos con una tabla de **mínimo 7 campos** incluyendo clave primaria autoincremental
- Utilizar **PDO (PHP Data Objects)** para la conexión a la base de datos
- **NO utilizar frameworks**, solo extensiones y métodos nativos de PHP
- Utilizar **sentencias preparadas** para todas las operaciones
- Crear formularios HTML utilizando **Bootstrap** (vía CDN)
- Implementar las 4 operaciones CRUD:
  - **Crear**: Insertar nuevos registros
  - **Leer**: Listar y visualizar registros
  - **Actualizar**: Modificar registros existentes
  - **Eliminar**: Borrar registros
- Generar un **diccionario de datos** de la tabla creada
- Incluir entre 5 y 10 registros de prueba en la base de datos

---

## ✨ Funcionalidades Implementadas

### 🔍 Listado de Productos

- Visualización en tabla HTML responsive
- Búsqueda por título con filtro dinámico
- Ordenamiento descendente por ID
- Badges de estado de publicación
- Acciones rápidas (ver/editar/eliminar)

### ➕ Alta de Productos

- Formulario con validación de campos
- Campos: título, descripción, precio, stock, categoría, estado
- Feedback visual con alertas de éxito/error

### 👁️ Visualización Detallada

- Vista completa de información del producto
- Navegación directa a edición o eliminación
- Manejo de errores para productos inexistentes

### ✏️ Modificación de Productos

- Formulario precargado con datos actuales
- Actualización mediante sentencias preparadas
- Validación de ID en URL

### 🗑️ Eliminación de Productos

- Confirmación mediante JavaScript alert
- Eliminación segura con PDO
- Redirección automática tras eliminar

---

## 🛠️ Tecnologías Utilizadas

| Tecnología        | Versión | Uso                                |
| ----------------- | ------- | ---------------------------------- |
| **PHP**           | 7.4+    | Backend y lógica de negocio        |
| **PDO**           | Nativo  | Conexión segura a base de datos    |
| **MySQL/MariaDB** | 10.x    | Sistema gestor de base de datos    |
| **Bootstrap**     | 5.1     | Framework CSS responsivo (vía CDN) |
| **HTML5**         | -       | Estructura semántica               |
| **CSS3**          | -       | Estilos personalizados             |

---

## 📁 Estructura del Proyecto

```
sistema-abm-productos/
├── config.php                      # Configuración de conexión a BD
├── instalar.php                    # Script de instalación automática
├── index.php                       # Listado principal de productos
├── crear.php                       # Formulario de alta
├── ver.php                         # Vista detallada del producto
├── editar.php                      # Formulario de modificación
├── borrar.php                      # Eliminación de productos
└── sql/
    └── productos_mercadolibre.sql  # Script de BD con datos de prueba
```

---

## 💾 Base de Datos

### Diccionario de Datos

| Campo           | Tipo    | Longitud | Restricciones                         | Descripción                                          |
| --------------- | ------- | -------- | ------------------------------------- | ---------------------------------------------------- |
| **id_producto** | INT     | 11       | PRIMARY KEY, AUTO_INCREMENT, UNSIGNED | Identificador único del producto                     |
| **titulo**      | VARCHAR | 150      | NOT NULL                              | Título del producto tal como aparece en MercadoLibre |
| **descripcion** | TEXT    | -        | NOT NULL                              | Descripción detallada del producto                   |
| **precio**      | DECIMAL | 10,2     | NOT NULL                              | Precio de venta del producto en pesos                |
| **stock**       | INT     | 11       | NOT NULL                              | Cantidad de unidades disponibles                     |
| **categoria**   | VARCHAR | 100      | NOT NULL                              | Categoría del producto                               |
| **publicado**   | BOOLEAN | 1        | DEFAULT 1                             | Estado de publicación (1=activo, 0=inactivo)         |

---

## 🚀 Inicio Rápido

### Requisitos previos

- XAMPP instalado (Apache + PHP + MySQL)
- Navegador web moderno

### Instalación

1. **Clonar/copiar** el proyecto en la carpeta `htdocs` de XAMPP

2. **Ejecutar el script de instalación automática:**

   ```
   http://localhost/sistema-abm-productos/instalar.php
   ```

   Este script creará automáticamente:

   - La base de datos `productos_mercadolibre`
   - La tabla `productos` con su estructura completa
   - Los registros de prueba (5-10 productos)

3. **Acceder a la aplicación:**
   ```
   http://localhost/sistema-abm-productos/
   ```

---

## 📸 Capturas de Pantalla

### Listado Principal

![Listado de Productos](abm_productos_php/screenshots/listado.jpg)
_Vista principal con tabla de productos, búsqueda y opciones de gestión_

### Formulario de Creación

![Crear Producto](abm_productos_php/screenshots/crear.jpg)
_Formulario para agregar nuevos productos con validación_

### Vista Detallada

![Detalle del Producto](abm_productos_php/screenshots/detalle.jpg)
_Información completa de un producto con opciones de edición/eliminación_

### Formulario de Edición

![Editar Producto](abm_productos_php/screenshots/editar.jpg)
_Actualización de datos con formulario precargado_

---



