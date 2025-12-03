(function(){
    const addBtn = document.getElementById('addBudgetBtn');
    const addModalEl = document.getElementById('addBudgetModal');
    const addForm = document.getElementById('addBudgetForm');

    if (addBtn && addModalEl) {
        addBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = new bootstrap.Modal(addModalEl);
            modal.show();
        });
    }

    if (addForm) {
        addForm.addEventListener('submit', function(e){
            // prefer normal submit to reuse existing budget store route (not implemented here).
            // Fallback: allow form to submit normally.
        });
    }

})();
