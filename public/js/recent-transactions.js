(function(){
    // Poll interval in milliseconds
    const POLL_INTERVAL = 10000; // 10 seconds
    const containerId = 'recentActivityContainer';
    const endpoint = '/recent-transactions';

    function formatGroupHTML(groups) {
        // groups is expected to be an object where keys are date strings and values are arrays
        if (!groups || Object.keys(groups).length === 0) {
            return '<div class="text-muted">No recent activity</div>';
        }

        let html = '';
        for (const dateKey of Object.keys(groups)) {
            const items = groups[dateKey];
            html += `<div class="small text-muted mt-2">${escapeHtml(dateKey)}</div>`;
            html += '<ul class="list-unstyled mb-2">';
            for (const t of items) {
                const categoryNameRaw = (t.category && t.category.name) ? t.category.name : (t.category_name ? t.category_name : null);
                const category = categoryNameRaw ? escapeHtml(categoryNameRaw) : 'No Category';
                const categoryTypeRaw = (t.category && t.category.type) ? t.category.type : (t.category_type ? t.category_type : '');
                const type = categoryTypeRaw ? ` <small class="text-muted">${escapeHtml(categoryTypeRaw)}</small>` : '';
                const note = t.note ? `<div class="small text-muted">${escapeHtml(t.note)}</div>` : '';
                let amountStr = '0.00';
                if (typeof t.amount === 'number') {
                    amountStr = Number(t.amount).toFixed(2);
                } else if (typeof t.amount === 'string') {
                    // server may return formatted string with commas; use as-is
                    amountStr = t.amount;
                } else if (t.amount) {
                    amountStr = String(t.amount);
                }
                const amount = escapeHtml(amountStr);

                html += `\n<li class="d-flex justify-content-between align-items-center border-bottom py-2 px-2">`;
                html += `<div class="flex-grow-1">`;
                html += `<div class="fw-semibold">${category}${type}</div>`;
                html += `${note}`;
                html += `</div>`;
                html += `<div class="fw-semibold text-end">₱${amount}</div>`;
                html += `</li>`;
            }
            html += '</ul>';
        }
        return html;
    }

    function escapeHtml(unsafe) {
        if (unsafe === null || typeof unsafe === 'undefined') return '';
        return String(unsafe)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    async function fetchAndRender() {
        try {
            const resp = await fetch(endpoint, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!resp.ok) {
                // don't spam console on server errors, but log once
                console.warn('Could not fetch recent transactions:', resp.status);
                return;
            }

            const payload = await resp.json();
            const data = payload && payload.groups ? payload.groups : payload;
            const container = document.getElementById(containerId);
            if (!container) return;

            // The endpoint returns an object keyed by date -> array of transactions
            const html = formatGroupHTML(data || {});
            container.innerHTML = html;
        } catch (err) {
            console.warn('Error fetching recent transactions:', err);
        }
    }

    // Start polling after DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            fetchAndRender();
            // expose so other scripts can trigger an immediate refresh
            window.fetchRecentTransactions = fetchAndRender;
            setInterval(fetchAndRender, POLL_INTERVAL);
        });
    } else {
        fetchAndRender();
        window.fetchRecentTransactions = fetchAndRender;
        setInterval(fetchAndRender, POLL_INTERVAL);
    }

    // Also listen for a custom event so other scripts can broadcast changes
    try {
        document.addEventListener('recentActivityChanged', function() {
            if (window.fetchRecentTransactions) window.fetchRecentTransactions();
        });
    } catch (err) {
        // ignore
    }

})();
