function selectKPI(event, type, el) {
    event.preventDefault();       // Prevent link navigation
    event.stopPropagation();      // Prevent modal

    const card = el.closest('.card');
    const dropdown = el.closest('.dropdown');
    const button = dropdown.querySelector('button');
    const items = dropdown.querySelectorAll('.dropdown-item');

    // Update active item
    items.forEach(i => i.classList.remove('active'));
    el.classList.add('active');

    // Update dropdown button label (append ' Overview')
    const label = type.charAt(0).toUpperCase() + type.slice(1) + ' Overview';
    button.textContent = label;

    // Update KPI display (allowance | income | all)
    const kpiDisplay = document.getElementById('kpi-display');
    if(type === 'allowance') {
        kpiDisplay.innerHTML = `
            <h1 class="fw-semibold mb-0 fs-2 fs-md-1">₱${card.dataset.allowance}</h1>
            <p class="text-secondary mt-1 mb-0 small">₱${card.dataset.lastAllowance} Previous Month</p>
        `;
    } else if (type === 'income'){
        kpiDisplay.innerHTML = `
            <h1 class="fw-semibold mb-0 fs-2 fs-md-1">₱${card.dataset.income}</h1>
            <p class="text-secondary mt-1 mb-0 small">₱${card.dataset.lastIncome} Previous Month</p>
        `;
    } else if (type === 'all'){
        kpiDisplay.innerHTML = `
            <h1 class="fw-semibold mb-0 fs-2 fs-md-1">₱${card.dataset.all}</h1>
            <p class="text-secondary mt-1 mb-0 small">₱${card.dataset.lastAll} Previous Month</p>
        `;
    }

    // Programmatically close the dropdown (we used stopPropagation so bootstrap won't auto-close)
    try {
        const dd = bootstrap.Dropdown.getOrCreateInstance(button);
        dd.hide();
    } catch (err) {
        // ignore if bootstrap not available
        console.warn('Dropdown hide failed', err);
    }

    // Update Cash Balance display according to selection
    try {
        const cashCard = document.getElementById('cashBalanceCard');
        const cashValueEl = document.getElementById('cashBalanceValue');
        if (cashCard && cashValueEl) {
            let val = cashCard.dataset.cashAllowance || '0.00';
            if (type === 'income') val = cashCard.dataset.cashIncome || '0.00';
            if (type === 'all') val = cashCard.dataset.cashAll || '0.00';
            cashValueEl.textContent = '₱' + val;
        }
    } catch (err) {
        console.warn('Failed to update cash balance', err);
    }
}

function openAllowanceModal(event) {
    const modal = new bootstrap.Modal(document.getElementById('allowanceovermodal'));
    modal.show();
}
