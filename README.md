# 🧱 Proyecto API RESTful – Laravel (Usuarios & Posts)

## 📋 Descripción General

Este proyecto implementa una **API RESTful** desarrollada en **Laravel**, siguiendo los principios de la **Arquitectura Limpia (Clean Architecture)**.  
La API permite el manejo de usuarios y posts, con autenticación mediante **JWT (JSON Web Tokens)**.

Incluye:
- Registro e inicio de sesión de usuarios.
- CRUD completo de usuarios y posts.
- Documentación automática con **Swagger (L5-Swagger)**.
- Base de datos **SQLite** por defecto (para facilidad de integración).
- Estructura modular y desacoplada (Domain / Application / Infrastructure / Presentation).

---

## 🚀 Cómo levantar el proyecto por primera vez

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tuusuario/laravel-clean-api.git
   cd laravel-clean-api
   ```

2. **Inicializar el proyecto**
    ```bash
    composer run-script app:init
   ```

3. **Iniciar el servidor**
   ```bash
    composer run-script app:dev
   ```
   La API estará disponible en:  
   👉 http://localhost:8000/api/documentation

4. **Verificar todo está correcto**
   ```bash
   php artisan tinker
   >>> \App\Models\User::count();
   >>> \App\Models\Post::count();
   >>> \App\Models\User::withCount('posts')->orderByDesc('posts_count')->take(3)->get(['id','email']);
   ```
5. **Ayuda en caso de error**
    Verificar dependencias con
    ```bash
    composer run-script app:doctor
   ```

---

## 🧩 Estructura del Proyecto (Arquitectura Limpia)

```
app/
  Application/
  Domain/
  Infrastructure/
    Persistence/
    Providers/
  Presentation/
    Docs/
    Http/
      Controllers/Api/
      Middleware/
      Requests/
      Resources/
    Policies/
  Models/
config/
bootstrap/
  app.php
routes/
  api.php
database/
  migrations/
  seeders/
  factories/
```

### 🧠 Justificación de la arquitectura
- **Clean Architecture** fue elegida para lograr un **alto desac acoplamiento y testabilidad**.  
- Permite **migrar tecnologías fácilmente** (por ejemplo, reemplazar Eloquent o JWT) sin alterar las reglas de negocio.
- La **lógica de negocio** vive en `Domain` y `Application`, separada de controladores o bases de datos.

La arquitectura **MVC** (Modelo, Vista, Controlador) hubiera sido mas que suficiente para los requerimientos funcionales, pero 
se eligio **Clean Architecture** para mostrar buenas prácticasen el prototipo de un software mantenible, testeable y desacoplado

---

## 🗄️ Base de Datos (SQLite por defecto)

Se utiliza **SQLite** por su **simplicidad y portabilidad**, sin requerir instalación externa.

Ventajas:
- No necesita servidor de base de datos.
- Fácil de migrar a **MySQL, PostgreSQL o SQL Server**.

---

## 🔐 Autenticación JWT

- Se utiliza **tymon/jwt-auth**.  
- En POST /login no se devuelve el token en el body. El servidor setea una cookie HttpOnly con el JWT (p. ej. access_token).
- Las requests autenticadas no necesitan header Authorization`

¿Por qué HttpOnly?

Mitiga XSS: el token no es accesible desde JavaScript.

Desacopla el cliente: no hay que gestionar headers ni almacenar tokens en localStorage/sessionStorage.

---



## 📘 Documentación Swagger

Se usa **L5-Swagger**:
```bash
composer require "darkaonline/l5-swagger"
php artisan l5-swagger:generate
```
Abrir en: [http://localhost:8000/api/documentation]

---

## 🧪 Depuración (Xdebug con SAIL)

En `.env`:
```
SAIL_XDEBUG_MODE=develop,debug,coverage
SAIL_XDEBUG_CONFIG=client_host=host.docker.internal
```

---

## 🧾 Justificación de mantener `app/Models` en lugar de `Infrastructure`

Se decidió mantenerlos en `app/Models` por compatibilidad con paquetes (Auth, Nova, Factories, etc.) y tooling Laravel. Pero conceptualmente iria dentro de la capa de `Infrastructure`

---
## 🧹 Estilo de código (PSR-12) — Pint + PHPCS

Para mantener un estilo PSR-12 consistente, el proyecto usa Laravel Pint como formateador automático y PHPCS como linter.

## ⚡ Stack tecnológico

Laravel 11, Eloquent ORM, JWT Auth, L5-Swagger, SQLite, Xdebug.

---

## 👤 Autor

**Ezequiel Mensur**  
Desarrollador Fullstack 
📧 contacto: [ezequielmensur@gmail.com]
