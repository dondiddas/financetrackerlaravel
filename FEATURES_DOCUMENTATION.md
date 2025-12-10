# 2. Features & Functionalities

This document explains the key features of the FinanceTracker application and how they fulfill CRUD operations (Create, Read, Update, Delete).

---

## Overview of Main Features

The FinanceTracker application provides comprehensive financial management with the following core modules:

1. **Transactions** (Income, Allowance, Expenses)
2. **Budgets**
3. **Bills & Subscriptions**
4. **Daily Limits**
5. **Reports & Analytics**

---

## 1. TRANSACTIONS MODULE

### Purpose
Manage all financial transactions including income, allowances, and expenses with categorization and detailed tracking.

### Insert Data (CREATE)

#### How Users Add Transactions:

**A. Daily Expenses**
- **Location**: Dashboard → Daily Expenses Card → "+" Button
- **Controller**: `ExpensesController@addDaily`
- **Process**:
  1. User clicks "Add Expense" button in Daily Expenses modal
  2. Fills out form with:
     - Category name (auto-created if new)
     - Amount
     - Note/description
  3. System creates or finds existing category with type 'expense'
  4. Creates new Transaction record with:
     - `user_id` (authenticated user)
     - `category_id`
     - `amount`
     - `note`
     - `transaction_date` (current date)
  5. Returns updated daily total via AJAX

**Code Example**:
```php
// ExpensesController.php - addDaily()
$category = Categories::firstOrCreate(
    ['name' => $request->category_name, 'user_id' => auth()->id()],
    ['type' => 'expense'] 
);

Transaction::create([
    'category_id' => $category->id,
    'amount' => $request->amount,
    'note' => $request->note,
    'user_id' => auth()->id(),
]);
```

**B. Income/Allowance**
- **Location**: Dashboard → Cash Balance Card → Toggle Income/Allowance → "+" Button
- **Controller**: `AllowanceController@addAllowances`
- **Process**:
  1. User selects type (Income or Allowance)
  2. Enters amount and optional note
  3. System finds or creates category based on type
  4. Creates Transaction record
  5. Updates cash balance display

**Code Example**:
```php
// AllowanceController.php - addAllowances()
$category = Categories::firstOrCreate([
    'name' => ucfirst($request->type), 
    'type' => $request->type,
    'user_id' => auth()->id(),
]);

Transaction::create([
    'user_id' => auth()->id(),
    'category_id' => $category->id,
    'amount' => $request->amount,
    'note' => $request->note ?? "Added {$request->type}",
    'transaction_date' => now(),
]);
```

### Retrieve Data (READ)

#### How Records Are Displayed:

**A. Dashboard View**
- **Controller**: `DashboardController@dashboard`
- **Data Retrieved**:
  1. **Current Month Expenses**: Sum of all expense transactions for current month
  2. **Daily Expenses**: Sum of expenses for today
  3. **Daily Expense Breakdown**: Individual transactions for today with category details
  4. **Recent Transactions**: Last 200 transactions grouped by date

**Code Example**:
```php
// ExpensesController.php
public function getCurrentMonthExpenses($userid) {
    return Transaction::where('user_id', $userid)
        ->whereHas('category', function($query){
            $query->where('type', 'expense');
        })
        ->whereMonth('transaction_date', now()->month)
        ->whereYear('transaction_date', now()->year)
        ->sum('amount');
}
```

**B. Transactions Page**
- **Route**: `/transactions`
- **Controller**: `ExpensesController@index`
- **Features**:
  - Paginated list of all transactions (10, 25, or 50 per page)
  - Filters by:
    - Type (income/expense/allowance)
    - Category
    - Date range (from/to)
    - Search query (note or category name)
  - Sorted by transaction date (newest first)
  - Shows: Date, Category, Amount, Note

**C. Reports Page**
- Displays aggregated transaction data with charts and visualizations
- Filters by date range and transaction type

### Update Data (UPDATE)

#### How Users Modify Transactions:

**A. Edit from Transactions List**
- **Location**: Transactions Page → Edit Button (per transaction)
- **Process**:
  1. User clicks edit icon on transaction row
  2. Modal/form opens with pre-filled data
  3. User modifies:
     - Category
     - Amount
     - Note
     - Transaction date
  4. System validates and updates Transaction record
  5. Refreshes list with updated data

**Note**: Update functionality uses standard Laravel form handling with validation.

**B. Bulk Updates**
- Categories can be changed for multiple transactions
- Date adjustments for backdated entries

### Delete Data (DELETE)

#### How Users Remove Transactions:

**A. Individual Deletion**
- **Location**: Transactions Page → Delete Button
- **Process**:
  1. User clicks delete/trash icon
  2. Confirmation prompt appears
  3. Upon confirmation, Transaction record is soft-deleted
  4. Record remains in database for audit trail
  5. Can be permanently deleted later

**B. Impact on Calculations**
- Deleted transactions are excluded from:
  - Cash balance calculations
  - Budget spent amounts
  - Reports and charts
  - Daily/monthly totals

---

## 2. BUDGETS MODULE

### Purpose
Set monthly spending limits per category and track progress to prevent overspending.

### Insert Data (CREATE)

#### How Users Add Budgets:

**Location**: Budgets Page → "Add" Button
**Controller**: `BudgetController@store`

**Process**:
1. User clicks "Add Budget" button
2. Modal opens with form:
   - Select Category (from user's expense categories)
   - Enter Amount (monthly limit)
   - Optional Note
3. System uses `updateOrCreate` to either:
   - Create new budget if category doesn't have one
   - Update existing budget for that category
4. Redirects to budgets page with success message

**Code Example**:
```php
// BudgetController.php - store()
$budget = Budget::updateOrCreate(
    ['user_id' => $userId, 'category_id' => $data['category_id']],
    ['amount' => $data['amount'], 'note' => $data['note'] ?? null]
);
```

**Key Feature**: One budget per category per user (prevents duplicates).

### Retrieve Data (READ)

#### How Budgets Are Displayed:

**A. Budgets Page**
- **Route**: `/budgets`
- **Controller**: `BudgetController@index`
- **Display**:
  - List of all user budgets sorted by category name
  - For each budget shows:
    - Category name and type
    - Budget limit amount
    - Amount spent (current month)
    - Progress bar (color-coded):
      - Green: < 80% spent
      - Yellow: 80-99% spent
      - Red: ≥ 100% spent (over budget)
    - Percentage used
    - Remaining amount
  - Summary cards:
    - Total Budget (sum of all limits)
    - Total Spent
    - Total Remaining
  - Alert banners:
    - Red alert: Categories over budget limit
    - Yellow warning: Categories near limit (≥ 80%)

**Code Example**:
```php
// BudgetController.php - index()
$budgets = $budgets->map(function($b) use ($spent) {
    $limit = (float) $b->amount;
    $spentAmt = $spent[$b->category_id] ?? 0.0;
    $percent = $limit > 0 ? min(100, ($spentAmt / $limit) * 100) : 0;
    $remaining = $limit - $spentAmt;

    return (object) [
        'id' => $b->id,
        'category_name' => $b->category->name,
        'amount' => $limit,
        'spent' => round($spentAmt, 2),
        'percent' => round($percent, 2),
        'remaining' => round($remaining, 2),
    ];
});
```

**B. Dashboard Budget Carousel**
- **Location**: Dashboard → Budgets Card
- **Features**:
  - Auto-scrolling carousel (3-second intervals)
  - Shows each budget with spent/limit comparison
  - Color-coded progress bars
  - Navigation dots for manual browsing
  - Displays "No budgets set" if empty with link to add

**SQL Join Logic**:
```php
// DashboardController.php
$budgets = Budget::where('budgets.user_id', $id)
    ->leftJoin('categories', 'budgets.category_id', '=', 'categories.id')
    ->leftJoin(
        DB::raw("(SELECT category_id, SUM(amount) as spent 
                  FROM transactions 
                  WHERE user_id = $id 
                  AND DATE_FORMAT(transaction_date, '%Y-%m') = '$currentMonth'
                  GROUP BY category_id) as t"),
        'budgets.category_id', '=', 't.category_id'
    )
    ->select(
        'budgets.*',
        'categories.name as category_name',
        DB::raw('COALESCE(t.spent, 0) as spent'),
        DB::raw('CASE WHEN budgets.amount > 0 
                 THEN ROUND((COALESCE(t.spent, 0) / budgets.amount) * 100) 
                 ELSE 0 END as percent')
    )
    ->get();
```

### Update Data (UPDATE)

#### How Users Modify Budgets:

**Location**: Budgets Page → Edit Button
**Controller**: `BudgetController@update`

**Process**:
1. User clicks edit icon on budget row
2. Modal opens with current values:
   - Category (can be changed)
   - Amount
   - Note
3. User modifies desired fields
4. System validates:
   - Checks budget belongs to user
   - Validates amount is numeric and ≥ 0
5. Updates Budget record
6. Recalculates percentages and alerts
7. Redirects with success message

**Code Example**:
```php
// BudgetController.php - update()
$budget = Budget::where('id', $id)->where('user_id', $userId)->first();
if (!$budget) {
    return redirect()->route('budgets.index')->with('error', 'Budget not found.');
}

$budget->amount = $data['amount'];
$budget->category_id = $data['category_id'];
$budget->note = $data['note'] ?? null;
$budget->save();
```

### Delete Data (DELETE)

#### How Users Remove Budgets:

**Note**: Current implementation doesn't have explicit delete functionality for budgets in the UI. Budgets can be:
- Set to 0 amount (effectively disabled)
- Replaced by updating with new category
- Manually deleted via database if needed

**Future Enhancement**: Add delete button with soft-delete capability.

---

## 3. BILLS & SUBSCRIPTIONS MODULE

### Purpose
Track recurring bills, subscriptions, and one-time payments with due date reminders.

### Insert Data (CREATE)

#### How Users Add Bills:

**Location**: Bills Page → "Add Bill" Button
**Controller**: `UpcomingBillsController@store`

**Process**:
1. User clicks "Add Bill" button
2. Form opens with fields:
   - Bill Name (e.g., "Netflix Subscription")
   - Amount
   - Due Date
   - Description (optional)
   - Is Recurring (checkbox)
   - Recurrence Type (if recurring):
     - Daily
     - Weekly
     - Monthly
     - Yearly
3. System validates all fields
4. Creates Bills record with:
   - `user_id`
   - `bill_name`
   - `amount`
   - `due_date`
   - `description`
   - `is_recurring` (boolean)
   - `recurrence_type_id`
   - `is_paid` (default: false)
5. Redirects with success message

**Code Example**:
```php
// UpcomingBillsController.php - store()
Bills::create([
    'user_id' => auth()->id(),
    'bill_name' => $request->bill_name,
    'amount' => $request->amount,
    'due_date' => $request->due_date,
    'description' => $request->description,
    'is_recurring' => $request->has('is_recurring') ? true : false,
    'recurrence_type_id' => $request->recurrence_type_id ?: null,
]);
```

### Retrieve Data (READ)

#### How Bills Are Displayed:

**A. Dashboard Upcoming Bills**
- **Controller**: `UpcomingBillsController@getUpcomingBills`
- **Display**: Next 3-5 upcoming unpaid bills sorted by due date
- **Shows**: Bill name, amount, due date, days until due

**Code Example**:
```php
public function getUpcomingBills($userid) {
    return Bills::with('recurrenceType')
        ->select('id','bill_name', 'amount', 'due_date','description','is_recurring','recurrence_type_id')
        ->where('user_id', $userid)
        ->whereDate('due_date', '>=', now())
        ->orderBy('due_date', 'asc')
        ->get();
}
```

**B. Bills Page**
- **Route**: `/bills`
- **Controller**: `UpcomingBillsController@index`
- **Features**:
  - Paginated list with filters:
    - **Status Filter**:
      - All bills
      - Overdue (unpaid & due date < today)
      - Upcoming (unpaid & due date ≥ today)
      - Paid
    - **Recurring Filter**: Show only recurring bills
    - **Search**: By bill name or description
  - **Sorting Options**:
    - Due date (default)
    - Amount
    - Bill name
    - Ascending/Descending
  - **Per-page Options**: 10, 25, 50 records
  - Visual indicators:
    - Red badge: Overdue bills
    - Green badge: Paid bills
    - Blue badge: Recurring bills

**C. Notifications**
- Dashboard shows alert for bills due in exactly 3 days
- Badge count shows number of bills needing attention

### Update Data (UPDATE)

#### How Users Modify Bills:

**A. Edit Bill Details**
- **Location**: Bills Page → Edit Button
- **Controller**: `UpcomingBillsController@getBill` (fetch), `update` (save)
- **Fields Editable**:
  - Bill name
  - Amount
  - Due date
  - Description
  - Recurring status
  - Recurrence type

**B. Update Description**
- **Controller**: `UpcomingBillsController@updateDescription`
- Quick update for adding notes/descriptions

**C. Mark as Paid**
- **Location**: Bills Page → "Mark Paid" Button
- **Controller**: `UpcomingBillsController@markPaid`
- **Process**:
  1. User clicks "Mark as Paid"
  2. System sets `is_paid = true`
  3. **If Recurring**: Automatically creates next occurrence:
     - Calculates next due date based on recurrence type:
       - Daily: +1 day
       - Weekly: +1 week
       - Monthly: +1 month
       - Yearly: +1 year
     - Creates new unpaid bill with same details
     - New bill starts fresh (unpaid status)
  4. Original bill stays marked as paid for records
  5. Redirects with success message

**Code Example**:
```php
// UpcomingBillsController.php - markPaid()
$bill->is_paid = true;
$bill->save();

if ($bill->is_recurring && $bill->recurrence_type_id) {
    $nextDue = null;
    $dt = \Carbon\Carbon::parse($bill->due_date);
    $type = $bill->recurrenceType->name ?? null;
    
    if ($type === 'monthly') {
        $nextDue = $dt->addMonth()->toDateString();
    } elseif ($type === 'weekly') {
        $nextDue = $dt->addWeek()->toDateString();
    }
    // ... other types
    
    if ($nextDue) {
        Bills::create([
            'user_id' => $bill->user_id,
            'bill_name' => $bill->bill_name,
            'amount' => $bill->amount,
            'due_date' => $nextDue,
            'description' => $bill->description,
            'is_recurring' => true,
            'recurrence_type_id' => $bill->recurrence_type_id,
        ]);
    }
}
```

### Delete Data (DELETE)

#### How Users Remove Bills:

**Location**: Bills Page → Delete/Trash Button
**Controller**: `UpcomingBillsController@destroy`

**Process**:
1. User clicks delete icon
2. Confirmation dialog appears
3. Upon confirmation:
   - System performs **soft delete** (Laravel's `delete()` method)
   - Record is marked as deleted but remains in database
   - Hidden from normal queries
4. Can be restored from "trash" if needed
5. Redirects with success message

**Code Example**:
```php
// UpcomingBillsController.php - destroy()
public function destroy(Request $request, $id) {
    $bill = Bills::find($id);
    if (!$bill) {
        return redirect()->back()->with('error', 'Bill not found.');
    }
    
    $bill->delete(); // Soft delete
    
    return redirect()->back()->with('success', 'Bill removed. It can be restored from the trash if needed.');
}
```

**Features**:
- Soft delete preserves data integrity
- Allows for accidental deletion recovery
- Maintains audit trail

---

## 4. CATEGORIES MODULE

### Purpose
Organize transactions and budgets by customizable categories (e.g., Food, Transport, Entertainment).

### Insert Data (CREATE)

#### Auto-Creation via Transactions:
- **Process**: When user adds expense/income with new category name:
  1. System checks if category exists for user
  2. If not found, creates new Category record:
     - `name`: User-provided name
     - `type`: 'expense', 'income', or 'allowance'
     - `user_id`: Current user
  3. Links transaction to category

**Code Example**:
```php
$category = Categories::firstOrCreate(
    ['name' => $request->category_name, 'user_id' => auth()->id()],
    ['type' => 'expense']
);
```

### Retrieve Data (READ)

- Categories appear in:
  - Dropdown menus for transaction creation
  - Budget category selection
  - Filter options on transactions page
  - Chart legends and breakdowns

### Update Data (UPDATE)

- Category names and types can be modified
- Changes reflect across all associated transactions and budgets

### Delete Data (DELETE)

- Categories can be deleted if no associated transactions/budgets
- Cascading rules prevent orphaned records

---

## 5. DAILY LIMITS MODULE

### Purpose
Set and track daily spending limits to control daily expenses.

### Insert Data (CREATE)

**Controller**: `DailyLimitController`
- Users set daily spending limit amount
- System creates DailyLimit record per user

### Retrieve Data (READ)

- Dashboard displays:
  - Today's limit
  - Today's spending
  - Remaining amount
  - Visual progress indicator
- Weekly and monthly limit tracking charts

### Update Data (UPDATE)

- Users can adjust daily limit amount
- Takes effect immediately for current day

### Delete Data (DELETE)

- Limits can be removed/disabled
- Historical data preserved for reporting

---

## 6. REPORTS & ANALYTICS MODULE

### Purpose
Provide visual insights and data analysis for financial patterns and trends.

### Retrieve Data (READ)

**Controller**: `ReportsController`

**Features**:
1. **Date Range Selection**: Custom from/to dates
2. **Type Filter**: Income, Expense, Allowance, or All
3. **Visualizations**:
   - **Pie Charts**: Spending by category
   - **Bar Charts**: Monthly trends
   - **Line Charts**: Weekly spending patterns
   - **Summary Cards**: Total income, expenses, savings
4. **Top Expenses**: Highest spending categories
5. **Burn Rate**: Daily/weekly/monthly spending averages
6. **Export Options**: Download reports as PDF/CSV (future enhancement)

**Data Aggregation Examples**:
```php
// Weekly Chart
public function getWeeklyChart($userId) {
    $labels = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    $dailyExpenses = [];
    
    $startOfWeek = now()->startOfWeek(0);
    foreach($labels as $index => $label) {
        $date = $startOfWeek->copy()->addDays($index);
        $dailyAmount = Transaction::where('user_id', $userId)
            ->whereHas('category', fn($q) => $q->where('type','expense'))
            ->whereDate('transaction_date', $date)
            ->sum('amount');
        $dailyExpenses[] = $dailyAmount;
    }
    
    return ['labels' => $labels, 'data' => $dailyExpenses];
}
```

---

## Technical Implementation Details

### Database Structure

**Key Tables**:
1. **users**: User accounts with authentication
2. **transactions**: All financial transactions (income/expense/allowance)
3. **categories**: Transaction categorization
4. **budgets**: Monthly spending limits per category
5. **bills**: Recurring and one-time bill tracking
6. **recurrence_types**: Types of bill recurrence (daily/weekly/monthly/yearly)
7. **daily_limits**: Daily spending limit settings
8. **sessions**: User session management (database driver)

### Security Features

1. **Authentication Required**: All CRUD operations require logged-in user
2. **User Isolation**: All queries filtered by `user_id` (no cross-user data access)
3. **CSRF Protection**: Laravel form tokens on all POST/PUT/DELETE requests
4. **Input Validation**: Server-side validation on all data submissions
5. **SQL Injection Prevention**: Eloquent ORM with parameterized queries
6. **Session Security**: Database sessions with encryption support

### Data Validation

**Example Validations**:
```php
// Transaction Creation
$request->validate([
    'category_name' => 'required|string|max:255',
    'amount' => 'required|numeric|min:0',
    'note' => 'required|string|max:255',
]);

// Budget Creation/Update
$data = $request->validate([
    'category_id' => 'required|integer',
    'amount' => 'required|numeric|min:0',
    'note' => 'nullable|string|max:255',
]);

// Bill Creation
$request->validate([
    'bill_name' => 'required|string|max:255',
    'amount' => 'required|numeric|min:0',
    'due_date' => 'required|date',
    'description' => 'nullable|string|max:500',
    'is_recurring' => 'nullable|boolean',
    'recurrence_type_id' => 'nullable|exists:recurrence_types,id',
]);
```

### User Experience Features

1. **Real-time Updates**: AJAX requests for instant feedback
2. **Modal Forms**: Quick data entry without page reload
3. **Auto-scrolling Carousel**: Budget visualization on dashboard
4. **Color-coded Alerts**: Visual warnings for budget overages
5. **Search & Filters**: Easy data discovery
6. **Pagination**: Performance optimization for large datasets
7. **Responsive Design**: Mobile-friendly interface
8. **Toast Notifications**: Success/error messages

---

## Summary

The FinanceTracker application provides comprehensive CRUD functionality across all major features:

### INSERT (CREATE)
- ✅ Add transactions (expenses, income, allowance)
- ✅ Create budgets with limits per category
- ✅ Add bills (one-time and recurring)
- ✅ Set daily spending limits
- ✅ Auto-create categories on-the-fly

### RETRIEVE (READ)
- ✅ View all transactions with advanced filtering
- ✅ Display budget progress with visual indicators
- ✅ Show upcoming and overdue bills
- ✅ Dashboard with real-time financial overview
- ✅ Charts and reports for data analysis

### UPDATE (MODIFY)
- ✅ Edit transaction details
- ✅ Modify budget amounts and categories
- ✅ Update bill information
- ✅ Mark bills as paid (auto-generates next occurrence if recurring)
- ✅ Adjust daily limits

### DELETE (REMOVE)
- ✅ Delete transactions with audit trail
- ✅ Remove bills (soft delete for recovery)
- ✅ Disable/remove budgets
- ✅ Clean up old data while preserving history

All operations are secured with user authentication, input validation, and proper error handling to ensure data integrity and user privacy.
