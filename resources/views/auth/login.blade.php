@extends('layout.app')

@section('title','Login')

@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <h3 class="card-title">Login</h3>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required class="form-control" />
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="{{ route('register') }}" class="btn btn-link ms-3">Register</a>
                </div>
            </form>
        </div>
    </div>

    @if(session('registration_success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
            <div id="regToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('registration_success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var toastEl = document.getElementById('regToast');
                if (toastEl) {
                    var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                    toast.show();
                }
            });
        </script>
        @endpush
    @endif
@endsection
