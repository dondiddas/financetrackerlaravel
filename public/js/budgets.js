document.addEventListener('DOMContentLoaded', function () {
    // Helpers
    const qs = (sel, ctx = document) => ctx.querySelector(sel);
    const qsa = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

    function updateProgressBars() {
        const items = qsa('.budget-item');
        let totalAmount = 0;
        let totalSpent = 0;

        items.forEach(item => {
            const amount = parseFloat(item.dataset.amount) || 0;
            const spent = parseFloat(item.dataset.spent) || 0;
            const percent = amount > 0 ? Math.round((spent / amount) * 100) : 0;
            const bar = qs('.progress-fill', item);
            const percentLabel = qs('.percent', item);

            // Color coding
            bar.classList.remove('bg-success','bg-warning','bg-danger');
            if (percent >= 100) bar.classList.add('bg-danger');
            else if (percent >= 80) bar.classList.add('bg-warning');
            else bar.classList.add('bg-success');

            // Animate width
            setTimeout(() => { bar.style.width = percent + '%'; }, 50);
            if (percentLabel) percentLabel.textContent = percent + '%';

            totalAmount += amount;
            totalSpent += spent;
        });

        // Update overview totals
        const totalBudgetEl = qs('#totalBudget');
        const totalSpentEl = qs('#totalSpent');
        const totalRemainingEl = qs('#totalRemaining');
        if (totalBudgetEl) totalBudgetEl.textContent = Number(totalAmount).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        if (totalSpentEl) totalSpentEl.textContent = Number(totalSpent).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        if (totalRemainingEl) totalRemainingEl.textContent = Number(totalAmount - totalSpent).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});

        renderAlerts(items);
    }

    function renderAlerts(items) {
        const alertsSection = qs('#budgetAlerts');
        if (!alertsSection) return;
        // Build alerts client-side for any dynamic updates
        const over = [];
        const near = [];
        items.forEach(item => {
            const amount = parseFloat(item.dataset.amount) || 0;
            const spent = parseFloat(item.dataset.spent) || 0;
            const percent = amount > 0 ? Math.round((spent / amount) * 100) : 0;
            const name = qs('strong', item)?.textContent || 'Category';
            if (percent >= 100) over.push(name);
            else if (percent >= 80) near.push(name);
        });

        // Remove any client-generated alerts first
        const clientAlerts = alertsSection.querySelectorAll('.client-generated');
        clientAlerts.forEach(a => a.remove());

        if (over.length) {
            const div = document.createElement('div');
            div.className = 'alert alert-danger shadow-sm d-flex align-items-start gap-2 client-generated';
            div.role = 'alert';
            div.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i><div><strong>${over.length} category(ies) over limit</strong><div class="small">You exceeded the budget for ${over.join(', ')}.</div></div>`;
            alertsSection.appendChild(div);
        }
        if (near.length) {
            const div = document.createElement('div');
            div.className = 'alert alert-warning shadow-sm d-flex align-items-start gap-2 client-generated';
            div.role = 'alert';
            div.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i><div><strong>${near.length} category(ies) near limit</strong><div class="small">Approaching limit for ${near.join(', ')}.</div></div>`;
            alertsSection.appendChild(div);
        }
    }

    // Month filter behavior: redirect with ?month=YYYY-MM
    const monthFilter = qs('#monthFilter');
    if (monthFilter) {
        monthFilter.addEventListener('change', function () {
            const selected = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('month', selected);
            window.location.href = url.toString();
        });
    }

    // Add button opens modal
    const addBtn = qs('#addBudgetBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const addModal = new bootstrap.Modal(document.getElementById('addBudgetModal'));
            addModal.show();
        });
    }

    // Edit buttons populate modal
    qsa('.editBudgetBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const amount = this.dataset.amount;
            const note = this.dataset.note || '';
            const category = this.dataset.category || '';
            const form = qs('#editBudgetForm');
            if (!form) return;
            qs('#editBudgetId').value = id;
            qs('#editAmount').value = amount;
            qs('#editNote').value = note;
            qs('#editBudgetCategory').value = category;

            // Set action to RESTful update route if following standard pattern
            // e.g., /budgets/{id}
            form.action = `/budgets/${id}`;
        });
    });

    // Initialize visuals
    updateProgressBars();

});
