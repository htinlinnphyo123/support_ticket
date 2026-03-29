# Support Ticket Portal

A robust, role-based Issue Tracking & Service Level Agreement (SLA) platform built with Laravel to empower Support Agents and Clients. 

---

## 1. Frontend Approach
**Stack:** Vue 3 (Composition API) + Inertia.js + Tailwind CSS (Flowbite).

**Why this approach?**
The assignment requested a JavaScript frontend that consumes data from Laravel without relying on Livewire. Inertia.js is the perfect fit: it allows us to build a modern, reactive Single Page Application (SPA) using Vue components while strictly maintaining a conventional Laravel MVC architecture on the backend. It cleanly separates the API/Data layer (handled via Laravel Resources) from the UI layer, resulting in a fast, API-driven frontend without the overhead of manually managing Axios state for every single page load.

---

## 2. Architecture & Main Design Choices
- **Service Layer (`TicketService`)**: I moved heavy business logic (such as SLA deadline calculation and Dashboard statistics aggregation) out of the Controllers. This keeps the Controllers lean, focused strictly on HTTP request routing, and makes the business logic highly testable.
- **Enums for Domain Logic**: Utilized native PHP Enums (`TicketPriority`, `TicketStatus`, `SlaStatus`) to centralize business rules. By embedding methods like `slaHours()` directly into the Enum, the application completely avoids "magic strings" and centralizes the source of truth.
- **Strict Resource Isolation**: I utilized Laravel API Resources (`TicketResource`, `CommentResource`) to rigidly control the JSON payloads injected into the Vue frontend. This guarantees that protected data (like internal agent notes) is physically stripped from the response before it ever reaches a client's browser.

---

## 3. Roles & SLA Implementation

### Role & Access Model
- **Client Users (Employees)**: Secured via Eloquent query scoping. A client querying the `TicketController` will only ever receive tickets where the creator's `organisation_id` matches their own.
- **Support Agents**: Unrestricted global view of the ticket queues. 
- **Internal Notes**: A boolean `is_public` flag exists on the `comments` table. When an Employee views a ticket, the eager-loading constraint `where('is_public', true)` acts as a firewall, ensuring internal banter is completely inaccessible.

### SLA Rules & Logic
- **High Priority** = 4 Hours
- **Medium Priority** = 24 Hours
- **Low Priority** = 48 Hours

**How it works seamlessly:**
Instead of relying on heavy background cron jobs to continuously sweep the database and flip ticket statuses, the system uses an event-driven date approach. Upon ticket creation (or when an Agent upgrades priority), `TicketService` calculates and stamps a concrete `due_date`. 
A dynamic model accessor (`getSlaStatusAttribute`) then evaluates `now()` against that `due_date` on the fly to instantly determine if the ticket is **On Track** 🟢, **Due Soon** 🟡, or **Overdue** 🔴.

---

## 4. Timebox, Scope, & Omissions

**Total Time Spent: ~8 Hours**
1. Database Diagram & Business Logic: 1 hr
2. Project Setup & Core CRUD (Organizations/Users): 2 hrs
3. Ticket Core Feature & SLA Logic: 2 hrs
4. Comments & Internal Notes Engine: 1 hr
5. Automated Testing (TDD): 1 hr
6. Documentation & Polish: 1 hr

**How I scoped it:**
I focused rigorously on the "Must-Haves" list. I successfully delivered complete Role separation, dynamic SLA tracking, and the strict Comment visibility firewall. Because I used Flowbite/Tailwind, I was able to rapidly hit some nice-to-haves as well, including advanced UI filtering and role-based Dashboard KPIs.

**What I deliberately left out within the timebox:**
- **Notifications / Queues**: I chose not to develop event-driven email notifications. Setting up robust Mailables and configuring asynchronous Queue workers would have pushed me past the 8-hour limit.
- **Audit Trails**: While there is a timeline of comments, a true `ticket_histories` table (tracking exactly who changed a status from X to Y) was scoped out in favor of proving the core business logic first.

---

## 5. Next Steps & Known Limitations

If I had more time to evolve this into a true production-ready enterprise app, I would address the following tech-debt and limitations:

1. **Shift to a Formal RBAC System**
   - *Limitation*: Currently, roles are managed via a simple `type` Enum on the User model.
   - *Next Step*: Replace hard-coded string comparisons with a scalable permissions system (e.g., `spatie/laravel-permission`). This allows for generic middleware guarding and the easy introduction of mid-tier roles like "Junior Agent" or "Team Lead".

2. **Hierarchical Organization Roles**
   - *Limitation*: Client users are all flat elements of a single organization.
   - *Next Step*: Introduce an "Owner" or "Manager" role per organization so clients can self-manage their own employee rosters and view overarching company metrics without needing our Agents to do it for them.

3. **Advanced Comment Interactions**
   - *Limitation*: The comment system is a functional but flat timeline.
   - *Next Step*: Implement nested replies (threading) and emoji reactions. In real-world enterprise support, threading drastically improves the readability of long, multi-day investigations.

4. **UX Refinements for Destructive Actions**
   - *Limitation*: Deleting records currently uses the native browser `window.confirm()` popup window constraint.
   - *Next Step*: Refactor the UI to use styled, non-blocking Vue Modal components for all destructive confirmations to keep the user immersed in the branded application context.
