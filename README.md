# LubiX Framework + Deca (Unified Dev Server)

LubiX is a high-performance PHP framework designed for developers who want the speed of modern React reactivity with the reliability of a solid PHP backend, without the complexity of managing multiple development servers and ports.

![LubiX Logo](/public/lubixbanner.png)

## 🌟 Key Features

- **Unified Developer Experience**: Run your entire stack on a single port (`3000`) with one command: `deca lubix serve`.
- **Zero-Build React Bundler**: A built-in esbuild-powered bundler that processes your JSX and React components on-the-fly during development. No `npm install` or complex Vite setup required for local dev.
- **FastCGI Backend Integration**: Direct integration with `php-cgi`. Your API requests are executed as CGI processes, eliminating the need to expose internal PHP ports.
- **Professional CLI (Deca)**: A Go-based global CLI tool for project scaffolding, server management, and database operations.
- **Robust ORM & Migrations**: Professional-grade migration system with full support for **batching** and **rollbacks**.
- **Live Reload (SSE)**: Automatic browser refresh triggered by a lightweight Server-Sent Events hub whenever you save a file.

---

## 🚀 Getting Started

### 1. Prerequisites
- **PHP**: 8.1 or higher (8.2+ recommended)
- **Composer**: Latest version
- **Go**: 1.21 or higher (to build Deca CLI from source)
- **Database**: MySQL, MariaDB, or any PDO-compatible database.

### 2. Project Installation
```bash
# Clone the repository
git clone https://github.com/LubiXLubiX/Deca.git
cd Deca

# Install PHP dependencies
composer install

# Setup environment
cp .env.example .env
# Edit .env with your database credentials
```

### 3. Install Deca CLI
Deca is the brain of your development workflow.

#### macOS / Linux
```bash
cd Deca-CLI
go build -o deca
chmod +x deca
sudo mv deca /usr/local/bin/deca
```

#### Windows (PowerShell)
```powershell
cd Deca-CLI
go build -o deca.exe
mkdir $env:USERPROFILE\bin
move .\deca.exe $env:USERPROFILE\bin\deca.exe
# Add $env:USERPROFILE\bin to your System PATH
```

---

## 🏗️ Architecture & Request Flow

LubiX uses a **Unified Proxy Architecture** managed by Deca:

1.  **Incoming Request**: All traffic hits `localhost:3000`.
2.  **Routing Logic**:
    -   **`/api/*`**: Routed to the PHP backend via **FastCGI (`php-cgi`)**.
    -   **`/deca/app.js`**: A bundled single-file JS serving your entire React app.
    -   **Static Assets**: Served directly from `public/` or `resources/app/`.
    -   **SPA Routes**: Any non-file request serves `resources/app/index.html`.

### Backend (PHP)
The backend follows a modern MVC/Service pattern.
-   `public/index.php`: The main entry point.
-   `bootstrap/app.php`: Handles service container and application initialization.
-   `routes/api.php`: Define your API endpoints here.

### Frontend (React)
Located in `resources/app/`.
-   `src/main.js`: Bootstraps the React application.
-   `src/ui/pages/`: Put your `.jsx` components here.
-   **Tailwind CSS**: Loaded via CDN in `index.html` for instant utility-first styling.

---

## 🛠️ Available Commands

### Project Management
```bash
# Create a new LubiX project
lubix create-project <project-name>

# Start development server
lubix serve

# Start development with hot reload
lubix dev
```

### Database Operations
```bash
# Create database from .env configuration
lubix db:create

# Run all pending migrations
lubix migrate

# Rollback the last migration batch
lubix migrate:rollback

# Rollback multiple batches (e.g., last 3)
lubix migrate:rollback 3
```

### Code Generation
```bash
# Create a new controller
lubix make:controller <ControllerName>

# Create a new model
lubix make:model <ModelName>

# Create a new migration
lubix make:migration <migration_name>
```

---

## 🛠️ Database & Migrations

LubiX provides a powerful CLI for database management:

```bash
# Create the database defined in .env
lubix db:create

# Run all pending migrations
lubix migrate

# Rollback the last migration batch
lubix migrate:rollback

# Rollback multiple batches (e.g., last 3)
lubix migrate:rollback 3
```

---

## 📂 Project Structure

-   `Deca-CLI/`: Source code for the Go-based CLI.
-   `app/`: Application logic (Models, Controllers, Services).
-   `database/migrations/`: Database schema definitions.
-   `packages/`: Core framework components (ORM, CLI, Core).
-   `public/`: Publicly accessible assets (images, logos).
-   `resources/app/`: React frontend source code.
-   `routes/`: API and Web route definitions.

---

## ❓ Troubleshooting

### Blank Page in Browser
-   Check the browser console (F12) for syntax errors in your JSX files.
-   Ensure `deca lubix serve` is running and the logs show `Bundle GET /deca/app.js` with status 200.
-   Verify that your components use explicit ESM imports from `https://esm.sh`.

### "php-cgi" not found
-   Deca requires `php-cgi` to be in your PATH. 
-   On macOS, `brew install php` usually includes it. 
-   Run `deca doctor` to verify your environment health.

---

## 🗺️ Roadmap
-   Full support for React 18+.
-   Pre-built Tailwind CSS compiler integration.
-   Plugin system for the Deca CLI.

---

## 📄 License
LubiX is open-sourced software licensed under the [MIT license](LICENSE).
