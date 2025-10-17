## Arquitectura (Clean Architecture)

```
[ Presentation ]  →  [ Application ]  →  [ Domain ]  ←  [ Infrastructure ]
  HTTP (Controllers,       Use Cases         Entidades +       Eloquent, Mappers,
  Requests, Policies,      + DTOs            Puertos            Repos concretos
  Resources)
```

### Reglas de dependencia (muy importantes)

- **Presentation** → puede depender de **Application** y **Domain** (interfaces/entidades).
- **Application** → puede depender de **Domain**. **No** framework ni Eloquent.
- **Domain** → no depende de nada del framework/ORM. Solo PHP.
- **Infrastructure** → depende de Eloquent/Laravel **y** de **Domain** (para implementar puertos y mapear entidades).

### Qué va en cada capa

**Domain**
- Entidades ricas (`App\\Domain\\*\\Entities\\*`).
- Puertos (interfaces de repos) en `App\\Domain\\*\\Repositories\\*`.
- (Opcional) DTOs de dominio para inputs (`*CreateData`, `*UpdateData`).

**Application**
- Use cases en `App\\Application\\*\\UseCases\\*`.
- DTOs de **entrada** (`*Input`, `*Query`) con `fromArray(...)`.
- DTOs de **salida** (`*Output`).

**Infrastructure**
- Repositorios Eloquent que **implementan** los puertos del dominio.
- Mappers Eloquent ↔ Domain en `App\\Infrastructure\\Persistence\\*\\Mappers\\*`.
- Modelos Eloquent.

**Presentation**
- Controllers (adaptan HTTP ↔ DTOs de Application).
- Requests (validación), **Policies** (autorización), **Resources** (serialización JSON).


---

## Flujo (ejemplo GET `/posts`)
1) Controller: `ListPostsQuery::fromArray($request->query())`  
2) Use case `ListPosts($query)` → depende del **PostRepository** (puerto)  
3) IoC resuelve `PostRepositoryEloquent` → Eloquent, filtros, paginado, **mapea** a entidades `Domain\\Post`  
4) Use case: **mapea** `Domain\\Post[]` → `PostOutput[]`, arma `PagedResult`  
5) Resource: serializa `{ data, meta }` conforme a OpenAPI

---

## Desacoples principales
- **Application no conoce Eloquent ni HTTP**: usa puertos y DTOs → fácil test unitario.
- **Domain no conoce framework**: entidades + interfaces puras → portable.
- **Infrastructure encapsula ORM y DB**: cambios de persistencia no afectan Application/Domain.
- **Policies sin Eloquent en Controllers**: autorización por **ID** mapeando Policy a entidad de dominio.

---

## Beneficios del diseño
- **Testabilidad**: Use cases y Domain testeables sin DB/HTTP.
- **Evolución**: cambiar ORM/DB, o el shape JSON, sin tocar reglas de negocio.
- **Seguridad**: `sort` y filtros validados; policies desacopladas de Eloquent.

---

## Cómo agregar una nueva funcionalidad (paso a paso)
1. **Domain**: entidad/VO si hace falta; declara/expande **puerto** del repo; DTOs de dominio para inputs.
2. **Infrastructure**: implementa el puerto con Eloquent; agrega **mapper**; valida sort/paginado.
3. **Application**: crea **Use Case** + **DTOs** de entrada/salida (y `PagedResult` si es listado).
4. **Presentation**: controller (adapter HTTP→DTO), form request (validación), resource (serialización), policy si aplica.
5. **Bindings** en provider (puerto→impl).
6. **Docs**: OpenAPI/README si aplica.

---

## Postman
- Variables de entorno:
  - `baseUrl`: `http://localhost:8000/api/v1`
  - `userId` para rutas `/users/{id}`.
- Cookie `httpOnly`: manejada por Postman (no header manual).
---

## Base de datos, migraciones y seeds
- Migraciones en `database/migrations`.
- Seeds mínimos en `database/seeders`.
- **Soft deletes** coherentes con policies (`withTrashed/onlyTrashed`).

---

## Seguridad
- Validación en Form Requests.
- Hash en Infra o cast `hashed`.
- CORS/rate limiting si aplica.

---
