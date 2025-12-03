@extends('layouts.app')

@section('title','Settings')

@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <h3 class="card-title">Settings</h3>

            @if(session('profile_success'))
                <div class="alert alert-success">{{ session('profile_success') }}</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="dark_mode" value="0">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="dark_mode" name="dark_mode" value="1" {{ old('dark_mode', $user->dark_mode) ? 'checked' : '' }}>
                    <label class="form-check-label" for="dark_mode">Enable dark mode</label>
                </div>

                <button type="submit" class="btn btn-primary">Save settings</button>
            </form>
        </div>
    </div>
@endsection
