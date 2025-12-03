(function(){
    const form = document.getElementById('transactionsFilters');
    const table = document.getElementById('transactionsTable');
    const pagination = document.getElementById('transactionsPagination');
    const summary = document.getElementById('transactionsSummary');
    const perPageSelect = document.getElementById('perPageSelect');
    const clearBtn = document.getElementById('clearFilters');

    if (!form) return;

    let debounceTimer = null;
    function fetchAndRender() {
        const params = new URLSearchParams(new FormData(form));
        params.set('per', perPageSelect.value || '10');
        const url = window.location.pathname + '?' + params.toString();

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                // Parse the returned HTML and extract fragments
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.getElementById('transactionsTable');
                const newPagination = doc.getElementById('transactionsPagination');
                const newSummary = doc.getElementById('transactionsSummary');

                if (newTable && table) table.innerHTML = newTable.innerHTML;
                if (newPagination && pagination) pagination.innerHTML = newPagination.innerHTML;
                if (newSummary && summary) summary.innerHTML = newSummary.innerHTML;

                // Update URL without reloading
                window.history.replaceState({}, '', url);
            })
            .catch(err => console.warn('Error fetching transactions:', err));
    }

    function debounceFetch() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchAndRender, 350);
    }

    form.addEventListener('input', debounceFetch);
    form.addEventListener('change', debounceFetch);
    perPageSelect.addEventListener('change', fetchAndRender);

    clearBtn.addEventListener('click', () => {
        form.querySelectorAll('input, select').forEach(el => {
            if (el.name) el.value = '';
        });
        perPageSelect.value = '10';
        fetchAndRender();
    });

    // Handle pagination clicks (event delegation)
    document.addEventListener('click', (e) => {
        const a = e.target.closest('#transactionsPagination a');
        if (a) {
            e.preventDefault();
            const url = new URL(a.href);
            const params = new URLSearchParams(url.search);
            // apply page to form and fetch
            // append current filters to page url
            params.set('per', perPageSelect.value || '10');
            window.history.replaceState({}, '', url.pathname + '?' + params.toString());
            fetch(url.pathname + '?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.getElementById('transactionsTable');
                    const newPagination = doc.getElementById('transactionsPagination');
                    const newSummary = doc.getElementById('transactionsSummary');

                    if (newTable && table) table.innerHTML = newTable.innerHTML;
                    if (newPagination && pagination) pagination.innerHTML = newPagination.innerHTML;
                    if (newSummary && summary) summary.innerHTML = newSummary.innerHTML;
                });
        }
    });

    // AJAX submit add income and add expense forms
    const addIncomeForm = document.getElementById('addIncomeForm');
    const addExpenseForm = document.getElementById('addExpenseForm');

    function ajaxSubmit(formEl, onSuccess) {
        formEl.addEventListener('submit', function(e){
            e.preventDefault();
            const fd = new FormData(formEl);
            fetch(formEl.action, {
                method: formEl.method || 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            }).then(r => r.json())
            .then(data => {
                if (data.success) {
                    // close modal if bootstrap is available
                    const modalEl = formEl.closest('.modal');
                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.hide();
                    }
                    fetchAndRender();
                    if (window.fetchRecentTransactions) window.fetchRecentTransactions();
                    if (onSuccess) onSuccess(data);
                } else {
                    alert(data.message || 'Saved');
                }
            }).catch(err => {
                console.warn('submit error', err);
                formEl.submit(); // fallback
            });
        });
    }

    if (addIncomeForm) ajaxSubmit(addIncomeForm);
    if (addExpenseForm) ajaxSubmit(addExpenseForm);

})();
