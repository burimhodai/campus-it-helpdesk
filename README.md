# Campus IT Help Desk

Campus IT Help Desk is a Laravel and MySQL web application for reporting and managing technical support problems. Students and employees can create tickets and follow their progress. IT staff work from a separate administration area where they assign, prioritize, update, and resolve requests.

This repository is the final project for the Web Programming course.

## Main features

### User area

- Account registration, login, and logout
- Dashboard with ticket totals by status
- Create a support ticket with category and priority
- View and filter only the signed-in user's tickets
- Edit a ticket while it is still new and unassigned
- Add follow-up messages and read public staff responses
- Track status, assigned technician, and the complete public history

### Administration area

- Dashboard with open, urgent, unassigned, and resolved ticket counts
- Search and filter tickets by status, priority, category, or assignment
- Assign a ticket to an administrator or leave it unassigned
- Change ticket status and priority
- Post a public response or an internal staff note
- Create, edit, disable, and safely delete ticket categories

## Technology

- PHP 8.2+
- Laravel 12
- MySQL or MariaDB
- Blade templates
- HTML, CSS, and a small amount of JavaScript
- Vite and npm for frontend assets
- PHPUnit for automated feature tests

## Run the project locally

The following setup uses XAMPP on Windows. PHP, Composer, Node.js/npm, and MySQL must be available.

1. Open the `helpdesk` folder in a terminal.
2. Start MySQL from the XAMPP Control Panel.
3. Create an empty database named `campus_helpdesk`. In phpMyAdmin, the SQL command is:

```sql
CREATE DATABASE campus_helpdesk
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

4. Create the local configuration file:

```powershell
Copy-Item .env.example .env
```

5. Check these values in `.env`:

```dotenv
APP_NAME="Campus IT Help Desk"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_helpdesk
DB_USERNAME=root
DB_PASSWORD=
```

6. Install dependencies and generate the application key:

```powershell
composer install
php artisan key:generate
npm install
npm run build
```

If PHP is not available as `php` in PowerShell, use `C:\xampp\php\php.exe` in its place. In this workspace, Composer can also be run with `C:\xampp\php\php.exe ..\composer.phar`.

7. Build the tables and load the demonstration data:

```powershell
php artisan migrate:fresh --seed
```

`migrate:fresh` deletes existing tables in the selected database. Use it only for this project database. For an existing installation that must keep its data, use `php artisan migrate` instead.

8. Start the application:

```powershell
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Demonstration accounts

| Role | Email | Password |
|---|---|---|
| Administrator | `admin@campus.test` | `Admin123!` |
| Support technician | `support@campus.test` | `Support123!` |
| Student | `student@campus.test` | `User123!` |
| Employee | `employee@campus.test` | `User123!` |

These are local demonstration accounts only. Do not reuse their passwords on a public deployment.

## Project demonstration

Use the prepared scenario in [docs/DEMONSTRATION.md](docs/DEMONSTRATION.md). It covers the complete user-to-administrator workflow, database relationships, validation, authorization, responsive design, and the code areas worth showing during the presentation.

To return the application to the exact demonstration state at any point, run:

```powershell
php artisan migrate:fresh --seed
```

## Database

The application uses four main normalized tables: `users`, `categories`, `tickets`, and `ticket_updates`. Laravel's session, cache, and queue support tables are also included.

- Migrations: `database/migrations`
- Repeatable sample data: `database/seeders/DatabaseSeeder.php`
- Standalone MySQL structure script: `database/schema/mysql.sql`
- ER diagram and normalization explanation: [docs/PROJECT_REPORT.md](docs/PROJECT_REPORT.md)

The migrations are the main source of truth. The SQL file is supplied for inspection or manual database creation as required by the project brief.

## Tests and code checks

Run the automated workflow tests:

```powershell
php artisan test
```

Run the PHP style check and compile the frontend:

```powershell
vendor\bin\pint --test
npm run build
```

The feature tests cover authentication, access control, creating and editing tickets, privacy between users, replies, administrator assignment and resolution, internal notes, and category safeguards.

## Repository and cloud deployment

The project is ready to be stored in GitHub. The `.env`, installed dependencies, generated assets, logs, and local database are excluded from version control.

For an optional public demonstration, Railway can deploy Laravel from a GitHub repository and provide a managed MySQL service. The practical deployment checklist is in [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md). Cloud hosting is optional; the local XAMPP setup is the primary supported demonstration environment.

## Documentation

- [Project report](docs/PROJECT_REPORT.md)
- [Demonstration script](docs/DEMONSTRATION.md)
- [Deployment and GitHub guide](docs/DEPLOYMENT.md)
- [MySQL schema](database/schema/mysql.sql)

