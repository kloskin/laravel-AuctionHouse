# Laravel AuctionHouse 🏠🧾

Aukcyjny system webowy stworzony w Laravelu, wykorzystujący **MongoDB** jako trwałą bazę danych i **Redis** do szybkich operacji w czasie rzeczywistym. Projekt uruchamiany w środowisku **Docker** (PHP + NGINX + MongoDB + Redis).

---

## 🚀 Stack technologiczny

- **Laravel** – framework PHP
- **MongoDB** – baza danych dokumentowa (`mongodb/laravel-mongodb`)
- **Redis** – cache, live ranking, liczniki
- **Docker** – konteneryzacja całego środowiska
- **NGINX** – reverse proxy + serwer HTTP
- **PHP-FPM** – silnik backendu

---

## ⚙️ Uruchomienie projektu

1. **Klonowanie repozytorium**

```bash
git clone https://github.com/twoja-nazwa-uzytkownika/laravel-auctionhouse.git
cd laravel-auctionhouse
```

2. **Budowanie i uruchomienie kontenerów**

```bash
docker-compose up -d --build
```

3. **Instalacja zależności i konfiguracja Laravel**

```bash
docker exec -it laravel_app bash
composer install
cp .env.example .env
php artisan key:generate
```

4. **Otwórz aplikację w przeglądarce**

```
http://localhost:8000
```

---

## 🔧 Kluczowe zmienne `.env`

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mongodb
DB_HOST=mongo
DB_PORT=27017
DB_DATABASE=auction

REDIS_HOST=redis
REDIS_PORT=6379
REDIS_CLIENT=phpredis
```

---

## 📁 Docker – struktura

- `docker/php/Dockerfile` – obraz PHP z obsługą MongoDB i Redis
- `docker/nginx/default.conf` – konfiguracja NGINX do serwowania Laravel
- `docker-compose.yml` – definicja usług aplikacji

---

## 🧪 Test MongoDB (opcjonalnie)

Dodaj do `routes/web.php`:

```php
use Illuminate\Support\Facades\DB;

Route::get('/test-mongo', function () {
    return DB::connection('mongodb')->listCollections();
});
```

---

## ✅ TODO – funkcje projektu

- [ ] Obsługa aukcji (CRUD – MongoDB)
- [ ] Licytacje w czasie rzeczywistym (Redis ZSET)
- [ ] Liczniki odwiedzin (Redis)
- [ ] Buforowanie popularnych aukcji
- [ ] System użytkowników i reputacji

---

## 📄 Licencja

MIT © 2025
