# dashboard

dev dashboard

# Developer Multi-Tool Dashboard

Lokale Laravel-Web-App für Entwickler-Workflows mit:

- Notizen (Markdown, Suche, Filter, Favoriten, Projekt-Zuordnung)
- Snippets (CRUD, Sprache, Tags, Copy-Button)
- Time Tracking (Start/Stop Timer, manuelle Einträge, Tages-/Wochenübersicht, CSV-Export)

Technik:

- PHP 8.2+
- Laravel 11 (Blade + Alpine)
- TailwindCSS + Vite
- SQLite (Default) + optional PostgreSQL
- Auth via Laravel Breeze (lokaler Login)

## DDEV Setup

Projekt liegt in `dev-dashboard/`.

1. DDEV starten

```bash
cd dev-dashboard
ddev start
```

2. Dependencies installieren

```bash
ddev composer install
ddev npm install
```

3. SQLite-Datei sicherstellen + Migrationen/Seed

```bash
ddev exec "test -f database/database.sqlite || touch database/database.sqlite"
ddev artisan migrate:fresh --seed
```

4. Assets bauen

```bash
ddev npm run build
```

Für aktive UI-Entwicklung stattdessen:

```bash
ddev npm run dev
```

Wenn bereits ein alter Vite-Hot-Link existiert (z. B. `http://127.0.0.1:5173` in `public/hot`), dann:

```bash
ddev restart
ddev npm run dev
```

Die Dev-URL muss dann auf `https://dev-dashboard.ddev.site:5173` zeigen.

5. App öffnen

- URL: `https://dev-dashboard.ddev.site`
- Demo-User (Seeder):
    - E-Mail: `dev@example.test`
    - Passwort: `password`

## Optional PostgreSQL statt SQLite

In `.env` ändern:

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=db
DB_USERNAME=db
DB_PASSWORD=db
```

Dann:

```bash
ddev artisan migrate:fresh --seed
```

## Tests

```bash
ddev artisan test
```

Enthaltene Feature-Tests:

- Timer Start/Stop + „nur 1 laufender Timer pro User"
- Autorisierung (fremde Notiz nicht lesbar)
- CSV-Export inkl. Raw + gerundeter Dauer

## Wichtige Funktionen

### Notizen

- CRUD
- Markdown Editor + Live-Preview
- Suche nach Titel/Inhalt
- Filter: Projekt, Tag, Favorit
- Mehrfach-Zuordnung zu Projekten

### Snippets

- CRUD
- Sprache, Tags, Projektbezug
- Copy-to-Clipboard
- Vorbereitung „aus Note als Snippet“ (TODO-Hinweis im UI)

### Time Tracking

- Timer Start/Stop
- Serverseitige Enforce-Regel: max. 1 laufender Timer pro User
- Manuelle Einträge (Start/Ende oder Start + Dauer)
- Tages-/Wochen-Summen pro Projekt
- Settings: Rundung, Wochenstart, Zeitzone
- CSV Export mit Filtern (Zeitraum, Projekt, Tags)

## Routen-Überblick

- Dashboard: `/dashboard`
- Projekte: `/projects`
- Notizen: `/notes`
- Snippets: `/snippets`
- Time Tracking: `/time-entries`
- Timer Endpunkte: `/time/start`, `/time/stop`
- CSV Export: `/time/export/csv`
- Settings: `/settings`
- Global Search API: `/search?q=...`

## Roadmap (vorbereitet)

- Tasks / Mini-Kanban
- Daily Log
- Bookmarks

Routing und DB-Tabellen dafür sind bereits angelegt (`tasks`, `daily_logs`, `bookmarks`).
