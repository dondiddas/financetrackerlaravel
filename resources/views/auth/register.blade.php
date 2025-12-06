@extends('layout.app')

@section('title','Register')

@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <h3 class="card-title">Register</h3>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input id="first_name" type="text" name="first_name" required class="form-control" />
                </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input id="middle_name" type="text" name="middle_name" required class="form-control" />
                </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input id="last_name" type="text" name="last_name" required class="form-control" />
                </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="form-control" />
                </div>

                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <a href="{{ route('login') }}" class="btn btn-link ms-3">Login</a>
                </div>
            </form>
        </div>
    </div>
@endsection
