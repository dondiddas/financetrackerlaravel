<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>@yield('title', 'My App')</title>
    <!-- CSS -->
    
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body style="background:#EBEBDF;">
    <div id="wrapper" class="d-flex">
        @include('partials.sidebar')

        <div id="page-content-wrapper" class="flex-grow-1 p-3"> 

            @yield('content')
        </div>
    </div>

    {{-- JS --}}
    {{-- <link rel="stylesheet" href="{{ url('resources/js/app.js') }}"> --}}
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/chart.js') }}"></script>

    <script>
// document.getElementById('sidebarToggle').addEventListener('click', function () {
//     document.getElementById('sidebar').classList.toggle('active');
// });
</script>
<script>
let lastScroll = 0;
const mobileNav = document.getElementById("mobileNav");

window.addEventListener("scroll", function () {
    let currentScroll = window.scrollY;

    if (currentScroll > lastScroll) {
        mobileNav.style.transform = "translateY(100%)";
    } else {
        mobileNav.style.transform = "translateY(0)";
    }

    lastScroll = currentScroll;
});
</script>


<div class="modal fade" id="globalFeedbackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-body text-center py-4">
                <div id="globalFeedbackIcon" class="mb-3 fs-1"></div>
                <h5 id="globalFeedbackTitle" class="mb-2"></h5>
                <div id="globalFeedbackMessage" class="small text-muted mb-3"></div>
                <div id="globalFeedbackTip" class="alert alert-info small" style="display:none;"></div>
                <div class="d-grid">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function(){
        const feedback = {
            success: @json(session('success')),
            error: @json(session('error')),
            tip: @json(session('error_tip')),
            info: @json(session('info')),
            validationErrors: @json($errors->any() ? $errors->all() : []),
        };

        let type = null;
        let title = '';
        let message = '';
        let tip = '';

        if (feedback.success) {
            type = 'success';
            title = 'Success';
            message = feedback.success;
        } else if (feedback.error || feedback.validationErrors.length) {
            type = 'error';
            title = 'Something went wrong';
            message = feedback.error || (feedback.validationErrors.length ? feedback.validationErrors[0] : 'An error occurred.');
            tip = feedback.tip || (feedback.validationErrors.length ? 'Please correct the highlighted fields and try again.' : 'Try again or contact support if the issue persists.');
        } else if (feedback.info) {
            type = 'info';
            title = 'Info';
            message = feedback.info;
        }

        if (type) {
            const iconEl = document.getElementById('globalFeedbackIcon');
            const titleEl = document.getElementById('globalFeedbackTitle');
            const msgEl = document.getElementById('globalFeedbackMessage');
            const tipEl = document.getElementById('globalFeedbackTip');

            if (type === 'success') {
                iconEl.innerHTML = '<i class="fa-solid fa-circle-check text-success"></i>';
                titleEl.className = 'mb-2';
                titleEl.innerText = title;
                msgEl.innerText = message;
                tipEl.style.display = 'none';
            } else if (type === 'error') {
                iconEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-danger"></i>';
                titleEl.className = 'mb-2 text-danger';
                titleEl.innerText = title;
                msgEl.innerText = message;
                if (tip) {
                    tipEl.style.display = 'block';
                    tipEl.innerText = tip;
                }
            } else if (type === 'info') {
                iconEl.innerHTML = '<i class="fa-solid fa-circle-info text-primary"></i>';
                titleEl.className = 'mb-2';
                titleEl.innerText = title;
                msgEl.innerText = message;
                tipEl.style.display = 'none';
            }

            try { console.log('GlobalFeedback debug', { type, title, message, tip, feedback }); } catch(e){}

            function showModalWhenReady() {
                try {
                    if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
                        const modalEl = document.getElementById('globalFeedbackModal');
                        const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
                        modal.show();
                        return true;
                    }
                } catch (e) {}
                return false;
            }

            if (!showModalWhenReady()) {
                let attempts = 0;
                const id = setInterval(() => {
                    attempts++;
                    if (showModalWhenReady() || attempts > 10) clearInterval(id);
                }, 100);
            }
        }
    })();
</script>

</body>
</html>


