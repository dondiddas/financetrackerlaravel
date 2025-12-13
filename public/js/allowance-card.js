document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.allowance-card').forEach(card => {
        card.addEventListener('click', function (e) {

            if (e.target.closest('.dropdown')) return;

            const modalEl = document.getElementById('allowanceovermodal');
            if (!modalEl) return;

            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    });
});
