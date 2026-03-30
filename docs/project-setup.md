# Project Setup Instructions

Follow these steps to completely set up and run the Support Ticket Portal locally.

## Prerequisites

Ensure you have the following installed on your machine:
- **PHP** (8.2 or higher)
- **Composer**
- **Node.js & NPM**
- **Database** (MySQL, SQLite, etc.)

---

## 1. Clone & Install Dependencies

Clone the project repository into your workspace:

```bash
git clone https://github.com/htinlinnphyo123/support_ticket
cd support_ticket
```

Install both the backend and frontend libraries:

```bash
composer install
npm install
```

---

## 2. Environment Configuration

Laravel requires an active environment configuration containing your database credentials and application encryption key.

Copy the boilerplate and generate your application key:

```bash
cp .env.example .env
php artisan key:generate
```

Open the `.env` file and verify your Database connection properties so Laravel can connect locally (e.g., MySQL default):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

---

## 3. Database Migration & Storage Linking

Run the migrations *with* the seed flag to build your tables and populate the system with dummy Users and Organisations to easily test the portal:

```bash
php artisan migrate --seed
```

Next, link the storage directory. This allows file attachments (like images or pdfs added to a comment) to be accessible correctly on the internet via the `public` directory:

```bash
php artisan storage:link
```

---

## 4. Starting the Application Ports

Because this application relies on a JavaScript framework (Vue 3/Vite), you need to compile assets concurrently alongside the backend.

Open **two separate terminal windows** inside the project directory:

**Terminal 1 (Frontend):**
```bash
npm run dev
```

**Terminal 2 (Backend API):**
```bash
php artisan serve
```

Visit the application at: `http://localhost:8000`

---

## 🔑 Login Credentials

If you successfully ran `php artisan migrate --seed`, the database will have automatically generated multiple accounts for you to test different User Roles. 

The universal password for seeded accounts is: `password`

**Support Agent View:**
- Email: `agent1@gmail.com`
- Password: `password`

**Client Employee View:**
- Email: `employee1@ogranisation1.com`
- Password: `password`
