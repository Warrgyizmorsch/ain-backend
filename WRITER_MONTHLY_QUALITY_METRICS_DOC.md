# Writer Monthly Quality Metrics — Technical & Operational Documentation

## 1. Overview & Objective

The **Writer Monthly Quality Metrics** system is an automated performance and quality-tracking module designed for the Assignment In Need (AIN) CRM. It tracks and surfaces quality infractions for writers on a strictly monthly rolling basis:

1. **Ticket Count**: Number of orders in the current month where an issue ticket was generated (`TCK-XXXXX` / `feedbackissue = 1`).
2. **Failed Orders Count**: Number of orders in the current month marked as failed via the red failed button (`❌` / `is_fail = 1`).
3. **Combined Total Issues**: The aggregate sum of both quality issues (`Tickets + Failed Orders`).

---

## 2. Role-Based Visibility Matrix

Per strict operational requirements, visibility is tightly controlled. Writers see **only their own** metrics, Admins see all, and non-writer operational roles (Marketing, Sales, Clients) cannot see quality scores:

| Role ID | Role Name | Can View Quality Metrics? | Visibility Scope | Where It Appears |
|---|---|---|---|---|
| **Role 1** | Super Admin |  **Yes** | All Writers | Main Orders Table (`/orders`), Writer Portals |
| **Role 9** | Sub Admin |  **Yes** | All Writers | Main Orders Table (`/orders`), Writer Portals |
| **Role 7** | Sub-Writer (Writer Team) |  **Yes** | **Own Monthly Count Only** | Sub-Writer Portal (`/order` via `sub-writer_order.blade.php`) |
| **Role 6** | Writer Team Leader (TL) |  **Yes** | **Own Monthly Count Only** | Writer TL Portal (`/order` via `writer-tl_order.blade.php`) |
| **Role 8** | Writer Admin |  **Yes** | **Own Team Monthly Count Only** | Writer Admin Portal (`/order` via `writer-admin_order.blade.php`) |
| **Role 4** | Marketing Team | ❌ **No (Hidden)** | None | N/A |
| **Role 5** | Project Team | ❌ **No (Hidden)** | None | N/A |
| **Role 3** | Feedback Team | ❌ **No (Hidden)** | None | N/A |
| **Role 2** | User / Client | ❌ **No (Hidden)** | None | N/A |

---

## 3. Data Architecture & Calculation Rules

### 3.1 Monthly Window
- Current month is dynamically bounded:
  - `startOfMonth`: e.g. `2026-10-01 00:00:00`
  - `endOfMonth`: e.g. `2026-10-31 23:59:59`
- Dynamic formatting displays the human-readable month name (e.g., `"October 2026"`).

### 3.2 Failed Orders Identification (`failed_count`)
An order is counted as a failure when:
- `orders.is_fail = 1`
- **Date Matching**:
  - Primary: `orders.failed_at` falls between `startOfMonth` and `endOfMonth`.
  - Fallback (legacy records where `failed_at` is null): `orders.order_date LIKE 'YYYY-MM%'`.

### 3.3 Ticket Generation Identification (`ticket_count`)
An order is counted as having a ticket generated when:
- `orders.feedbackissue = 1` OR (`orders.feedback_ticket IS NOT NULL AND orders.feedback_ticket != ''`).
- **Date Matching**:
  - Primary: `orders.feedback_date` falls between `startOfMonth` and `endOfMonth`.
  - Fallback (legacy records where `feedback_date` is null): `orders.order_date LIKE 'YYYY-MM%'`.

### 3.4 Writer Association by Role
- **Sub-Writer (Role 7)**:
  - Linked either through the multi-writer junction table `multiple_wrtier` (`multiple_wrtier.user_id = auth()->id()`) OR direct assignment `orders.swid = auth()->id()`.
- **Writer Team Leader (Role 6)**:
  - Linked through `orders.wid = auth()->id()`.
- **Writer Admin (Role 8)**:
  - Linked through `orders.admin_id = auth()->id()`.
- **Admin Lookup (Role 1 & 9)**:
  - Resolves via `orders.swid`, `orders.wid`, or junction table `multiple_wrtier.user_id`.

---

## 4. UI / UX Design & Presentation

### 4.1 Top KPI Summary Banner (Writer Portals)
Located at the top of the order listing in `sub-writer_order.blade.php`, `writer-tl_order.blade.php`, and `writer-admin_order.blade.php`:

```
+-----------------------------+-----------------------------+-----------------------------+
|    [🎫 Icon]                |    [❌ Icon]                |    [⚠️ Icon]                |
|    2                        |    1                        |    3                        |
|    This Month's Tickets     |    This Month's Failed      |    Total Issues             |
|    (October 2026)           |    Orders                   |    (Tickets + Failed)       |
+-----------------------------+-----------------------------+-----------------------------+
```

### 4.2 Table Column: `Monthly Quality (Tickets / Fail)`
Added to the data table immediately before the `Actions` column:
- Displays the writer's month summary:
  - `[ October 2026 ]` (Header pill)
  - `[ 🎫 Tickets: 2 ]  [ ❌ Failed: 1 ]`
  - `[ Total: 3 ]`
- Displays individual order incident flags if this specific order triggered an infraction:
  - `[ 🎫 TCK-60336 ]` (Ticket raised on this order)
  - `[ ❌ Failed Job ]` (Marked failed on this order)

### 4.3 Admin Main Orders View (`row.blade.php`)
In the main orders table (`/orders`), Super Admins and Sub Admins see the assigned writer's monthly quality score badge directly underneath the writer's name and deadline. Non-admin roles see only the standard writer details.

### 4.4 Real-time AJAX Search Alignment
The AJAX search endpoints (`/search-writer` and `/search-writerTl`) in `OrderController.php` output the matching `<td>` cell dynamically, preserving table grid alignment even during active filtering.

---

## 5. End-to-End Operational Flow Example

### Scenario
1. Sub-Writer **Charmee** (Role 7, ID 8328) is assigned Order **`UKS60336`**.
2. **Current State**:
   - Tickets this month: `0`
   - Failed orders this month: `0`
   - Total issues: `0`
   - Charmee opens the Sub-Writer portal (`/order`):
     - Top KPI cards show `0 Tickets | 0 Failed | 0 Total`.
     - Order row shows clean badges: `Total: 0`.

3. **Event A: Issue Ticket Raised**
   - Support or QA submits a feedback chat message for order `UKS60336`.
   - `OrderController::sendFeedback()` sets:
     - `orders.feedback_ticket = 'TCK-60336'`
     - `orders.feedbackissue = 1`
     - `orders.feedback_date = now()` (e.g. `2026-10-07 06:10:16`).
   - **Immediate Impact**:
     - Charmee's monthly metrics become: **`Tickets: 1 | Failed: 0 | Total: 1`**.
     - Top KPI Banner highlights `1` in blue.
     - Row for `UKS60336` displays: `🎫 TCK-60336` red warning badge.

4. **Event B: Order Marked as Failed**
   - QA clicks the Red Button `❌` on the order.
   - `OrderController::updateOrderStatus()` sets:
     - `orders.is_fail = 1`
     - `orders.failed_at = now()` (e.g. `2026-10-07 06:15:00`).
   - **Immediate Impact**:
     - Charmee's monthly metrics become: **`Tickets: 1 | Failed: 1 | Total: 2`**.
     - Top KPI Banner updates: `Tickets: 1`, `Failed: 1`, `Total: 2`.
     - Row for `UKS60336` displays both `🎫 TCK-60336` and `❌ Failed Job`.

5. **Event C: Admin Oversight**
   - Super Admin or Sub Admin logs in and opens `/orders`.
   - Under the Writer column for `UKS60336`, Admin sees:
     `[ 🎫 1 | ❌ 1 | Total: 2 ]`
   - Marketing team logs into the same CRM:
     - The quality badges and columns are completely omitted from their DOM/UI.

---

## 6. Implementation Summary & Modified Files

### Modified Files:
1. **`app/Helpers/helpers.php`**:
   - `can_view_writer_quality_metrics(?int $roleId)`: Role authorization gate.
   - `get_writer_monthly_quality_metrics($userId, $roleId, $month)`: Aggregate monthly calculations with static in-memory caching.
   - `render_writer_monthly_quality_cell(array $stats, $order)`: HTML renderer for table cells with badges.
2. **`app/Http/Controllers/OrderController.php`**:
   - `handleRoleSix()`: Computes `$data['writerMonthlyStats']` for Writer TL.
   - `handleRoleSeven()`: Computes `$data['writerMonthlyStats']` for Sub-Writer.
   - `handleRoleEight()`: Computes `$data['writerMonthlyStats']` for Writer Admin.
   - `search_writer()`: Injects monthly quality `<td>` in AJAX responses.
   - `search_writerTl()`: Injects monthly quality `<td>` in AJAX responses.
3. **`resources/views/order/sub-writer_order.blade.php`**:
   - Added Top 3 KPI ribbon cards.
   - Added `Monthly Quality (Tickets / Fail)` table header and data cells.
4. **`resources/views/order/writer-tl_order.blade.php`**:
   - Added Top 3 KPI ribbon cards.
   - Added `Monthly Quality (Tickets / Fail)` table header and data cells.
5. **`resources/views/order/writer-admin_order.blade.php`**:
   - Added Top 3 KPI ribbon cards.
   - Added `Monthly Quality (Tickets / Fail)` table header and data cells.
6. **`resources/views/back-end/order/partials/row.blade.php`**:
   - Added Role 1 and Role 9 restricted monthly quality badge in the Writer column.

---

## 7. Performance & Zero N+1 Guarantee

To guarantee fast page loads even with tables containing hundreds of orders:
- Calculations use static in-memory caching within the request lifecycle (`static $cache = []`).
- Writer portals calculate the writer's monthly total **exactly once** and pass it to all rows.
- On the main orders table, queries execute only for distinct writers and are cached for the duration of the request.
- No redundant DB round trips are made.

