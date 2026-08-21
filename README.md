# ShopEase

**A modern e-commerce platform built for the Bangladeshi market.**

ShopEase is a single-store online shop that lets customers browse products, add items to cart, and checkout using **Cash on Delivery (COD)** or **Stripe**. An admin panel gives full control over the catalog, orders, invoices, reviews, and sales reports.

| | |
|---|---|
| **Market** | Bangladesh |
| **Currency** | Bangladeshi Taka (৳ / BDT) |
| **Language** | English |
| **Stack** | Laravel 13 · Vue 3 · Inertia.js · Tailwind CSS · MySQL |

---

## Features

### Storefront
- **Home** — Hero carousel, categories, Best Selling, New Collection
- **Shop** — Filter by category/price, sort, search, pagination
- **Product Details** — Image gallery, ৳ pricing, stock badge, reviews & ratings
- **Cart** — Quantity update/remove, live totals
- **Checkout** — Shipping form, delivery charge (inside/outside Dhaka), COD + Stripe
- **Order Confirmation** — Unique order number + invoice
- Fully responsive (mobile-first: 360px / 768px / 1280px)

### Admin Panel
- Dashboard (orders, sales ৳, pending orders, products)
- Hero slide management (CRUD + reorder)
- Category management (CRUD, soft-delete)
- Product management (CRUD, multi-image, stock status toggle, Best Seller / Featured flags)
- Order management (status lifecycle: Pending → Processing → Shipped → Delivered → Cancelled)
- Invoice view & PDF download
- Review moderation (approve / hide / delete)
- Sales reports (date range, metrics, best-sellers, category sales, CSV/PDF export)

### Business Rules
- Stock is **badge-only** (`In Stock` / `Stock Out`) — no quantity tracking
- Stock Out products cannot be added to cart or purchased
- Every order generates a unique order number + invoice
- Reviews require a registered account and admin approval before public display
- Order placement and status changes trigger queued email notifications

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 13 (PHP) |
| Frontend | Vue 3 + Inertia.js |
| Styling | Tailwind CSS |
| Starter | Laravel Starter Kit (Inertia + Vue) |
| Database | MySQL 8 (InnoDB, utf8mb4) |
| Auth | Laravel built-in (role: `admin` / `customer`) |
| Payments | Cash on Delivery + Stripe |
| Email | Laravel Mail (queued) |
| PDF | DomPDF (invoices & reports) |
| Storage | Laravel public disk (images) |

---

## Project Structure (High-Level)

```
shopease/
├── app/
│   ├── Http/Controllers/     # Storefront + Admin controllers
│   ├── Models/               # Eloquent models + relationships
│   ├── Mail/                 # Order confirmation, status, admin alert
│   └── ...
├── database/
│   ├── migrations/           # All tables (see DATABASE_DESIGN.md)
│   ├── seeders/              # Realistic Bangladeshi sample data
│   └── factories/
├── resources/
│   ├── js/
│   │   ├── Pages/            # Inertia pages (Store + Admin)
│   │   ├── Components/       # Reusable Vue components
│   │   ├── Layouts/          # StoreLayout, AdminLayout
│   │   └── Composables/      # Currency helper, etc.
│   └── css/
├── routes/
│   ├── web.php               # Storefront routes
│   └── .........
└── ...
```

---

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL 8
- (Optional) Redis for queue (or use `database` driver)
- Stripe account (test + live keys)

---

## Installation

### 1. Clone & Install Dependencies

```bash
git clone <your-repo-url> shopease
cd shopease

composer install
npm install
```

### 2. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME=ShopEase
APP_URL=http://localhost:8000


### 3. Database

```bash
php artisan migrate --seed
```

```

```

Visit: **http://localhost:8000**


## Payment Methods

| Method | Behavior |
|--------|----------|
| **COD** | Order placed immediately with `payment_status = pending` |
| **Stripe** | Redirect / PaymentIntent flow → on success mark order paid & confirmed; on failure/cancel keep unpaid |

### Stripe Notes
- Use **test keys** during development
- Webhooks must be configured for reliable payment confirmation (idempotent handling)
- Currency charged via Stripe can be BDT (if supported in your Stripe account) or converted — confirm with your Stripe dashboard settings
- Never expose `STRIPE_SECRET` on the frontend

---

## Currency Formatting

All prices are stored in BDT and displayed with the Taka symbol and thousands separators:


A shared PHP helper and Vue composable keep formatting consistent across the entire application.

---

## Key Documentation

| Document | Purpose |
|----------|---------|
| [REQUIREMENT_ANALYSIS.md](./REQUIREMENT_ANALYSIS.md) | Functional & non-functional requirements |
| [PRD.md](./PRD.md) | Product requirements, user flows, acceptance criteria |
| [DESIGN_GUIDELINES.md](./DESIGN_GUIDELINES.md) | Colors, typography, spacing, component tokens |
| [DATABASE_DESIGN.md](./DATABASE_DESIGN.md) | Full schema, relationships, indexes, enums |
| [AI_PROMPTS.md](./AI_PROMPTS.md) | Sequenced prompts to build the app phase-by-phase |

---

## Development Phases (Recommended Order)

| Phase | Focus | Output |
|-------|-------|--------|
| 0 | Project Setup | Running Laravel + Vue + Inertia + Tailwind |
| 1 | Storefront UI | Professional static pages with dummy data |
| 2 | Database | Migrations, models, seeders |
| 3 | Dynamic Storefront | Real data + COD checkout end-to-end |
| 4 | Admin Panel | Full CRUD modules + reports |
| 5 | Payment + Email | Stripe + queued notifications |
| 6 | Polish & QA | Responsiveness, a11y, performance |

See `AI_PROMPTS.md` for the exact prompts to use with an AI coding assistant.

---

## Testing Checklist (Acceptance)

- [ ] Home renders Hero → Categories → Best Selling → New Collection → Footer
- [ ] Shop supports filter, sort, search, pagination; Stock Out blocked
- [ ] Product details show gallery, ৳ price, stock badge, approved reviews
- [ ] Cart updates totals correctly; empty state handled
- [ ] Checkout works for both **COD** and **Stripe**
- [ ] Stripe success/fail/cancel handled idempotently (webhooks + client confirmation)
- [ ] Every order generates an invoice + confirmation email
- [ ] Customers can submit reviews; admin can moderate them
- [ ] Order status changes notify the customer by email
- [ ] Admin can manage hero, categories, products, orders, invoices, reviews, reports
- [ ] Sales reports show accurate figures with date filtering
- [ ] All currency in ৳; UI in English; fully responsive at 360 / 768 / 1280px

---

## Security Notes

- CSRF protection on all forms
- Input validation via Form Requests
- Hashed passwords (Laravel default)
- Validated image uploads
- Stripe webhooks verified with signing secret before marking orders paid
- Idempotent payment handling (no duplicate confirmations)
- Never commit real Stripe secret keys

---

## License

This project is private / proprietary unless otherwise stated by the owner.

---

## Support

For questions about requirements or design, refer to the documents listed above.  
For development issues, open an issue in the repository.

---

**ShopEase** — Simple, professional e-commerce for Bangladesh.
