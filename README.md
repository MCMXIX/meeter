# Meeter

Meeter is a simple dating application built as a technical assessment using Laravel, Vue 3, Inertia.js, Tailwind CSS, and Docker.

## Tech Stack

- Laravel 12
- Vue 3
- Inertia.js
- Tailwind CSS
- MySQL
- Docker & Docker Compose
- Vite

---

## Features

- User Registration & Login
- User Profiles
- Browse People
- Search Users
- Direct Messaging
- Conversation List
- Message History
- Responsive UI

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/MCMXIX/meeter.git
cd meeter
```

### 2. Create the environment file

```bash
cp .env.example .env
```

### 3. Start Docker

```bash
docker compose up -d --build
```

### 4. Install dependencies

```bash
docker compose exec app composer install
docker compose exec node npm install
```

### 5. Generate application key

```bash
docker compose exec app php artisan key:generate
```

### 6. Run migrations and seeders

```bash
docker compose exec app php artisan migrate:fresh --seed
```

### 7. Build frontend assets

```bash
docker compose exec node npm run build
```

For development:

```bash
docker compose exec node npm run dev
```

### 8. Visit the application

```
http://localhost:8000
```

---

## Demo Account

Email

```
demouser{0-9}@.com
```

Password

```
password123
```
