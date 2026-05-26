# MVP — Plataforma de Gestión de Collaboraciones entre Marcas y Creators

## Proyecto

Plataforma interna para gestionar:
- creators,
- campañas,
- outreach,
- colaboraciones,
- visitas,
- publicaciones,
- scoring,
- y relaciones entre marcas y creators.

El objetivo NO es construir un marketplace abierto.

El objetivo es construir un:

```txt
Sistema operativo de creator collaborations
```

La plataforma debe ayudar a:
- reclutar creators,
- construir una red privada,
- gestionar campañas,
- hacer outreach,
- coordinar visitas,
- registrar publicaciones,
- medir fiabilidad,
- y escalar operaciones.

---

# Arquitectura Conceptual

## Flujo principal

```txt
CreatorLead
    ↓
Creator
    ↓
Opportunity
    ↓
Collaboration
```

Todo ocurre dentro de:

```txt
Brand
 └── Campaign
```

---

# Entidades Principales

## Users

Usuarios internos:
- admin
- operador
- manager

Campos:
- id
- name
- email
- password
- role

---

## Cities

Ciudades normalizadas del sistema.

Campos:
- id
- name
- slug
- country
- timezone

Relaciones:
- Creator ↔ Cities
- Brand ↔ Cities

Pivot tables:
- creator_city
- brand_city

---

## Brands

Negocios o marcas.

Campos:
- id
- name
- slug
- industry
- description
- website_url
- status
- notes

Relaciones:
- hasMany Campaigns
- morphMany SocialAccounts
- belongsToMany Cities
- belongsToMany Tags

---

## Campaigns

Unidad operativa principal.

Representa:
- activaciones
- necesidades de contenido
- campañas mensuales
- creator visits

Campos:
- id
- brand_id
- name
- description
- objective
- status
- starts_at
- ends_at
- compensation_type
- requirements
- notes

Estados:
- draft
- active
- paused
- completed
- cancelled

Relaciones:
- belongsTo Brand
- hasMany Opportunities
- hasMany Collaborations
- belongsToMany Tags

---

## CreatorLeads

Creators descubiertos pero todavía no incorporados a la red.

NO tienen SocialAccounts todavía.

Campos:
- id
- platform
- handle
- profile_url
- name
- city_name
- country_name
- niche
- status
- contacted_at
- responded_at
- approved_at
- rejection_reason
- notes
- source

Estados:
- discovered
- contacted
- follow_up
- interested
- approved
- rejected
- ghosted
- archived

Relaciones:
- hasMany CreatorLeadMetrics

---

## CreatorLeadMetrics

Histórico de métricas de un lead.

Campos:
- id
- creator_lead_id
- followers_count
- following_count
- posts_count
- engagement_rate
- captured_at

---

## Creators

Miembros activos de la red.

Campos:
- id
- name
- username
- email
- phone
- bio
- ugc_only
- accepts_barter
- status
- rating
- joined_at
- last_active_at
- notes

Estados:
- active
- inactive
- paused
- blacklisted

Relaciones:
- morphMany SocialAccounts
- belongsToMany Cities
- belongsToMany Tags
- hasMany Opportunities
- hasMany Collaborations
- hasMany CreatorScores

---

## SocialAccounts

Redes sociales asociadas a Brands o Creators.

Relación polimórfica.

Campos:
- id
- accountable_type
- accountable_id
- platform
- handle
- url
- is_primary

Ejemplos:
- Instagram
- TikTok
- YouTube
- Twitter

Relaciones:
- morphTo accountable
- hasMany SocialAccountMetrics

---

## SocialAccountMetrics

Histórico de métricas de una red social.

Campos:
- id
- social_account_id
- followers_count
- following_count
- posts_count
- engagement_rate
- captured_at

Objetivo:
Guardar snapshots históricos de crecimiento.

---

## Opportunities

Representa:

"Hemos ofrecido una colaboración concreta a este creator"

NO significa colaboración aceptada.

Es el pipeline de outreach.

Campos:
- id
- campaign_id
- creator_id
- status
- channel
- source_account
- message_template
- first_contacted_at
- last_contacted_at
- responded_at
- follow_up_count
- rejection_reason
- notes
- assigned_to
- converted_to_collaboration_id

Estados:
- draft
- contacted
- follow_up
- interested
- accepted
- rejected
- ghosted
- expired
- cancelled

Relaciones:
- belongsTo Campaign
- belongsTo Creator
- hasMany OpportunityEvents
- hasOne Collaboration

---

## OpportunityEvents

Historial completo del outreach.

Permite registrar:
- mensajes
- follow-ups
- respuestas
- cambios de estado

Campos:
- id
- opportunity_id
- type
- old_status
- new_status
- message
- metadata
- created_by

Ejemplos:
- contacted
- follow_up_sent
- creator_replied
- accepted
- rejected
- ghosted

---

## Collaborations

Colaboración confirmada.

SOLO existe cuando el creator acepta una Opportunity.

Representa la ejecución real.

Campos:
- id
- campaign_id
- creator_id
- opportunity_id
- status
- scheduled_for
- accepted_at
- scheduled_at
- visited_at
- published_at
- completed_at
- content_type
- compensation_type
- compensation_value
- publication_url
- notes
- assigned_to

Estados:
- accepted
- scheduled
- rescheduled
- visited
- no_show
- published
- completed
- cancelled

Relaciones:
- belongsTo Campaign
- belongsTo Creator
- belongsTo Opportunity
- hasMany CollaborationEvents

---

## CollaborationEvents

Timeline operativo de la colaboración.

Campos:
- id
- collaboration_id
- type
- old_status
- new_status
- message
- metadata
- created_by

Ejemplos:
- scheduled
- rescheduled
- visit_completed
- no_show
- publication_received
- completed

---

## CreatorScores

Scoring interno del creator.

Campos:
- id
- creator_id
- visual_quality_score
- communication_score
- professionalism_score
- reliability_score
- speed_score
- global_score
- notes
- created_by

Objetivo:
Construir datos propietarios sobre creators fiables.

---

## Tags

Sistema flexible de clasificación.

Ejemplos:
- food
- wellness
- beauty
- fitness
- lifestyle
- luxury
- ugc
- barcelona

Campos:
- id
- name
- slug
- type

Relaciones:
- Creators ↔ Tags
- Brands ↔ Tags
- Campaigns ↔ Tags

---

# Relaciones Globales

```txt
Brand
 ├── Cities
 ├── Campaigns
 └── SocialAccounts
        └── SocialAccountMetrics

Campaign
 ├── Opportunities
 │      ├── Creator
 │      └── OpportunityEvents
 │
 └── Collaborations
        ├── Creator
        └── CollaborationEvents

CreatorLead
 └── CreatorLeadMetrics

Creator
 ├── Cities
 ├── Tags
 ├── SocialAccounts
 │      └── SocialAccountMetrics
 │
 ├── Opportunities
 ├── Collaborations
 └── CreatorScores
```

---

# Flujo Operativo Completo

## 1. Recruiting

```txt
CreatorLead discovered
→ contacted
→ approved
→ Creator created
```

## 2. Campaign Creation

```txt
Brand creates Campaign
```

## 3. Outreach

```txt
Campaign
→ Opportunities
→ Creators contacted
```

## 4. Conversion

```txt
Opportunity accepted
→ Collaboration created
```

## 5. Execution

```txt
scheduled
→ visited
→ published
→ completed
```

---

# Arquitectura Laravel Recomendada

## Models

```txt
User

City

Brand
Campaign

CreatorLead
CreatorLeadMetric

Creator

SocialAccount
SocialAccountMetric

Opportunity
OpportunityEvent

Collaboration
CollaborationEvent

CreatorScore

Tag
```

---

# Filosofía Técnica

La plataforma NO debe sentirse como:

```txt
CRM de influencers
```

Debe sentirse como:

```txt
Infraestructura operativa de creator collaborations
```

La ventaja competitiva estará en:
- historial
- fiabilidad
- rapidez operativa
- calidad de creators
- datos propietarios internos

---

# Reglas del Stack y Desarrollo

## Objetivo técnico

La plataforma debe construirse como una API robusta, documentada y mantenible.

El backend será una API en Laravel pensada para:
- operar internamente el negocio,
- gestionar datos de creators, marcas, campañas, oportunidades y colaboraciones,
- exponer endpoints claros,
- mantener documentación técnica viva,
- y permitir que más adelante exista frontend web, panel interno, apps o integraciones externas.

La prioridad NO es construir rápido sin estructura.

La prioridad es construir una base sólida, clara y escalable.

---

# Stack Backend

## Framework principal

```txt
Laravel
```

Laravel será usado como framework principal para la API.

La aplicación debe seguir una arquitectura limpia y modular, evitando meter toda la lógica en controllers.

---

## Base de datos

```txt
PostgreSQL
```

Motivos:
- relaciones complejas,
- buen soporte para datos estructurados,
- escalabilidad,
- integridad referencial,
- soporte para JSON cuando haga falta usar metadata.

---

## Autenticación

La API debe tener autenticación robusta desde el inicio.

Opciones recomendadas:

```txt
Laravel Sanctum
```

para una primera versión interna/API token.

Más adelante se podrá valorar:

```txt
OAuth2 / Laravel Passport
```

si hay integraciones externas o clientes de terceros.

---

# Reglas de Autenticación

## Obligatorio

Todos los endpoints privados deben requerir autenticación.

No debe existir ningún endpoint sensible sin middleware de auth.

Ejemplo:

```php
Route::middleware('auth:sanctum')->group(function () {
    // private routes
});
```

---

## Usuarios

El sistema tendrá usuarios internos.

Roles iniciales:

```txt
admin
manager
operator
viewer
```

---

## Permisos

Para MVP se puede empezar con roles simples.

Más adelante se podrá implementar un sistema más granular con policies o permissions.

Regla:

```txt
Nunca mezclar lógica de permisos directamente en controllers.
```

Usar:
- Policies
- Gates
- Middleware
- Form Requests

---

# Arquitectura de API

## Versionado

Toda la API debe ir versionada.

Ejemplo:

```txt
/api/v1/brands
/api/v1/campaigns
/api/v1/creators
```

No crear endpoints sin versión.

---

## Convención REST

Usar estructura REST siempre que tenga sentido.

Ejemplo:

```txt
GET    /api/v1/brands
POST   /api/v1/brands
GET    /api/v1/brands/{brand}
PUT    /api/v1/brands/{brand}
DELETE /api/v1/brands/{brand}
```

Para acciones de dominio, usar endpoints explícitos.

Ejemplo:

```txt
POST /api/v1/opportunities/{opportunity}/accept
POST /api/v1/opportunities/{opportunity}/reject
POST /api/v1/opportunities/{opportunity}/mark-ghosted

POST /api/v1/collaborations/{collaboration}/schedule
POST /api/v1/collaborations/{collaboration}/mark-visited
POST /api/v1/collaborations/{collaboration}/mark-no-show
POST /api/v1/collaborations/{collaboration}/mark-published
```

---

# Controllers

Los controllers deben ser finos.

No deben contener lógica de negocio compleja.

Responsabilidad del controller:
- recibir request,
- validar mediante FormRequest,
- llamar a Actions/Services,
- devolver Resource/Response.

No hacer:
- queries complejas directamente,
- cambios de estado con lógica larga,
- cálculos de métricas,
- creación de logs manual repetida.

---

# Actions / Services

Toda operación importante debe vivir en una Action o Service.

Ejemplos:

```txt
CreateOpportunityAction
AcceptOpportunityAction
RejectOpportunityAction
ConvertOpportunityToCollaborationAction
ScheduleCollaborationAction
MarkCollaborationVisitedAction
MarkCollaborationNoShowAction
CreateSocialAccountMetricSnapshotAction
```

Regla:

```txt
Cada cambio de estado importante debe pasar por una Action.
```

---

# Form Requests

Toda entrada de datos debe validarse con Form Requests.

Ejemplo:

```txt
StoreBrandRequest
UpdateBrandRequest
StoreCampaignRequest
StoreOpportunityRequest
AcceptOpportunityRequest
ScheduleCollaborationRequest
```

No validar directamente en el controller salvo casos mínimos.

---

# API Resources

Todas las respuestas deben usar API Resources.

Ejemplo:

```txt
BrandResource
CampaignResource
CreatorResource
OpportunityResource
CollaborationResource
SocialAccountResource
```

No devolver modelos Eloquent directamente.

---

# Estados y Transiciones

Las entidades con status deben tener transiciones controladas.

Entidades con status:
- Campaign
- CreatorLead
- Creator
- Opportunity
- Collaboration

Regla:

```txt
No actualizar status directamente desde cualquier endpoint.
```

Cada cambio relevante debe:
- validar transición permitida,
- actualizar timestamps si aplica,
- crear event/log,
- devolver respuesta consistente.

Ejemplo:

```txt
Opportunity contacted → accepted
Opportunity accepted → Collaboration created
Collaboration scheduled → visited
Collaboration scheduled → no_show
```

---

# Logs de Dominio

Los logs son obligatorios en procesos operativos.

Entidades con logs:
- OpportunityEvents
- CollaborationEvents

Cada cambio de estado debe generar un event.

Ejemplo:

```txt
Opportunity status changed contacted → accepted
Collaboration status changed scheduled → visited
```

Los logs deben guardar:
- estado anterior,
- estado nuevo,
- usuario que hizo la acción,
- mensaje opcional,
- metadata opcional,
- fecha.

---

# OpenAPI

La API debe tener especificación OpenAPI desde el principio.

Formato recomendado:

```txt
OpenAPI 3.1
```

Debe existir un archivo principal:

```txt
docs/openapi.yaml
```

o:

```txt
openapi.yaml
```

---

## Reglas OpenAPI

Cada endpoint implementado debe añadirse a la especificación OpenAPI en el mismo cambio.

No se considera terminada una feature si:
- no tiene endpoint documentado,
- no tiene request body documentado,
- no tiene responses documentadas,
- no tiene errores principales documentados.

---

## Cada endpoint debe documentar

- método HTTP,
- path,
- descripción,
- auth requerida,
- parámetros,
- request body,
- response 200/201,
- errores 400/401/403/404/422,
- schema usado.

---

## Schemas OpenAPI obligatorios

Cada entidad principal debe tener schema:

```txt
User
Brand
City
Campaign
CreatorLead
CreatorLeadMetric
Creator
SocialAccount
SocialAccountMetric
Opportunity
OpportunityEvent
Collaboration
CollaborationEvent
CreatorScore
Tag
```

---

# Documentación del Proyecto

Además de OpenAPI, debe existir documentación técnica interna.

Carpeta recomendada:

```txt
docs/
```

Archivos recomendados:

```txt
docs/architecture.md
docs/domain.md
docs/api.md
docs/openapi.yaml
docs/auth.md
docs/status-flows.md
docs/changelog.md
```

---

# Regla de documentación viva

Cada vez que se implemente una feature, hay que actualizar documentación.

Una feature NO está terminada si no se actualizan:

```txt
1. OpenAPI
2. docs/domain.md si cambia el modelo de dominio
3. docs/status-flows.md si cambia algún estado o transición
4. docs/changelog.md con resumen del cambio
```

---

# Changelog

Debe mantenerse un changelog técnico.

Archivo:

```txt
docs/changelog.md
```

Formato:

```md
## YYYY-MM-DD

### Added
- Nuevo endpoint para crear campaigns.

### Changed
- Cambiada transición de opportunity rejected.

### Fixed
- Corregida validación de social account handle.
```

---

# Testing

La API debe tener tests desde el inicio.

Tipos de tests:
- Feature tests para endpoints
- Unit tests para Actions/Services
- Tests de validación
- Tests de permisos
- Tests de transiciones de estado

Regla:

```txt
Toda Action crítica debe tener test.
```

Ejemplos:
- crear opportunity
- aceptar opportunity
- convertir opportunity en collaboration
- reagendar collaboration
- marcar no show
- marcar publicación

---

# Respuestas de API

Todas las respuestas deben ser consistentes.

## Éxito

```json
{
  "data": {}
}
```

## Listados

```json
{
  "data": [],
  "meta": {},
  "links": {}
}
```

## Error

```json
{
  "message": "Validation error",
  "errors": {}
}
```

---

# Paginación, filtros y orden

Todos los listados principales deben soportar paginación.

Entidades:
- Brands
- Campaigns
- CreatorLeads
- Creators
- Opportunities
- Collaborations

Filtros recomendados:
- status
- city
- tag
- campaign
- brand
- creator
- date range

Orden recomendado:
- created_at
- updated_at
- scheduled_for
- status

---

# Soft Deletes

Usar soft deletes en entidades principales.

Entidades recomendadas:
- Brands
- Campaigns
- CreatorLeads
- Creators
- SocialAccounts
- Opportunities
- Collaborations
- Tags

No borrar información operativa sensible si puede afectar historial.

---

# Auditoría

Toda entidad operativa importante debe tener trazabilidad.

Campos recomendados cuando aplique:
- created_by
- updated_by
- assigned_to

Especialmente en:
- Campaigns
- Opportunities
- Collaborations
- Events
- Scores

---

# Metadata

Usar campos JSON `metadata` solo para información flexible o externa.

No usar metadata como sustituto de columnas importantes.

Correcto:
- guardar payload de una API externa,
- guardar datos de contexto de un evento,
- guardar detalles variables.

Incorrecto:
- guardar status dentro de metadata,
- guardar creator_id dentro de metadata,
- guardar scheduled_for dentro de metadata.

---

# Jobs y Automatizaciones

Cualquier tarea periódica debe ir en Jobs/Commands.

Ejemplos futuros:
- actualizar métricas de social accounts,
- marcar opportunities como ghosted,
- enviar recordatorios,
- recalcular scores,
- generar snapshots.

No ejecutar lógica pesada en requests HTTP.

---

# Colas

Preparar el sistema para usar queues.

Recomendado:
- database queue en local/MVP
- Redis queue en producción cuando haya volumen

---

# Seguridad

Reglas básicas:
- no exponer IDs sensibles innecesarios,
- validar todos los inputs,
- usar rate limiting,
- no devolver stack traces en producción,
- proteger endpoints privados,
- no guardar tokens externos en texto plano,
- usar variables de entorno.

---

# Rate Limiting

Aplicar rate limiting a:
- login
- endpoints de escritura
- endpoints públicos si existen en el futuro

---

# CORS

Configurar CORS solo para dominios permitidos.

No usar wildcard en producción.

---

# Factories y Seeders

Cada modelo principal debe tener Factory.

Seeders mínimos:
- usuario admin
- ciudades iniciales
- tags iniciales
- marcas demo opcionales
- creators demo opcionales

---

# Convenciones de nombres

Usar nombres claros de dominio.

Preferir:
- Creator
- Brand
- Campaign
- Opportunity
- Collaboration

Evitar:
- Influencer
- Deal
- Lead genérico
- UserProfile ambiguo

El proyecto debe mantener la terminología de creators, red privada y colaboraciones.

---

# Criterio de Feature Terminada

Una feature solo se considera terminada si incluye:

```txt
1. Migration
2. Model
3. Relationships
4. Factory
5. Request validation
6. Controller endpoint
7. Action/Service si hay lógica de negocio
8. API Resource
9. Tests
10. OpenAPI actualizado
11. Documentación actualizada
12. Changelog actualizado
```

---

# Principio Final

No construir una API rápida y desordenada.

Construir una API sólida que permita convertir el caos operativo de colaboraciones con creators en un sistema profesional, documentado y escalable.

