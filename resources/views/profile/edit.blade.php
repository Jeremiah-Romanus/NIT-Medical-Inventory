@extends('layouts.layout')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Update your basic account details safely.')

@section('content')
    <div class="row justify-content-start">
        <div class="col-lg-7 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Profile details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Role</label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $user->role === 'procurement' ? 'Procurement Officer' : ucfirst($user->role) }}"
                                disabled
                            >
                            <div class="form-text">
                                Your role is managed by the system administrator.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-2"></i>
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
