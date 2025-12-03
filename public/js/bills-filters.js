(function(){
    // Debounce helper
    function debounce(fn, wait){
        let t;
        return function(){
            const args = arguments;
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        }
    }

    function qsFromForm(form){
        const params = new URLSearchParams();
        Array.from(new FormData(form).entries()).forEach(([k,v]) => {
            if (v === null || v === undefined) return;
            if (v === '') return;
            params.append(k, v);
        });
        return params.toString();
    }

    async function fetchAndReplace(url){
        try{
            const res = await fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}});
            if (!res.ok) return;
            const text = await res.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(text, 'text/html');

            // Replace table body
            const newTable = doc.querySelector('#billsTable');
            const oldTable = document.querySelector('#billsTable');
            if (newTable && oldTable) oldTable.innerHTML = newTable.innerHTML;

            // Replace pagination
            const newPag = doc.querySelector('#billsPagination');
            const oldPag = document.querySelector('#billsPagination');
            if (newPag && oldPag) oldPag.innerHTML = newPag.innerHTML;

            // Replace summary
            const newSummary = doc.querySelector('#billsSummary');
            const oldSummary = document.querySelector('#billsSummary');
            if (newSummary && oldSummary) oldSummary.innerHTML = newSummary.innerHTML;

        } catch (err){
            console.warn('bills fetch failed', err);
        }
    }

    document.addEventListener('DOMContentLoaded', function(){
        const form = document.querySelector('.bills-toolbar form');
        if (!form) return;

        // Add mobile clear button inside search input
        const searchInput = form.querySelector('input[type="search"][name="q"]');
        if (searchInput) {
            const group = searchInput.closest('.input-group');
            if (group) {
                const clearBtn = document.createElement('button');
                clearBtn.type = 'button';
                clearBtn.className = 'btn btn-outline-secondary d-md-none';
                clearBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                clearBtn.style.marginLeft = '6px';
                clearBtn.title = 'Clear';
                clearBtn.addEventListener('click', function(){
                    searchInput.value = '';
                    // trigger instant filter
                    debouncedFilter();
                });
                group.parentNode.insertBefore(clearBtn, group.nextSibling);
            }
        }

        // Collapsible filters for very small screens
        const filterControls = document.getElementById('filterControls');
        const filtersToggle = document.getElementById('filtersToggle');
        if (filterControls && filtersToggle) {
            filtersToggle.addEventListener('click', function(e){
                e.preventDefault();
                filterControls.classList.toggle('d-none');
            });
        }

        // Debounced instant filtering
        const applyUrlBase = window.location.pathname || '/bills';

        async function doFilter(){
            const qs = qsFromForm(form);
            const url = applyUrlBase + (qs ? ('?' + qs) : '');
            // Push history so back/forward works
            try { history.replaceState(null, '', url); } catch(e){}
            await fetchAndReplace(url);
        }

        const debouncedFilter = debounce(doFilter, 450);

        // wire inputs
        form.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('input', function(e){
                // immediate on select change
                if (el.tagName.toLowerCase() === 'select') {
                    debouncedFilter();
                } else {
                    debouncedFilter();
                }
            });
        });

        // Apply button (non-AJAX fallback) keep sticky class behavior
        const applyBtn = form.querySelector('button[type="submit"]');
        if (applyBtn) {
            applyBtn.classList.add('apply-btn');
        }

        // Wire reset link to clear and fetch
        const resetLink = form.querySelector('a[href="' + window.location.pathname + '"]');
        if (resetLink) {
            resetLink.addEventListener('click', function(e){
                // default will navigate but also clear in-place for AJAX
                setTimeout(()=>{ debouncedFilter(); }, 50);
            });
        }
    });
})();
