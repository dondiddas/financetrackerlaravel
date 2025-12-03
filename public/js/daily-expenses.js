(function(){
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('dailyExpensesForm');
        if (!form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(form);
            const action = form.action;

            const tokenInput = form.querySelector('input[name="_token"]');
            const csrf = tokenInput ? tokenInput.value : (document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content')) || '';

            try {
                const res = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: formData
                });

                if (!res.ok) {
                    window.location.reload();
                    return;
                }

                const contentType = res.headers.get('content-type') || '';
                if (contentType.indexOf('application/json') !== -1) {
                    const data = await res.json();
                    if (data.success) {
                        const tbody = document.getElementById('dailyExpensesList');
                        if (tbody && data.expense) {
                            // remove placeholder 'no expenses' row if present
                            const placeholder = tbody.querySelector('tr.no-expenses');
                            if (placeholder) placeholder.remove();

                            const tr = document.createElement('tr');
                            const date = data.expense.transaction_date || '';
                            const category = data.expense.category_name || 'No Category';
                            const noteHtml = data.expense.note ? '<div class="small text-muted">' + data.expense.note + '</div>' : '';
                            tr.innerHTML = '<td class="text-muted">' + date + '</td>' +
                                           '<td>' + category + noteHtml + '</td>' +
                                           '<td class="fw-semibold text-end">₱' + Number(data.expense.amount).toFixed(2) + '</td>';
                            tbody.prepend(tr);
                        }

                        if (data.totals && data.totals.daily !== undefined) {
                            const totalEl = document.getElementById('dailyTotal');
                            const cardEl = document.getElementById('dailyExpensesCardValue');
                            if (totalEl) totalEl.textContent = '₱' + Number(data.totals.daily).toFixed(2);
                            if (cardEl) cardEl.textContent = '₱' + Number(data.totals.daily).toFixed(2);
                        }

                        form.reset();
                        const existing = document.getElementById('ajaxSuccessAlert');
                        if (existing) existing.remove();
                        const alert = document.createElement('div');
                        alert.id = 'ajaxSuccessAlert';
                        alert.className = 'alert alert-success py-2 small mb-2';
                        alert.textContent = data.message || 'Expense added successfully!';
                        form.parentNode.insertBefore(alert, form);
                        setTimeout(function(){ alert.style.transition = 'opacity 0.5s'; alert.style.opacity = '0'; setTimeout(function(){ alert.remove(); }, 500); }, 2500);
                        // trigger recent activity refresh if available
                        if (window.fetchRecentTransactions && typeof window.fetchRecentTransactions === 'function') {
                            try { window.fetchRecentTransactions(); } catch (e) { console.warn('recent refresh failed', e); }
                        }
                    } else {
                        alert(data.message || 'Unable to add expense');
                    }
                } else {
                    window.location.reload();
                }

            } catch (err) {
                console.error(err);
                alert('Network error while saving expense');
                window.location.reload();
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    });
})();
