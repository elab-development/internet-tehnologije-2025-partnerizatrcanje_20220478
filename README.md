# RunApp

RunApp je web aplikacija za upravljanje trkama i učešćima na trkama. Sistem omogućava pregled lokacija za trčanje, pregled svih i budućih trka, prijavu i otkazivanje učešća, prikaz korisničkih postova i komentara, kao i administraciju prijava. Aplikacija je realizovana kao monorepo projekat sa Laravel backend-om i React frontend-om.

## Glavne funkcionalnosti

- pregled početne stranice sa osnovnim informacijama o aplikaciji
- registracija i prijava korisnika
- pregled lokacija za trčanje na interaktivnoj mapi
- pregled svih trka i budućih trka
- prijava korisnika na trku
- pregled i otkazivanje sopstvenih učešća
- pregled postova i komentara
- dodavanje i brisanje komentara
- prikaz vremenskih uslova za datum održavanja trke korišćenjem Open-Meteo API-ja
- administracija učešća sa paginacijom i mogućnošću otkazivanja

## Tehnologije

### Frontend

- React
- React Router DOM
- React Bootstrap
- Bootstrap
- React Leaflet
- Axios
- React Toastify
- React Icons
- React Testing Library
- Jest

### Backend

- Laravel
- Laravel Sanctum
- Eloquent ORM
- Swagger / OpenAPI anotacije

### Baza i infrastruktura

- MySQL 8
- Docker
- Docker Compose
- Nginx
- PHP-FPM
- GitHub Actions

## Struktura projekta

```bash
.
├── client/                  # React frontend
├── server/                  # Laravel backend
├── docker/
│   └── nginx/               # Nginx konfiguracija
├── client.Dockerfile        # Dockerfile za frontend
├── server.Dockerfile        # Dockerfile za backend
└── docker-compose.yml       # Pokretanje celog sistema
```

## Pokretanje aplikacije lokalno

### Preduslovi

Pre lokalnog pokretanja potrebno je da su instalirani:

- Node.js 20+
- npm
- PHP 8.2+
- Composer
- MySQL 8
- Git

### 1. Kloniranje repozitorijuma

```bash
git clone <URL_REPOZITORIJUMA>
cd <IME_PROJEKTA>
```

### 2. Pokretanje backend-a

Pređi u `server` direktorijum i instaliraj PHP zavisnosti:

```bash
cd server
composer install
```

Kopiraj `.env` fajl i generiši aplikacioni ključ:

```bash
cp .env.example .env
php artisan key:generate
```

U `.env` fajlu podesi konekciju ka bazi, na primer:

```env
APP_NAME=RunApp
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trkaci
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

Pokreni migracije i, po potrebi, seed podatke:

```bash
php artisan migrate
php artisan db:seed
```

Pokreni Laravel server ili PHP-FPM/Nginx konfiguraciju, u zavisnosti od lokalnog setup-a. Za jednostavan razvoj može:

```bash
php artisan serve
```

### 3. Pokretanje frontend-a

U novom terminalu pređi u `client` direktorijum:

```bash
cd client
npm install
```

Kreiraj `.env` fajl i podesi adresu backend API-ja:

```env
REACT_APP_API_URL=http://localhost:8000
```

Ako backend koristi Nginx na portu 8080, onda vrednost može biti:

```env
REACT_APP_API_URL=http://localhost:8080
```

Pokreni frontend:

```bash
npm start
```

Frontend će biti dostupan na adresi:

```text
http://localhost:3000
```

## Pokretanje testova

Frontend testovi se pokreću iz `client` direktorijuma:

```bash
cd client
npm test
```

Za pokretanje testova bez watch moda:

```bash
npm test -- --watchAll=false
```

## Pokretanje pomoću Docker-a i Docker Compose-a

Projekat sadrži odvojene Docker konfiguracije za frontend i backend, kao i `docker-compose.yml` za pokretanje celog sistema.

### Servisi

Docker Compose podiže sledeće servise:

- `server` – Laravel backend u PHP-FPM kontejneru
- `web` – Nginx reverse proxy za backend
- `client` – React frontend
- `db` – MySQL baza podataka

### Pokretanje celog sistema

Iz root direktorijuma projekta pokreni:

```bash
docker compose up --build
```

Ili, ako koristiš stariju sintaksu:

```bash
docker-compose up --build
```

### Pristup aplikaciji

Nakon uspešnog pokretanja, servisi će biti dostupni na sledećim adresama:

- frontend: `http://localhost:3000`
- backend preko Nginx-a: `http://localhost:8080`
- MySQL: `localhost:33061`

### Zaustavljanje servisa

```bash
docker compose down
```

Za gašenje i brisanje volumena:

```bash
docker compose down -v
```

## API pregled

### Javni endpoint-i

- `POST /api/login` – prijava korisnika
- `POST /api/register` – registracija korisnika
- `GET /api/lokacije` – prikaz svih lokacija
- `GET /api/trke` – prikaz svih trka
- `GET /api/trke/buduce` – prikaz budućih trka
- `GET /api/trke/{id}` – detalji pojedinačne trke

### Zaštićeni endpoint-i (Sanctum)

- `POST /api/logout`
- `POST /api/trke`
- `DELETE /api/trke/{id}`
- `GET /api/trke/{id}/ucesca`
- `GET /api/ucesca`
- `GET /api/users/{userId}/ucesca`
- `GET /api/trke/{trkaId}/ucesca`
- `POST /api/ucesca`
- `DELETE /api/ucesca/{id}`
- `GET /api/postovi`
- `GET /api/postovi/{id}`
- `POST /api/postovi`
- `DELETE /api/postovi/{id}`
- `GET /api/komentari`
- `GET /api/komentari/{postId}`
- `POST /api/komentari`
- `DELETE /api/komentari/{id}`
- `GET /api/ucesca/paginacija`
- `GET /api/ucesca/{id}`

## Stranice aplikacije

Frontend deo aplikacije sadrži sledeće stranice:

- `/` – početna stranica
- `/login` – prijava i registracija
- `/o-nama` – informacije o timu i projektu
- `/lokacije` – mapa lokacija za trčanje
- `/trke` – pregled trka, prijava i vremenski podaci
- `/moja-ucesca` – pregled korisnikovih prijava
- `/postovi` – pregled postova i komentara
- `/admin` – administracija učešća

## Eksterni servisi

Aplikacija koristi sledeće eksterne servise:

- **OpenStreetMap / Leaflet** za prikaz mapa i lokacija za trčanje
- **Open-Meteo API** za prikaz istorijskih vremenskih uslova na dan održavanja trke

## Bezbednost

Backend koristi Laravel Sanctum za autentifikaciju tokenima i zaštitu privatnih ruta. Lozinke korisnika se čuvaju heširane pomoću `bcrypt`, a ulazni podaci se validiraju u kontrolerima korišćenjem Laravel Validator mehanizma.

## CI/CD

Za automatizaciju provere kvaliteta koristi se GitHub Actions pipeline. Na svaki `push` i `pull request` nad granom `main` pokreću se frontend testovi, a nakon uspešnog završetka testiranja grade se Docker image-i za frontend i backend.
