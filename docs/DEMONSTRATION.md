# Project Demonstration

This demonstration follows one support request from submission to resolution. It is organized by actions and evidence rather than presentation time.

## Prepare the demonstration

1. Start MySQL in XAMPP.
2. From the project folder, run `php artisan migrate:fresh --seed`.
3. Run `php artisan serve`.
4. Open `http://127.0.0.1:8000`.
5. Keep two browser windows available: one for the student and one private/incognito window for the administrator.

Resetting with the seeder gives the same four categories, four sample tickets, and four accounts every time.

## Accounts

| Purpose | Email | Password |
|---|---|---|
| Student workflow | `student@campus.test` | `User123!` |
| Administrator workflow | `admin@campus.test` | `Admin123!` |
| Second staff member | `support@campus.test` | `Support123!` |

## Demonstration sequence

### 1. Introduce the problem and landing page

Explain that support requests sent through messages are difficult to assign and track. Show that the landing page immediately offers ticket submission, status tracking, and a staff workflow.

Expected evidence: public navigation contains login and registration, while protected pages are not exposed.

### 2. Show authentication and validation

Open registration and briefly point out the required fields. Submit an incomplete form to show server-side validation, then move to login and sign in as `student@campus.test`.

Expected evidence: invalid input returns clear field errors; successful login regenerates the session and opens the user dashboard.

### 3. Review the user dashboard

Point out the ticket totals and recent requests. Open `HD-260826-1003`, the urgent software problem.

Expected evidence: the page shows its reference, category, priority, status, description, and update history. It has no assigned technician yet.

### 4. Create a new ticket

Choose **New ticket** and enter a realistic request:

- Subject: `Projector has no image in room A-12`
- Category: `Hardware`
- Priority: `High`
- Description: `The projector powers on but shows no image from the lecturer computer. I checked the HDMI cable and selected the correct input.`

Submit it and note the generated `HD-...` reference.

Expected evidence: the ticket is connected to the signed-in user and category, begins as open, appears on the dashboard, and receives an initial history entry.

### 5. Prove user isolation

Open the ticket list to show that the student sees only their own records. If useful, sign in as `employee@campus.test` in another private window and show that copying the student's ticket URL returns a forbidden response.

Expected evidence: the ticket policy blocks access based on ownership rather than merely hiding links.

### 6. Work the queue as an administrator

In the private window, sign in as `admin@campus.test`. Show the admin dashboard, then open the ticket queue.

Use the filters to display urgent tickets or unassigned tickets. Search by part of a subject or a ticket reference. Open the newly created projector ticket.

Update it with:

- Assigned to: `Support Technician`
- Priority: `High`
- Status: `In progress`
- Public response: `The request has been assigned. A technician will check the projector and HDMI connection.`

Expected evidence: the assignment and status update are saved together, and a chronological update records who made the change.

### 7. Demonstrate internal and public communication

Add a second administrator update marked as an internal note:

`Bring a spare HDMI adapter and test laptop.`

Return to the student's window and refresh the ticket.

Expected evidence: the public response is visible to the student; the internal note is not. Both remain visible in the admin history.

### 8. Complete the ticket lifecycle

As the administrator, set the ticket to `Resolved` and post:

`The damaged HDMI adapter was replaced and the projector was tested successfully.`

Expected evidence: `resolved_at` is recorded, dashboard totals change, and the student sees the final response and resolved state.

### 9. Show category management

Open **Categories** in the admin area. Create a temporary category, edit its description, disable it, then delete it while it has no tickets. Point out that a used category cannot be deleted and must be disabled instead.

Expected evidence: category names and slugs are unique, disabled categories no longer appear in new ticket forms, and foreign-key history is protected.

### 10. Show responsive behaviour

Reduce the browser width or use device emulation. Open the user ticket list and a form.

Expected evidence: navigation wraps cleanly, tables become labelled blocks, forms remain usable, and no horizontal scrolling is required for the main workflow.

## Database evidence to show

Open phpMyAdmin and display the four core tables:

- `users`
- `categories`
- `tickets`
- `ticket_updates`

Use the structure view to point out the foreign keys. The ER diagram and the explanation of third normal form are in `docs/PROJECT_REPORT.md`.

## Code evidence to show

If the examiner asks how the application works, show these files:

1. `routes/web.php` for public, user, and administrator routes.
2. `app/Models/Ticket.php` for relationships, workflow constants, and reference generation.
3. `app/Policies/TicketPolicy.php` for ownership checks.
4. `app/Http/Controllers/Admin/TicketController.php` for filters and updates.
5. `database/migrations/2026_08_26_000002_create_tickets_table.php` for foreign keys and indexes.
6. `tests/Feature/TicketWorkflowTest.php` for executable proof of the main user rules.

## Questions to be ready for

- **Why Laravel?** It provides routing, authentication support, validation, ORM relationships, CSRF protection, migrations, and testing while the project still uses PHP throughout.
- **Why keep ticket updates in a separate table?** A ticket can have any number of messages and state changes. Separate rows avoid repeated columns and preserve chronological history.
- **How is unauthorized access prevented?** Authentication middleware identifies the user, the admin middleware protects staff routes, and the ticket policy checks record ownership.
- **Why can a used category not be deleted?** Tickets must keep a valid category for historical accuracy. Disabling it prevents future use without breaking existing records.
- **How is SQL injection prevented?** Laravel's query builder and Eloquent bind values instead of concatenating user input into SQL.
- **How can the database be reproduced?** Run the versioned migrations and seeder, or inspect/import the supplied MySQL structure script.

