# Yanji Restaurant Reservation System - Requirements

## Overview

This document defines the requirements for the Yanji restaurant table and reservation management system. **Always read this document before making changes to ensure consistency.**

---

## 1. Table Configuration

### 1.1 Regular Tables

Regular tables are fixed-capacity tables that cannot be split or merged.

| Property | Description |
|----------|-------------|
| `id` | Unique identifier (e.g., "1", "2", "10") |
| `name` | Display name (e.g., "Table 1") |
| `nameKo` | Korean display name (e.g., "테이블 1") |
| `minGuests` | Minimum guest capacity |
| `maxGuests` | Maximum guest capacity |
| `location` | Location type: "main", "private", "outdoor" |

### 1.2 Splittable Private Rooms

Private rooms can be configured as **splittable**, meaning they can operate as:
- **Part A** - Left/first section (e.g., "8A")
- **Part B** - Right/second section (e.g., "8B")
- **Merged (Full Room)** - Combined as one large private room (e.g., "Table 8")

#### Configuration Schema

```json
{
  "id": "8",
  "name": "Private Room 1",
  "nameKo": "프라이빗 룸 1",
  "location": "private",
  "splittable": true,
  "minGuests": 6,
  "maxGuests": 10,
  "parts": [
    {
      "id": "8a",
      "name": "Private Room 1-A",
      "nameKo": "프라이빗 룸 1-A",
      "minGuests": 3,
      "maxGuests": 5
    },
    {
      "id": "8b",
      "name": "Private Room 1-B",
      "nameKo": "프라이빗 룸 1-B",
      "minGuests": 3,
      "maxGuests": 5
    }
  ]
}
```

#### Current Splittable Tables

| Parent Table | Part A | Part B | Full Capacity | Part Capacity |
|--------------|--------|--------|---------------|---------------|
| Table 8 | 8A | 8B | 6-10 guests | 3-5 guests each |
| Table 9 | 9A | 9B | 6-10 guests | 3-5 guests each |

### 1.3 Mutual Exclusion Rules

**Critical Rule**: Parts A and B are **independent** of each other. The full room requires **both parts** to be free.

| Scenario | 8A Status | 8B Status | Table 8 (Full) Status |
|----------|-----------|-----------|----------------------|
| Nothing reserved | Available | Available | Available |
| 8A reserved | Reserved | **Available** | **Blocked** |
| 8B reserved | **Available** | Reserved | **Blocked** |
| Both 8A + 8B reserved | Reserved | Reserved | **Blocked** |
| Table 8 (full) reserved | **Blocked** | **Blocked** | Reserved |

**Key Points**:
- 8A and 8B do NOT block each other - they are separate bookable units
- If either 8A OR 8B is reserved, the full room (Table 8) becomes unavailable
- If the full room (Table 8) is reserved, both 8A AND 8B become unavailable

---

## 2. Reservation System

### 2.1 Duration Rules

Duration is automatically assigned based on guest count:

| Guest Count | Duration |
|-------------|----------|
| 1 person | 60 minutes |
| 2-4 persons | 90 minutes |
| 5-7 persons | 120 minutes |
| 8-10 persons | 180 minutes |

### 2.2 Table Assignment Priority

When multiple tables can accommodate a reservation, use this priority:

1. **Prefer regular tables over split tables** - Don't use 8A when Table 3 is available
2. **Prefer exact capacity match** - Don't assign 8-person table to 2-person party
3. **Prefer non-private rooms for small parties** - Save private rooms for larger groups
4. **Only use split tables when necessary** - Last resort for capacity matching

#### Assignment Algorithm

```
For a party of N guests:
1. Find all tables where minGuests <= N <= maxGuests
2. Sort by preference:
   a. Regular tables first (splittable: false)
   b. Then by capacity fit (closer to N is better)
   c. Split tables last (8A, 8B, 9A, 9B)
3. Check availability for each table in order
4. Assign first available table
```

### 2.3 Reservation Statuses

| Status | Description |
|--------|-------------|
| `confirmed` | Reservation is booked, guest not yet arrived |
| `seated` | Guest has arrived and is seated |
| `completed` | Dining session finished |
| `cancelled` | Reservation was cancelled |

### 2.4 Time Slot Availability

A time slot is **unavailable** if:
- Another reservation overlaps with the time range (startTime to endTime)
- The slot is in the past (for today only)
- The slot would extend beyond operation hours block end time

---

## 3. Operation Hours

### 3.1 Configuration

```json
{
  "operationHours": {
    "open": "11:30",
    "close": "22:00",
    "lastReservation": "20:30",
    "blocks": [
      { "name": "Lunch", "start": "11:30", "end": "14:30" },
      { "name": "Dinner", "start": "17:00", "end": "22:00" }
    ]
  }
}
```

### 3.2 Closed Days

Support for:
- Regular weekly closures (e.g., every Tuesday)
- Specific date closures (e.g., holidays)
- Date range closures (e.g., renovation period)

---

## 4. Dashboard Requirements

### 4.1 Table Cards (Main Grid)

Each table card shows:
- Table name and capacity
- **Current time slot status** (Available/Reserved/Occupied)
- Current reservation info (if any)
- Quick actions for current slot:
  - "Seat Now" button for confirmed reservations
  - "Clear Table" button for seated guests

### 4.2 Table Modal (Click to Expand)

When clicking a table card, show modal with:
- All time slots for that table grouped by block (Lunch/Dinner)
- Each slot shows:
  - Time range (e.g., "12:00 - 13:30")
  - Status badge (Available/Reserved/Occupied/Completed/Passed)
  - Guest info (if reserved)
  - Action buttons per slot

### 4.3 Splittable Table Display

For splittable tables (8, 9), the dashboard should show:
- Individual cards for each part (8A, 8B, 9A, 9B)
- Visual indicator showing linked status
- When one part is reserved, other parts show as blocked

### 4.4 Time Slot Statuses

| Status | Color | Description |
|--------|-------|-------------|
| Available | Teal (#4ecdc4) | Can be reserved |
| Reserved | Orange (#ff9f1c) | Confirmed, waiting for guest |
| Occupied | Red (#ff6b6b) | Guest is currently seated |
| Completed | Gray (#555) | Session finished |
| Passed | Dark Gray (#444) | Past slot, no reservation was made |
| Blocked | Purple (#8b5cf6) | Unavailable due to related table reservation |
| Limited | Amber (#f59e0b) | Part table with some sessions blocked by full room |

### 4.5 Table Card Status (Overview)

The table card in the main grid shows status based on **current time window**:

| Status | When Shown |
|--------|------------|
| Available | No blocking, table is free now |
| Reserved | Direct reservation starting within 30 minutes |
| Occupied | Guest currently seated |
| Blocked | Part is blocked NOW by full room reservation |
| Limited | Part is available now, but has future sessions blocked by full room reservation |

**Limited Status Example**:
- Table 8 (Full) reserved for 18:00-20:00
- Current time is 12:00
- 8A card shows **"Limited"** (not "Blocked") because 8A has available sessions now

### 4.5 Real-time Updates

- Auto-refresh every 30 seconds
- Manual refresh button available
- Time remaining indicator for occupied tables

---

## 5. Settings Requirements (Admin)

### 5.1 Table Management

Admin should be able to:
- Add/edit/delete regular tables
- Configure splittable tables:
  - Mark table as splittable
  - Define Part A and Part B properties
  - Set capacity for parts vs full room
- Set table location (main/private/outdoor)

### 5.2 Splittable Table Configuration UI

```
[ ] This table can be split into two sections

When checked, show:
┌─────────────────────────────────────────────────┐
│ Part A                    │ Part B              │
│ Name: [Private Room 1-A]  │ Name: [Private 1-B] │
│ Min: [3] Max: [5]         │ Min: [3] Max: [5]   │
└─────────────────────────────────────────────────┘

Full Room Capacity:
Min: [6] Max: [10]
```

### 5.3 Operation Hours Management

- Set open/close times
- Configure time blocks (Lunch, Dinner, etc.)
- Set last reservation time
- Configure closed days

---

## 6. Backend API Requirements

### 6.1 Availability Endpoint

`GET /reservations/available?date={date}&guestCount={count}`

Must return only truly available slots considering:
- Existing reservations
- Splittable table mutual exclusion
- Operation hours blocks
- Past time filtering (for today)

### 6.2 Conflict Detection

When creating a reservation, backend must check:

```typescript
function hasConflict(newReservation, existingReservations): boolean {
  for (const existing of existingReservations) {
    // Skip cancelled
    if (existing.status === 'cancelled') continue;

    // Check time overlap
    if (existing.startTime >= newReservation.endTime) continue;
    if (existing.endTime <= newReservation.startTime) continue;

    // Check table conflict
    if (isTableConflict(newReservation.tableId, existing.tableId)) {
      return true;
    }
  }
  return false;
}

function isTableConflict(tableA, tableB): boolean {
  // Same table
  if (tableA === tableB) return true;

  // Splittable table rules:
  // - "8" (full) conflicts with "8a" and "8b" (parts)
  // - "8a" conflicts with "8" (full) but NOT with "8b"
  // - "8b" conflicts with "8" (full) but NOT with "8a"

  const parentA = getParentTable(tableA);  // "8a" -> "8", "8" -> null
  const parentB = getParentTable(tableB);

  // Part conflicts with full room
  if (parentA && parentA === tableB) return true;  // 8a vs 8
  if (parentB && parentB === tableA) return true;  // 8 vs 8a

  // Parts do NOT conflict with each other (8a vs 8b = no conflict)
  // parentA === parentB would be true for 8a vs 8b, but we don't block

  return false;
}
```

### 6.3 Table ID Patterns

| Type | Pattern | Examples |
|------|---------|----------|
| Regular table | Numeric string | "1", "2", "10" |
| Parent splittable | Numeric string | "8", "9" |
| Split part | Number + letter | "8a", "8b", "9a", "9b" |

**Important**: When matching parts to parents, ensure "10a" doesn't match "1":
```typescript
function isPartOfTable(partId: string, tableId: string): boolean {
  if (!partId.startsWith(tableId)) return false;
  const suffix = partId.substring(tableId.length);
  return suffix.length > 0 && /^[a-z]/i.test(suffix);
}
// "8a".isPartOf("8") = true
// "10a".isPartOf("1") = false (suffix is "0a", starts with digit)
```

---

## 7. Customer-Facing Reservation Page

### 7.1 Flow

1. Select date from calendar
2. Select number of guests
3. View available time slots (grouped by Lunch/Dinner)
4. Select time slot
5. Enter contact information
6. Confirm reservation

### 7.2 Availability Display

- Show only truly available time slots
- Gray out unavailable slots
- Show "Limited availability" indicator when few tables remain
- Real-time polling (30 seconds) to update availability

### 7.3 Table Assignment

- Customer does NOT choose specific table
- System auto-assigns best available table based on priority rules
- Confirmation shows assigned table

---

## 8. Data Storage

### 8.1 Reservation Record

```typescript
interface Reservation {
  reservationId: string;      // UUID
  tableId: string;            // "1", "8", "8a", etc.
  tablePart: string | null;   // "8a", "8b", or null for regular/full
  customerName: string;
  customerPhone: string;
  guestCount: number;
  date: string;               // "2025-01-23"
  startTime: string;          // "12:00"
  endTime: string;            // "13:30"
  duration: string;           // "90min"
  status: ReservationStatus;
  notes?: string;
  createdBy: string;          // "customer" or "admin"
  createdAt: string;          // ISO timestamp
}
```

---

## 9. Menu System

### 9.1 Menu Display

The menu page (`menu.php`) displays all available menu sections and items with pricing and descriptions in multiple languages (English, Chinese, Korean).

### 9.2 Query Parameter Rules

The menu behavior is controlled by the `?table` query parameter:

#### Without Table Parameter (`menu.php`)
- Menu items are **displayed**
- **"Add to Cart" buttons are HIDDEN**
- **Shopping cart button is HIDDEN**
- **Admin controls are VISIBLE** (for authenticated users only)

#### With Table Parameter (`menu.php?table=1`)
- Menu items are **displayed**
- **"Add to Cart" buttons are VISIBLE**
- **Shopping cart button is VISIBLE**
- **Admin controls are VISIBLE** (for authenticated users only)

### 9.3 Admin Controls

Admin users (authenticated) can:
- Add new sections (+ Section button)
- Add new items to sections (+ Item button)
- Edit existing items
- Delete items
- Logout

Admin controls are **always visible** to authenticated users, regardless of the `?table` parameter.

### 9.4 Menu Structure

```typescript
interface MenuSection {
  sectionId: string;
  icon: string;
  names: {
    en: string;
    ko: string;
    zh: string;
  };
  meta?: {
    portion?: Record<string, string>;
    description?: Record<string, string>;
  };
  createdAt: string;
  updatedAt: string;
}

interface MenuItem {
  itemId: string;
  sectionId: string;
  names: {
    en: string;
    ko: string;
    zh: string;
  };
  description?: {
    en: string;
    ko: string;
    zh: string;
  };
  price: number;
  imageUrl?: string;
  vegetarian: boolean;
  createdAt: string;
  updatedAt: string;
}

interface MenuResponse {
  currency: string;        // "GBP"
  currencySymbol: string;  // "£"
  sections: MenuSection[];
}
```

### 9.5 Shopping Cart & Payment Flow

**Cart Functionality:**
- The shopping cart is **only functional** when accessed with the `?table` query parameter
- Users can add items to cart
- Cart displays subtotal, VAT (20%), and total
- Cart data persists in localStorage
- Cart button shows item count

**Checkout Process:**
When customers click "Place Order", they are redirected to a dedicated payment processor page with the following flow:

1. **Payment Method Selection**: User selects either "Credit Card" or "Cash"
2. **Credit Card Flow**:
   - Display manual card input form (no wallet integration)
   - Fields: Cardholder Name, Card Number, Expiry Date, CVV, Billing Address
   - Submit payment via `POST /payments/intent` API
   - Create order with payment reference via `POST /orders`
3. **Cash Flow**:
   - Display amount received input
   - Show change calculation
   - Optional staff notes field
   - Submit payment via `POST /payments/intent` API
   - Create order with payment reference

**Payment Processor Page:** `checkout-payment.php`
- Accessible from both `menu.php` (customer checkout) and `orders-dashboard.php` (staff payment processing)
- URL parameters: `?source=menu|dashboard&table=N&amount=X.XX&cart=...` (for menu) or `?source=dashboard&orderId=X&amount=X.XX` (for dashboard)
- Handles payment method selection and processing
- Updates both `yanji-orders` and `yanji-payments` tables upon successful payment
- Auto-redirects after payment completion

---

## 10. Payment System

### 10.1 Payment Methods

Two payment methods are supported:
1. **Credit Card** - Manual card input form
2. **Cash** - Amount received and change calculation

### 10.2 Payment Flow

```
Customer/Staff → Cart/Order → "Place Order"/"Pay" → 
  → Redirect to checkout-payment.php → 
  → Select Payment Method → 
  → Enter Payment Details → 
  → Process Payment (create payment record) → 
  → Create/Update Order → 
  → Success Notification → 
  → Auto-redirect to menu or dashboard
```

### 10.3 API Endpoints Called

**From checkout-payment.php:**
1. `POST /payments/intent` - Create payment intent/record
2. `POST /orders` - Create order (from menu) or `PUT /orders/{orderId}` - Update order (from dashboard)

### 10.4 Payment Record Schema

```json
{
  "paymentId": "UUID",
  "orderId": "UUID",
  "amount": 29.50,
  "paymentMethod": "card|cash",
  "paymentStatus": "pending|completed|failed",
  "cardLastFour": "1234" (for card payments),
  "amountReceived": 30.00 (for cash payments),
  "change": 0.50 (for cash payments),
  "notes": "Staff notes",
  "createdAt": "ISO-timestamp",
  "updatedAt": "ISO-timestamp"
}
```

---

## Revision History

| Date | Version | Changes |
|------|---------|---------|
| 2026-01-27 | 1.3 | Added Payment System (Section 10) - Payment processor page, methods, and flow |
| 2025-01-25 | 1.1 | Added Menu System requirements (Section 9) - Query parameter rules for cart and "Add to Cart" button visibility |
| 2025-01-23 | 1.0 | Initial requirements document |

---

**Remember**: Always read this document before implementing changes to ensure consistency with the overall system design.
